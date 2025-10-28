<?php
// Check if this is an AJAX request first
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjax) {
    // For AJAX requests, disable error display to prevent HTML output before JSON
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
} else {
    // For direct browser requests, enable error display for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

require_once './pdo_conexion.php';
require('./fpdf/fpdf.php'); // You might need to install FPDF library

// Check if reports directory exists, if not create it
$reportsDir = './reports';
if (!file_exists($reportsDir)) {
    mkdir($reportsDir, 0777, true);
}

// Ensure no output has been sent before
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Check if animal ID is provided
if (!isset($_GET['tagid']) || empty($_GET['tagid'])) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error: No animal ID provided']);
        exit;
    } else {
        die('Error: No animal ID provided');
    }
}

$tagid = $_GET['tagid'];

// Connect to database using PDO (from pdo_conexion.php)
// The $conn variable is already defined in pdo_conexion.php as PDO connection
// We need to create a mysqli connection for this script
try {
    $mysqli_conn = new mysqli($servername, $username, $password, $dbname);
    if ($mysqli_conn->connect_error) {
        throw new Exception('Connection failed: ' . $mysqli_conn->connect_error);
    }
    $conn = $mysqli_conn; // Use mysqli connection for this script
    error_log("Database connection established successfully");
} catch (Exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
        exit;
    } else {
        die('Connection failed: ' . $e->getMessage());
    }
}

// Set charset to UTF-8 for proper character encoding in PDF
mysqli_set_charset($conn, "utf8");

// Log successful connection
error_log("Database connection established successfully");

// Fetch animal basic info
$sql_animal = "SELECT * FROM ovino WHERE tagid = ?";
$stmt_animal = $conn->prepare($sql_animal);
if (!$stmt_animal) {
    error_log('Failed to prepare animal query: ' . mysqli_error($conn));
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to prepare animal query: ' . mysqli_error($conn)]);
        exit;
    } else {
        die('Failed to prepare animal query: ' . mysqli_error($conn));
    }
}

$stmt_animal->bind_param('s', $tagid);
$stmt_animal->execute();
$result_animal = $stmt_animal->get_result();

if ($result_animal->num_rows === 0) {
    error_log('Animal not found with tagid: ' . $tagid);
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error: Animal not found']);
        exit;
    } else {
        die('Error: Animal not found');
    }
}

$animal = $result_animal->fetch_assoc();
error_log("Animal data retrieved successfully: " . $animal['nombre'] . " (" . $animal['tagid'] . ")");

// Create PDF
class PDF extends FPDF
{
    // Animal data to access in header
    protected $animalData;
    
    // Set animal data
    function setAnimalData($data) {
        $this->animalData = $data;
    }
    
    // Helper function to ensure proper UTF-8 encoding for searchable text
    function EncodeText($text) {
        // Handle null or empty values
        if ($text === null || $text === '') {
            return '';
        }
        
        // Convert to string if needed
        $text = (string)$text;
        
        // Remove control characters and normalize text
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // Convert text to proper encoding for FPDF
        if (mb_detect_encoding($text, 'UTF-8', true)) {
            // Text is UTF-8, convert to ISO-8859-1 for FPDF compatibility
            return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
        }
        return $text;
    }
    
    // Override Cell method to ensure proper text encoding
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        // Ensure text is properly formatted for searchability
        $txt = trim($txt); // Remove extra whitespace
        $txt = preg_replace('/\s+/', ' ', $txt); // Normalize whitespace
        parent::Cell($w, $h, $this->EncodeText($txt), $border, $ln, $align, $fill, $link);
    }
    
    // Add method to set optimal font for searchability
    function SetSearchableFont($family='Arial', $style='', $size=10) {
        $this->SetFont($family, $style, $size);
        // Ensure text rendering mode is optimal for searchability
        $this->_out('2 Tr'); // Set text rendering mode to fill (most searchable)
    }
    
    // Page header
    function Header()
    {
        // Only show header on first page
        if ($this->PageNo() == 1) {
            // Set margins and padding
            $this->SetMargins(10, 10, 10);
            
            // Draw a subtle header background
            $this->SetFillColor(240, 240, 240);
            $this->Rect(0, 0, 210, 35, 'F');
            
            // Logo with adjusted position - with error handling and fallbacks
            $logoLoaded = false;
            $logoPaths = [
                './images/default_image.png',
                './images/Registroca-logo.png',
                './images/whatsapp-logo.jpg'
            ];
            
            foreach ($logoPaths as $logoPath) {
                if (file_exists($logoPath)) {
                    try {
                        $this->Image($logoPath, 10, 6, 30);
                        $logoLoaded = true;
                        error_log('Successfully loaded logo: ' . $logoPath);
                        break;
                    } catch (Exception $e) {
                        error_log('Failed to load logo ' . $logoPath . ': ' . $e->getMessage());
                        continue;
                    }
                }
            }
            
            // If no image logo loaded, create a text-based logo
            if (!$logoLoaded) {
                $this->SetFont('Arial', 'B', 14);
                $this->SetTextColor(0, 100, 0); // Dark green
                $this->SetXY(10, 10);
                $this->Cell(30, 8, 'ANIMALIA', 0, 0, 'C');
                $this->SetXY(10, 18);
                $this->SetFont('Arial', '', 8);
                $this->Cell(30, 8, 'Sistema Ganadero', 0, 0, 'C');
                error_log('Used text-based logo fallback');
            }
            
            // Add current date on upper right
            $this->SetSearchableFont('Arial', '', 10);
            $this->SetTextColor(80, 80, 80); // Gray color for date
            $current_date = date('d/m/Y H:i:s');
            $this->SetXY(150, 8); // Position on upper right
            $this->Cell(50, 8, 'Fecha: ' . $current_date, 0, 0, 'R');
            
            // Add a decorative line
            $this->SetDrawColor(0, 128, 0); // Green line
            $this->Line(10, 35, 200, 35);
            
            // Main report title
            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor(0, 80, 0); // Darker green for main title
            
            $this->Ln(5);
            
            // Title section with animal name - larger, bold font
            $this->SetSearchableFont('Arial', 'B', 16);
            $this->SetTextColor(0, 100, 0); // Dark green color for title
            // Center alignment for animal name
            $this->Cell(0, 10, mb_strtoupper($this->animalData['nombre']), 0, 1, 'C');
            
            // Tag ID in a slightly smaller font, still professional
            $this->SetSearchableFont('Arial', 'B', 12);
            $this->SetTextColor(80, 80, 80); // Gray color for tag ID
            // Center alignment for Tag ID
            $this->Cell(0, 10, 'Tag ID: ' . $this->animalData['tagid'], 0, 1, 'C');
            $this->Ln(5);
            
            // Add animal images
            if (!empty($this->animalData)) {
                // Photo section title
                $this->SetFont('Arial', 'B', 12);
                $this->SetTextColor(0, 0, 0);
                $this->Cell(0, 5, 'CONDICION CORPORAL', 0, 1, 'C');
                $this->Ln(1);
                
                // Start position for images
                $y = 70; // Adjusted for the new title
                $imageWidth = 60;
                $spacing = 5;
                
                // Left position for first image
                $x1 = 10;
                // Left position for second image
                $x2 = $x1 + $imageWidth + $spacing;
                // Left position for third image
                $x3 = $x2 + $imageWidth + $spacing;
                
                // Add first image if exists
                if (!empty($this->animalData['image'])) {
                    $imagePath = $this->animalData['image'];
                    $imagePath = str_replace('\\', '/', $imagePath); // Normalize path
                    
                    // Paths to try
                    $pathsToTry = [
                        $imagePath,
                        './' . ltrim($imagePath, './'),
                        '../' . $imagePath,
                        $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($imagePath, '/')
                    ];
                    
                    foreach ($pathsToTry as $path) {
                        if (file_exists($path)) {
                            $this->Image($path, $x1, $y, $imageWidth);
                            break;
                        }
                    }
                }
                
                // Add second image if exists
                if (!empty($this->animalData['image2'])) {
                    $imagePath = $this->animalData['image2'];
                    $imagePath = str_replace('\\', '/', $imagePath); // Normalize path
                    
                    // Paths to try
                    $pathsToTry = [
                        $imagePath,
                        './' . ltrim($imagePath, './'),
                        '../' . $imagePath,
                        $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($imagePath, '/')
                    ];
                    
                    foreach ($pathsToTry as $path) {
                        if (file_exists($path)) {
                            $this->Image($path, $x2, $y, $imageWidth);
                            break;
                        }
                    }
                }
                
                // Add third image if exists
                if (!empty($this->animalData['image3'])) {
                    $imagePath = $this->animalData['image3'];
                    $imagePath = str_replace('\\', '/', $imagePath); // Normalize path
                    
                    // Paths to try
                    $pathsToTry = [
                        $imagePath,
                        './' . ltrim($imagePath, './'),
                        '../' . $imagePath,
                        $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($imagePath, '/')
                    ];
                    
                    foreach ($pathsToTry as $path) {
                        if (file_exists($path)) {
                            $this->Image($path, $x3, $y, $imageWidth);
                            break;
                        }
                    }
                }
                
                // Add image captions
                $this->SetFont('Arial', 'I', 8);
                $this->SetY($y + $imageWidth + 2);
                $this->SetX($x1);
                $this->Cell($imageWidth, 10, 'Foto Principal', 0, 0, 'C');
                $this->SetX($x2);
                $this->Cell($imageWidth, 10, 'Foto Secundaria', 0, 0, 'C');
                $this->SetX($x3);
                $this->Cell($imageWidth, 10, 'Foto Adicional', 0, 0, 'C');
                
                // Add extra space after images
                $this->Ln(10);
            }
        }
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetSearchableFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Draw a circle
    function Circle($x, $y, $r, $style='D')
    {
        $this->Ellipse($x, $y, $r, $r, $style);
    }
    
    // Draw an ellipse
    function Ellipse($x, $y, $rx, $ry, $style='D')
    {
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
            
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x)*$k, ($h-$y)*$k,
            ($x+$lx)*$k, ($h-$y)*$k,
            ($x+$rx)*$k, ($h-$y+$ly)*$k,
            ($x+$rx)*$k, ($h-$y+$ry)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k, ($h-$y+$ry+$ly)*$k,
            ($x+$lx)*$k, ($h-$y+$ry+$ry)*$k,
            ($x)*$k, ($h-$y+$ry+$ry)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k, ($h-$y+$ry+$ry)*$k,
            ($x-$rx)*$k, ($h-$y+$ry+$ly)*$k,
            ($x-$rx)*$k, ($h-$y+$ry)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x-$rx)*$k, ($h-$y+$ly)*$k,
            ($x-$lx)*$k, ($h-$y)*$k,
            ($x)*$k, ($h-$y)*$k,
            $op));
    }

    // Function to styled chapter titles
    function ChapterTitle($title)
    {
        // Add animal tagid and nombre to the title (except for farm-wide statistics)
        $animalInfo = '';
        if ($this->animalData && isset($this->animalData['tagid']) && isset($this->animalData['nombre'])) {
            // Don't add animal info for farm-wide statistics (any title containing "(Finca)" or distribution reports)
            if (strpos($title, '(Finca)') === false && $title !== 'Distribucion por Raza' && $title !== 'Distribucion de Animales por Grupo' && $title !== 'Indice de Conversion Alimenticia (ICA)' && $title !== 'Resumen de Vacunaciones y Tratamientos' && $title !== 'Duracion de Gestaciones' && $title !== 'Hembras Sin Registro de Gestacion' && $title !== 'Animales con mas de 365 Dias Desde Ultimo Parto' && $title !== 'ESTADISTICAS DE LA FINCA') {
                $animalInfo = ' ' . $this->animalData['tagid'] . ' (' . $this->animalData['nombre'] . ')';
            }
        }
        $fullTitle = $title . $animalInfo;
        
        $this->SetSearchableFont('Arial', 'B', 12);
        $this->SetFillColor(0, 100, 0); // Darker green
        $this->SetTextColor(255, 255, 255); // White text
        
        // Check if this is a main section title (all caps)
        if ($title == 'PRODUCCION' || $title == 'ALIMENTACION' || $title == 'SALUD' || 
            $title == 'REPRODUCCION' || $title == 'ESTADISTICAS DE LA FINCA') {
            // Main section titles - centered, larger font, more space before/after
            $this->SetSearchableFont('Arial', 'B', 14);
            $this->Ln(5); // Extra space before main sections
            $this->Cell(0, 10, $fullTitle, 0, 1, 'C', true);
            $this->Ln(5); // Extra space after main sections
        } else {
            // Regular subsection titles - left aligned
            $this->Cell(0, 8, $fullTitle, 0, 1, 'L', true);
            $this->Ln(3);
        }
        
        $this->SetTextColor(0, 0, 0); // Reset to black text
    }

    // Data table
    function DataTable($header, $data)
    {
        // Column widths
        $w = array(40, 50, 40, 50);
        
        // Header
        $this->SetSearchableFont('Arial', 'B', 10);
        $this->SetFillColor(50, 120, 50); // Darker green for header
        $this->SetTextColor(255, 255, 255); // White text for better contrast
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetTextColor(0, 0, 0); // Reset to black text for data
        
        // Data
        $this->SetSearchableFont('Arial', '', 9); // Match SimpleTable font size
        $this->SetFillColor(245, 250, 245); // Match SimpleTable fill color
        $fill = false;
        foreach ($data as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $this->Cell($w[$i], 6, $row[$i], 1, 0, 'C', $fill); // Center align all cells
            }
            $this->Ln();
            $fill = !$fill;
        }
        $this->Ln(5);
    }
    
    // Simple table for two columns
    function SimpleTable($header, $data)
    {
        // Determine column count and adjust widths accordingly
        $columnCount = count($header);
        
        // Default column widths
        if ($columnCount == 2) {
            $w = array(60, 120); // Original 2-column layout
        } elseif ($columnCount == 3) {
            $w = array(50, 50, 80); // 3-column layout (date, value, price)
        } elseif ($columnCount == 4) {
            $w = array(40, 60, 40, 40); // 4-column layout
        } else {
            // Create automatic column widths
            $pageWidth = $this->GetPageWidth() - 20; // Adjust for margins
            $w = array_fill(0, $columnCount, $pageWidth / $columnCount);
        }
        
        // Check if this is a table that needs special formatting
        if (in_array('Precio ($/Kg)', $header) || in_array('Dosis', $header)) {
            // Special column widths for tables with price or dose fields
            if ($columnCount == 3) {
                $w = array(45, 60, 75); // Date, Weight/Product, Price/Dose
            }
        }
        
        // Header with background
        $this->SetSearchableFont('Arial', 'B', 10);
        $this->SetFillColor(50, 120, 50); // Darker green for header
        $this->SetTextColor(255, 255, 255); // White text for better contrast
        for ($i = 0; $i < $columnCount; $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetTextColor(0, 0, 0); // Reset to black text for data
        
        // Data
        $this->SetSearchableFont('Arial', '', 9); // Slightly smaller font to fit more text
        $this->SetFillColor(245, 250, 245); // Lighter green tint
        $fill = false;
        
        foreach ($data as $row) {
            // Make sure we have the right number of cells
            $rowCount = count($row);
            for ($i = 0; $i < $columnCount; $i++) {
                // If the cell exists in data, display it, otherwise display empty cell
                $cellContent = ($i < $rowCount) ? $row[$i] : '';
                
                // Center align all data cells for consistency
                $align = 'C';
                
                $this->Cell($w[$i], 6, $cellContent, 1, 0, $align, $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        
        // Add space after table
        $this->Ln(5);
    }
}

// Create PDF instance
try {
    $pdf = new PDF();
    $pdf->setAnimalData($animal);
    error_log("PDF instance created successfully");
} catch (Exception $e) {
    error_log('Failed to create PDF instance: ' . $e->getMessage());
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to create PDF instance: ' . $e->getMessage()]);
        exit;
    } else {
        die('Failed to create PDF instance: ' . $e->getMessage());
    }
}

// Set UTF-8 metadata for better searchability
try {
    $pdf->SetTitle('Reporte Veterinario - ' . $animal['nombre'] . ' (' . $animal['tagid'] . ')', true);
    $pdf->SetAuthor('Sistema Animalia', true);
    $pdf->SetSubject('Historial Veterinario Completo', true);
    $pdf->SetKeywords('veterinario, ganado, ovino, historial, ' . $animal['tagid'] . ', ' . $animal['nombre'], true);
    $pdf->SetCreator('Animalia - Sistema de Gestión Ganadera', true);
    error_log("PDF metadata set successfully");
} catch (Exception $e) {
    error_log('Failed to set PDF metadata: ' . $e->getMessage());
    // Continue anyway as this is not critical
}

$pdf->AliasNbPages();
try {
    $pdf->AddPage();
    error_log("First PDF page added successfully");
} catch (Exception $e) {
    error_log('Failed to add first page: ' . $e->getMessage());
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to add first page: ' . $e->getMessage()]);
        exit;
    } else {
        die('Failed to add first page: ' . $e->getMessage());
    }
}

// Basic animal information
$pdf->ChapterTitle('Datos');
$header = array('Concepto', 'Descripcion');
$data = array(
    array('Tag ID', $animal['tagid']),
    array('Nombre', $animal['nombre']),
    array('Fecha Nacimiento', $animal['fecha_nacimiento']),
    array('Genero', $animal['genero']),
    array('Raza', $animal['raza']),
    array('Etapa', $animal['etapa']),
    array('Grupo', $animal['grupo']),
    array('Estatus', $animal['estatus'])
);
$pdf->SimpleTable($header, $data);

// Peso history
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Pesos del animal');
$sql_weight = "SELECT oh_peso_tagid, oh_peso_fecha, oh_peso_animal, oh_peso_precio FROM oh_peso WHERE oh_peso_tagid = ? ORDER BY oh_peso_fecha DESC";
$stmt_weight = $conn->prepare($sql_weight);
if (!$stmt_weight) {
    error_log('Failed to prepare weight query: ' . $conn->error);
    $result_weight = false;
} else {
    $stmt_weight->bind_param('s', $tagid);
    $stmt_weight->execute();
    $result_weight = $stmt_weight->get_result();
}

if ($result_weight && $result_weight->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Peso (kg)', 'Precio ($/Kg)');
    $data = array();
    while ($row = $result_weight->fetch_assoc()) {
        $data[] = array($row['oh_peso_tagid'], $row['oh_peso_fecha'], $row['oh_peso_animal'], $row['oh_peso_precio']);
    }
    $pdf->SimpleTable($header, $data);

} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay regisros de pesajes', 0, 1);
    $pdf->Ln(2);
}   

// Leche
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Produccion Leche del animal');
$sql_leche = "SELECT oh_leche_tagid, oh_leche_fecha_inicio, oh_leche_fecha_fin, oh_leche_peso, oh_leche_precio FROM oh_leche WHERE oh_leche_tagid = ? ORDER BY oh_leche_fecha_inicio DESC";
$stmt_leche = $conn->prepare($sql_leche);
if (!$stmt_leche) {
    error_log('Failed to prepare leche query: ' . $conn->error);
    $result_leche = false;
} else {
    $stmt_leche->bind_param('s', $tagid);
    $stmt_leche->execute();
    $result_leche = $stmt_leche->get_result();
}

if ($result_leche && $result_leche->num_rows > 0) {
    $header = array('Tag ID', 'Fecha Inicio', 'Fecha Fin', 'Peso (kg)', 'Precio ($/Kg)');
    $data = array();
    while ($row = $result_leche->fetch_assoc()) {
        $data[] = array($row['oh_leche_tagid'], $row['oh_leche_fecha_inicio'], $row['oh_leche_fecha_fin'], $row['oh_leche_peso'], $row['oh_leche_precio']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de produccion de leche', 0, 1);
    $pdf->Ln(2);
}

// Concentrado
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Consumo de Concentrado');
$sql_concentrado = "SELECT oh_concentrado_tagid, oh_concentrado_fecha_inicio, oh_concentrado_fecha_fin, oh_concentrado_racion, oh_concentrado_costo FROM oh_concentrado WHERE oh_concentrado_tagid = ? ORDER BY oh_concentrado_fecha_inicio DESC";
$stmt_concentrado = $conn->prepare($sql_concentrado);
if (!$stmt_concentrado) {
    error_log('Failed to prepare concentrado query: ' . $conn->error);
    $result_concentrado = false;
} else {
    $stmt_concentrado->bind_param('s', $tagid);
    $stmt_concentrado->execute();
    $result_concentrado = $stmt_concentrado->get_result();
}

if ($result_concentrado && $result_concentrado->num_rows > 0) {
    $header = array('Tag ID', 'Fecha Inicio', 'Fecha Fin', 'Consumo Concentrado Peso (kg)', 'Precio ($/Kg)');
    $data = array();
    while ($row = $result_concentrado->fetch_assoc()) {
        $data[] = array($row['oh_concentrado_tagid'], $row['oh_concentrado_fecha_inicio'], $row['oh_concentrado_fecha_fin'], $row['oh_concentrado_racion'], $row['oh_concentrado_costo']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de consumo de concentrado', 0, 1);
    $pdf->Ln(2);
}

// Salt
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Consumo de Sal');
$sql_salt = "SELECT oh_sal_tagid, oh_sal_fecha_inicio, oh_sal_fecha_fin, oh_sal_producto, oh_sal_racion, oh_sal_costo FROM oh_sal WHERE oh_sal_tagid = ? ORDER BY oh_sal_fecha_inicio DESC";
$stmt_salt = $conn->prepare($sql_salt);
if (!$stmt_salt) {
    error_log('Failed to prepare salt query: ' . $conn->error);
    // Skip this section if query fails
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Error: No se pudieron cargar los datos de sal', 0, 1);
    $result_salt = false;
} else {
    $stmt_salt->bind_param('s', $tagid);
    $stmt_salt->execute();
    $result_salt = $stmt_salt->get_result();
}

if ($result_salt && $result_salt->num_rows > 0) {
    $header = array('Tag ID', 'Fecha Inicio', 'Fecha Fin', 'Producto', 'Racion (Kg)', 'Costo ($/Kg)');
    $data = array();
    while ($row = $result_salt->fetch_assoc()) {
        $data[] = array($row['oh_sal_tagid'], $row['oh_sal_fecha_inicio'], $row['oh_sal_fecha_fin'], $row['oh_sal_producto'], $row['oh_sal_racion'], $row['oh_sal_costo']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de consumo de sal', 0, 1);
    $pdf->Ln(2);
}

// Molasses
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Consumo de Melaza');
$sql_molasses = "SELECT oh_melaza_tagid, oh_melaza_fecha_inicio, oh_melaza_fecha_fin, oh_melaza_producto, oh_melaza_racion, oh_melaza_costo FROM oh_melaza WHERE oh_melaza_tagid = ? ORDER BY oh_melaza_fecha_inicio DESC";
$stmt_molasses = $conn->prepare($sql_molasses);
if (!$stmt_molasses) {
    error_log('Failed to prepare molasses query: ' . $conn->error);
    $result_molasses = false;
} else {
    $stmt_molasses->bind_param('s', $tagid);
    $stmt_molasses->execute();
    $result_molasses = $stmt_molasses->get_result();
}

if ($result_molasses && $result_molasses->num_rows > 0) {
    $header = array('Tag ID', 'Fecha Inicio', 'Fecha Fin', 'Producto', 'Racion (Kg)', 'Costo ($/Kg)');
    $data = array();
    while ($row = $result_molasses->fetch_assoc()) {
        $data[] = array($row['oh_melaza_tagid'], $row['oh_melaza_fecha_inicio'], $row['oh_melaza_fecha_fin'], $row['oh_melaza_producto'], $row['oh_melaza_racion'], $row['oh_melaza_costo']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de consumo de melaza', 0, 1);
    $pdf->Ln(2);
}

// Vaccination - Aftosa
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Vacunacion Aftosa');
$pdf->ChapterTitle('Aftosa');
$sql_aftosa = "SELECT oh_aftosa_tagid, oh_aftosa_fecha, oh_aftosa_producto, oh_aftosa_dosis FROM oh_aftosa WHERE oh_aftosa_tagid = ? ORDER BY oh_aftosa_fecha DESC";
$stmt_aftosa = $conn->prepare($sql_aftosa);
if (!$stmt_aftosa) {
    error_log('Failed to prepare aftosa query: ' . $conn->error);
    $result_aftosa = false;
} else {
    $stmt_aftosa->bind_param('s', $tagid);
    $stmt_aftosa->execute();
    $result_aftosa = $stmt_aftosa->get_result();
}

if ($result_aftosa && $result_aftosa->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_aftosa->fetch_assoc()) {
        $data[] = array($row['oh_aftosa_tagid'], $row['oh_aftosa_fecha'], $row['oh_aftosa_producto'], $row['oh_aftosa_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de vacunacion aftosa', 0, 1);
    $pdf->Ln(2);
}

// Vaccination - Brucelosis
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Vacunacion Brucelosis');
$sql_bruc = "SELECT oh_brucelosis_tagid, oh_brucelosis_fecha, oh_brucelosis_producto, oh_brucelosis_dosis FROM oh_brucelosis WHERE oh_brucelosis_tagid = ? ORDER BY oh_brucelosis_fecha DESC";
$stmt_bruc = $conn->prepare($sql_bruc);
if (!$stmt_bruc) {
    error_log('Failed to prepare brucelosis query: ' . $conn->error);
    $result_bruc = false;
} else {
    $stmt_bruc->bind_param('s', $tagid);
    $stmt_bruc->execute();
    $result_bruc = $stmt_bruc->get_result();
}

if ($result_bruc && $result_bruc->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_bruc->fetch_assoc()) {
        $data[] = array($row['oh_brucelosis_tagid'], $row['oh_brucelosis_fecha'], $row['oh_brucelosis_producto'], $row['oh_brucelosis_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de vacunacion brucelosis', 0, 1);
    $pdf->Ln(2);
}

// Vaccination - Clostridiosis
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Vacunacion Clostridiosis');
$sql_clostridiosis = "SELECT oh_clostridiosis_tagid, oh_clostridiosis_fecha, oh_clostridiosis_producto, oh_clostridiosis_dosis FROM oh_clostridiosis WHERE oh_clostridiosis_tagid = ? ORDER BY oh_clostridiosis_fecha DESC";
$stmt_clostridiosis = $conn->prepare($sql_clostridiosis);
if (!$stmt_clostridiosis) {
    error_log('Failed to prepare clostridiosis query: ' . $conn->error);
    $result_clostridiosis = false;
} else {
    $stmt_clostridiosis->bind_param('s', $tagid);
    $stmt_clostridiosis->execute();
    $result_clostridiosis = $stmt_clostridiosis->get_result();
}

if ($result_clostridiosis && $result_clostridiosis->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_clostridiosis->fetch_assoc()) {
        $data[] = array($row['oh_clostridiosis_tagid'], $row['oh_clostridiosis_fecha'], $row['oh_clostridiosis_producto'], $row['oh_clostridiosis_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de vacunacion clostridiosis', 0, 1);
    $pdf->Ln(2);
}

// Vaccination - Neumonia
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Vacunacion Neumonia');
$sql_neumonia = "SELECT oh_neumonia_tagid, oh_neumonia_fecha, oh_neumonia_producto, oh_neumonia_dosis FROM oh_neumonia WHERE oh_neumonia_tagid = ? ORDER BY oh_neumonia_fecha DESC";
$stmt_neumonia = $conn->prepare($sql_neumonia);
if (!$stmt_neumonia) {
    error_log('Failed to prepare neumonia query: ' . $conn->error . ' (Table may not exist)');
    $result_neumonia = false;
} else {
    $stmt_neumonia->bind_param('s', $tagid);
    $stmt_neumonia->execute();
    $result_neumonia = $stmt_neumonia->get_result();
}

if ($result_neumonia && $result_neumonia->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_neumonia->fetch_assoc()) {
        $data[] = array($row['oh_neumonia_tagid'], $row['oh_neumonia_fecha'], $row['oh_neumonia_producto'], $row['oh_neumonia_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de vacunacion neumonia', 0, 1);
    $pdf->Ln(2);
}

// Vaccination - Ectima
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Vacunacion Ectima');
$sql_ectima = "SELECT oh_ectima_tagid, oh_ectima_fecha, oh_ectima_producto, oh_ectima_dosis FROM oh_ectima WHERE oh_ectima_tagid = ? ORDER BY oh_ectima_fecha DESC";
$stmt_ectima = $conn->prepare($sql_ectima);
if (!$stmt_ectima) {
    error_log('Failed to prepare ectima query: ' . $conn->error . ' (Table may not exist)');
    $result_ectima = false;
} else {
    $stmt_ectima->bind_param('s', $tagid);
    $stmt_ectima->execute();
    $result_ectima = $stmt_ectima->get_result();
}

if ($result_ectima && $result_ectima->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_ectima->fetch_assoc()) {
        $data[] = array($row['oh_ectima_tagid'], $row['oh_ectima_fecha'], $row['oh_ectima_producto'], $row['oh_ectima_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de vacunacion Ectima', 0, 1);
    $pdf->Ln(2);
}

// Parasites Treatment
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Tratamiento Parasitos');
$sql_para = "SELECT oh_parasitos_tagid, oh_parasitos_fecha, oh_parasitos_producto, oh_parasitos_dosis FROM oh_parasitos WHERE oh_parasitos_tagid = ? ORDER BY oh_parasitos_fecha DESC";
$stmt_para = $conn->prepare($sql_para);
if (!$stmt_para) {
    error_log('Failed to prepare parasitos query: ' . $conn->error);
    $result_para = false;
} else {
    $stmt_para->bind_param('s', $tagid);
    $stmt_para->execute();
    $result_para = $stmt_para->get_result();
}

if ($result_para && $result_para->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_para->fetch_assoc()) {
        $data[] = array($row['oh_parasitos_tagid'], $row['oh_parasitos_fecha'], $row['oh_parasitos_producto'], $row['oh_parasitos_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de tratamiento parasitos', 0, 1);
    $pdf->Ln(2);
}

// Garrapatas Treatment
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Tratamiento Garrapatas');
$sql_tick = "SELECT oh_garrapatas_tagid, oh_garrapatas_fecha, oh_garrapatas_producto, oh_garrapatas_dosis FROM oh_garrapatas WHERE oh_garrapatas_tagid = ? ORDER BY oh_garrapatas_fecha DESC";
$stmt_tick = $conn->prepare($sql_tick);
if (!$stmt_tick) {
    error_log('Failed to prepare garrapatas query: ' . $conn->error);
    $result_tick = false;
} else {
    $stmt_tick->bind_param('s', $tagid);
    $stmt_tick->execute();
    $result_tick = $stmt_tick->get_result();
}

if ($result_tick && $result_tick->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Producto', 'Dosis (ml)');
    $data = array();
    while ($row = $result_tick->fetch_assoc()) {
        $data[] = array($row['oh_garrapatas_tagid'], $row['oh_garrapatas_fecha'], $row['oh_garrapatas_producto'], $row['oh_garrapatas_dosis']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de tratamiento garrapatas', 0, 1);
    $pdf->Ln(2);
}

// Inseminacion
$pdf->AddPage();
$pdf->ChapterTitle('REPRODUCCION');
$pdf->ChapterTitle('Tabla Inseminaciones');
$sql_ins = "SELECT oh_inseminacion_tagid, oh_inseminacion_fecha, oh_inseminacion_numero FROM oh_inseminacion WHERE oh_inseminacion_tagid = ? ORDER BY oh_inseminacion_fecha DESC";
$stmt_ins = $conn->prepare($sql_ins);
if (!$stmt_ins) {
    error_log('Failed to prepare inseminacion query: ' . $conn->error);
    $result_ins = false;
} else {
    $stmt_ins->bind_param('s', $tagid);
    $stmt_ins->execute();
    $result_ins = $stmt_ins->get_result();
}

if ($result_ins && $result_ins->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Inseminacion Nro.');
    $data = array();
    while ($row = $result_ins->fetch_assoc()) {
        $data[] = array($row['oh_inseminacion_tagid'], $row['oh_inseminacion_fecha'], $row['oh_inseminacion_numero']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de inseminaciones', 0, 1);
    $pdf->Ln(2);
}

// Gestacion
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Gestaciones');
$sql_preg = "SELECT oh_gestacion_tagid, oh_gestacion_fecha, oh_gestacion_numero FROM oh_gestacion WHERE oh_gestacion_tagid = ? ORDER BY oh_gestacion_fecha DESC";
$stmt_preg = $conn->prepare($sql_preg);
if (!$stmt_preg) {
    error_log('Failed to prepare gestacion query: ' . $conn->error);
    $result_preg = false;
} else {
    $stmt_preg->bind_param('s', $tagid);
    $stmt_preg->execute();
    $result_preg = $stmt_preg->get_result();
}

if ($result_preg && $result_preg->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Gestacion Nro.');
    $data = array();
    while ($row = $result_preg->fetch_assoc()) {
        $data[] = array($row['oh_gestacion_tagid'], $row['oh_gestacion_fecha'], $row['oh_gestacion_numero']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No registros de gestacion encontrados', 0, 1);
    $pdf->Ln(2);
}

// Parto
$pdf->AddPage();
$pdf->ChapterTitle('Tabla Partos');
$sql_birth = "SELECT oh_parto_tagid, oh_parto_fecha, oh_parto_numero FROM oh_parto WHERE oh_parto_tagid = ? ORDER BY oh_parto_fecha DESC";
$stmt_birth = $conn->prepare($sql_birth);
if (!$stmt_birth) {
    error_log('Failed to prepare parto query: ' . $conn->error);
    $result_birth = false;
} else {
    $stmt_birth->bind_param('s', $tagid);
    $stmt_birth->execute();
    $result_birth = $stmt_birth->get_result();
}

if ($result_birth && $result_birth->num_rows > 0) {
    $header = array('Tag ID', 'Fecha', 'Parto Nro.');
    $data = array();
    while ($row = $result_birth->fetch_assoc()) {
        $data[] = array($row['oh_parto_tagid'], $row['oh_parto_fecha'], $row['oh_parto_numero']);
    }
    $pdf->SimpleTable($header, $data);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de partos', 0, 1);
    $pdf->Ln(2);
}

// Add Monthly Farm Weight Statistics
$pdf->AddPage();
$pdf->ChapterTitle('ESTADISTICAS DE LA FINCA');

// Add Breed Distribution Statistics
$pdf->ChapterTitle('Razas');

// SQL to get breed distribution
$sql_breeds = "SELECT 
    raza,
    COUNT(*) as total_animales,
    ROUND((COUNT(*) * 100.0) / (SELECT COUNT(*) FROM ovino WHERE estatus = 'Activo'), 1) as porcentaje
FROM ovino 
WHERE estatus = 'Activo'
GROUP BY raza
ORDER BY total_animales DESC";

$result_breeds = $conn->query($sql_breeds);

if ($result_breeds->num_rows > 0) {
    $header = array('Raza', 'Total Animales', 'Porcentaje (%)');
    $data = array();
    $total_animals = 0;
    
    while ($row = $result_breeds->fetch_assoc()) {
        $data[] = array(
            $row['raza'] ?: 'No Especificada',  // Handle NULL or empty breed
            $row['total_animales'],
            number_format($row['porcentaje'], 1)
        );
        $total_animals += $row['total_animales'];
    }
    
    // Add total row
    $data[] = array(
        'TOTAL',
        $total_animals,
        '100.0'
    );
    
    $pdf->SimpleTable($header, $data);
    
    // Add explanatory note
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->Cell(0, 5, 'Nota: Porcentajes calculados sobre el total de animales activos en el sistema.', 0, 1);
    $pdf->Ln(2);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de animales para generar la distribucion por razas', 0, 1);
    $pdf->Ln(2);
}

// Add Animal Distribution by Group
$pdf->ChapterTitle('Grupos');

// SQL to get animal distribution by group
$sql_groups = "SELECT 
    grupo,
    COUNT(*) as total_animales,
    ROUND((COUNT(*) * 100.0) / (SELECT COUNT(*) FROM ovino WHERE estatus = 'Activo'), 1) as porcentaje
FROM ovino 
WHERE estatus = 'Activo'
GROUP BY grupo
ORDER BY total_animales DESC";

$result_groups = $conn->query($sql_groups);

if ($result_groups->num_rows > 0) {
    $header = array('Grupo', 'Total Animales', 'Porcentaje (%)');
    $data = array();
    $total_animals = 0;
    
    while ($row = $result_groups->fetch_assoc()) {
        $data[] = array(
            $row['grupo'],
            $row['total_animales'],
            number_format($row['porcentaje'], 1)
        );
        $total_animals += $row['total_animals'];
    }
    
    // Add total row
    $data[] = array(
        'TOTAL',
        $total_animals,
        '100.0'
    );
    
    $pdf->SimpleTable($header, $data);
    
    // Add explanatory note
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->Cell(0, 5, 'Nota: Porcentajes calculados sobre el total de animales activos en el sistema.', 0, 1);
    $pdf->Ln(2);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de animales para generar la distribucion por grupos', 0, 1);
    $pdf->Ln(2);
}


//Estadisticas------------------

// Add Monthly Weight Statistics
$pdf->AddPage();
$pdf->ChapterTitle('Produccion Carnica');

// SQL to get monthly total weight with averages for multiple weights in same month
$sql_monthly = "WITH MonthlyWeights AS (
    SELECT 
        DATE_FORMAT(oh_peso_fecha, '%Y-%m-01') as primer_dia_mes,
        oh_peso_tagid,
        AVG(oh_peso_animal) as peso_promedio_animal
    FROM oh_peso 
    GROUP BY DATE_FORMAT(oh_peso_fecha, '%Y-%m-01'), oh_peso_tagid
)
SELECT 
    primer_dia_mes as mes,
    COUNT(DISTINCT oh_peso_tagid) as total_animales,
    ROUND(SUM(peso_promedio_animal), 2) as peso_total,
    ROUND(AVG(peso_promedio_animal), 2) as peso_promedio
FROM MonthlyWeights
GROUP BY primer_dia_mes
ORDER BY primer_dia_mes DESC
LIMIT 12";  // Last 12 months

$result_monthly = $conn->query($sql_monthly);

if ($result_monthly->num_rows > 0) {
    $header = array('Mes', 'Total Animales', 'Peso Total (kg)', 'Promedio (kg)');
    $data = array();
    $total_weight = 0;
    $total_months = 0;
    $min_weight = PHP_FLOAT_MAX;
    $max_weight = 0;
    
    while ($row = $result_monthly->fetch_assoc()) {
        // Format the month to Spanish format
        $date = DateTime::createFromFormat('Y-m-d', $row['mes']);
        $mes_espanol = strftime('%B %Y', $date->getTimestamp());
        $mes_espanol = ucfirst(mb_strtolower($mes_espanol, 'UTF-8'));
        
        $data[] = array(
            $mes_espanol,
            $row['total_animales'],
            number_format($row['peso_total'], 2),
            number_format($row['peso_promedio'], 2)
        );
        
        // Track statistics
        $total_weight += $row['peso_promedio'];
        $total_months++;
        $min_weight = min($min_weight, $row['peso_promedio']);
        $max_weight = max($max_weight, $row['peso_promedio']);
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add statistics
    if ($total_months > 0) {
        $overall_average = $total_weight / $total_months;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(5);
        $pdf->Cell(0, 6, 'ESTADISTICAS DE PESO:', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, sprintf('Promedio General: %.2f kg', $overall_average), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Peso Minimo Mensual: %.2f kg', $min_weight), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Peso Maximo Mensual: %.2f kg', $max_weight), 0, 1, 'L');
    }
    
    // Add explanatory note
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, 'Notas:
- Los pesos se calculan como un promedio mensual por animal.
- Si hay varios pesos para un animal en el mismo mes, se usa el promedio.
- El peso total es la suma de los pesos promedio de todos los animales del mes.
- El promedio mensual es el peso total dividido por el numero de animales.
- Las estadisticas muestran la tendencia de peso en los ultimos 12 meses.', 0, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de peso para generar estadisticas mensuales', 0, 1);
    $pdf->Ln(2);
}

// Add Monthly Milk Production Statistics
$pdf->AddPage();    
$pdf->ChapterTitle('Produccion lechera');

// SQL to get monthly milk production with daily calculations and costs
$sql_milk_monthly = "WITH MonthlyMilk AS (
    SELECT 
        DATE_FORMAT(oh_leche_fecha_inicio, '%Y-%m-01') as primer_dia_mes,
        oh_leche_tagid,
        AVG(oh_leche_peso) as produccion_diaria_promedio,
        AVG(oh_leche_precio) as precio_promedio
    FROM oh_leche
    GROUP BY DATE_FORMAT(oh_leche_fecha_inicio, '%Y-%m-01'), oh_leche_tagid
),
MonthlyStats AS (
    SELECT 
        primer_dia_mes as mes,
        COUNT(DISTINCT oh_leche_tagid) as total_vacas,
        ROUND(SUM(produccion_diaria_promedio), 2) as produccion_diaria_total,
        ROUND(AVG(produccion_diaria_promedio), 2) as promedio_diario_por_vaca,
        ROUND(AVG(precio_promedio), 2) as precio_promedio_mes
    FROM MonthlyMilk
    GROUP BY primer_dia_mes
)
SELECT 
    mes,
    total_vacas,
    produccion_diaria_total,
    produccion_diaria_total * DAY(LAST_DAY(mes)) as produccion_total_mes,
    promedio_diario_por_vaca,
    promedio_diario_por_vaca * DAY(LAST_DAY(mes)) as promedio_mensual_por_vaca,
    precio_promedio_mes
FROM MonthlyStats
ORDER BY mes DESC
LIMIT 12";

$result_milk_monthly = $conn->query($sql_milk_monthly);

if ($result_milk_monthly->num_rows > 0) {
    $header = array('Mes', '# Animal', 'Prod. Diaria', 'Prod. Mensual', 'Diario x Animal', 'Mensual x Animal', 'Precio Prom.');
    $data = array();
    
    // Statistics tracking
    $total_production = 0;
    $total_months = 0;
    $min_daily_per_cow = PHP_FLOAT_MAX;
    $max_daily_per_cow = 0;
    $total_daily_per_cow = 0;
    
    while ($row = $result_milk_monthly->fetch_assoc()) {
        // Format the month to Spanish format
        $date = DateTime::createFromFormat('Y-m-d', $row['mes']);
        $mes_espanol = strftime('%B %Y', $date->getTimestamp());
        $mes_espanol = ucfirst(mb_strtolower($mes_espanol, 'UTF-8'));
        
        $data[] = array(
            $mes_espanol,
            $row['total_vacas'],
            number_format($row['produccion_diaria_total'], 2),
            number_format($row['produccion_total_mes'], 2),
            number_format($row['promedio_diario_por_animal'], 2),
            number_format($row['promedio_mensual_por_animal'], 2),
            number_format($row['precio_promedio_mes'], 2)
        );
        
        // Track statistics
        $total_production += $row['produccion_total_mes'];
        $total_daily_per_cow += $row['promedio_diario_por_animal'];
        $min_daily_per_cow = min($min_daily_per_cow, $row['promedio_diario_por_animal']);
        $max_daily_per_cow = max($max_daily_per_cow, $row['promedio_diario_por_animal']);
        $total_months++;
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add statistics summary
    if ($total_months > 0) {
        $avg_daily_per_cow = $total_daily_per_cow / $total_months;
        $avg_monthly_production = $total_production / $total_months;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(5);
        $pdf->Cell(0, 6, 'ESTADISTICAS GENERALES:', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, sprintf('Produccion Mensual Promedio: %.2f litros', $avg_monthly_production), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Promedio Diario por Animal: %.2f litros', $avg_daily_per_cow), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Minimo Diario por Animal: %.2f litros', $min_daily_per_cow), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Maximo Diario por Animal: %.2f litros', $max_daily_per_cow), 0, 1, 'L');
    }
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, 'Notas:
- La produccion se calcula como un promedio diario por animal por mes.
- Si hay varios registros para un animal en el mismo mes, se usa el promedio.
- La produccion diaria total es la suma de los promedios diarios de todos los animales.
- La produccion mensual se calcula multiplicando la produccion diaria por los dias del mes.
- El promedio por animal representa la produccion individual promedio.
- Los precios mostrados son promedios mensuales por litro.
- Las estadisticas muestran la tendencia de produccion en los ultimos 12 meses.', 0, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de produccion de leche para generar estadisticas mensuales', 0, 1);
    $pdf->Ln(2);
}

// Add Monthly Feed Consumption Statistics
$pdf->AddPage();    
$pdf->ChapterTitle('Consumo Concentrado');

// SQL to get monthly feed consumption with daily calculations and costs
$sql_feed_monthly = "WITH MonthlyFeed AS (
    SELECT 
        DATE_FORMAT(oh_concentrado_fecha_inicio, '%Y-%m-01') as primer_dia_mes,
        oh_concentrado_tagid,
        AVG(oh_concentrado_racion) as consumo_diario_promedio,
        AVG(oh_concentrado_costo) as costo_promedio
    FROM oh_concentrado
    GROUP BY DATE_FORMAT(oh_concentrado_fecha_inicio, '%Y-%m-01'), oh_concentrado_tagid
),
MonthlyStats AS (
    SELECT 
        primer_dia_mes as mes,
        COUNT(DISTINCT oh_concentrado_tagid) as total_animales,
        ROUND(SUM(consumo_diario_promedio), 2) as consumo_diario_total,
        ROUND(AVG(consumo_diario_promedio), 2) as promedio_diario_por_animal,
        ROUND(AVG(costo_promedio), 2) as costo_promedio_mes
    FROM MonthlyFeed
    GROUP BY primer_dia_mes
)
SELECT 
    mes,
    total_animales,
    consumo_diario_total,
    consumo_diario_total * DAY(LAST_DAY(mes)) as consumo_total_mes,
    promedio_diario_por_animal,
    promedio_diario_por_animal * DAY(LAST_DAY(mes)) as promedio_mensual_por_animal,
    costo_promedio_mes,
    ROUND(consumo_diario_total * DAY(LAST_DAY(mes)) * costo_promedio_mes, 2) as costo_total_mes
FROM MonthlyStats
ORDER BY mes DESC";

$result_feed_monthly = $conn->query($sql_feed_monthly);

if ($result_feed_monthly->num_rows > 0) {
    $header = array('Mes', '# Animales', 'C. Diario', 'C. Mensual', 'Diario x Animal', 'Mensual x Animal', 'Precio Prom.', 'Total');
    $data = array();
    
    // Statistics tracking
    $total_consumption = 0;
    $total_cost = 0;
    $total_months = 0;
    $min_daily_per_animal = PHP_FLOAT_MAX;
    $max_daily_per_animal = 0;
    $total_daily_per_animal = 0;
    
    while ($row = $result_feed_monthly->fetch_assoc()) {
        // Format the month to Spanish format
        $date = DateTime::createFromFormat('Y-m-d', $row['mes']);
        $mes_espanol = strftime('%B %Y', $date->getTimestamp());
        $mes_espanol = ucfirst(mb_strtolower($mes_espanol, 'UTF-8'));
        
        $data[] = array(
            $mes_espanol,
            $row['total_animales'],
            number_format($row['consumo_diario_total'], 2),
            number_format($row['consumo_total_mes'], 2),
            number_format($row['promedio_diario_por_animal'], 2),
            number_format($row['promedio_mensual_por_animal'], 2),
            number_format($row['costo_promedio_mes'], 2),
            number_format($row['costo_total_mes'], 2)
        );
        
        // Track statistics
        $total_consumption += $row['consumo_total_mes'];
        $total_cost += $row['costo_total_mes'];
        $total_daily_per_animal += $row['promedio_diario_por_animal'];
        $min_daily_per_animal = min($min_daily_per_animal, $row['promedio_diario_por_animal']);
        $max_daily_per_animal = max($max_daily_per_animal, $row['promedio_diario_por_animal']);
        $total_months++;
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add statistics summary
    if ($total_months > 0) {
        $avg_daily_per_animal = $total_daily_per_animal / $total_months;
        $avg_monthly_consumption = $total_consumption / $total_months;
        $avg_monthly_cost = $total_cost / $total_months;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(5);
        $pdf->Cell(0, 6, 'ESTADISTICAS GENERALES:', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, sprintf('Consumo Mensual Promedio: %.2f kg', $avg_monthly_consumption), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Promedio Diario por Animal: %.2f kg', $avg_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Minimo Diario por Animal: %.2f kg', $min_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Maximo Diario por Animal: %.2f kg', $max_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Costo Mensual Promedio: $%.2f', $avg_monthly_cost), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, sprintf('Costo Total (12 meses): $%.2f', $total_cost), 0, 1, 'L');
    }
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, 'Notas:
- El consumo se calcula como un promedio diario por animal por mes.
- Si hay varios registros para un animal en el mismo mes, se usa el promedio.
- El consumo diario total es la suma de los promedios diarios de todos los animales.
- El consumo mensual se calcula multiplicando el consumo diario por los dias del mes.
- El promedio por animal representa el consumo individual promedio.
- Los precios mostrados son promedios mensuales por kilogramo.
- El costo total incluye el consumo mensual multiplicado por el precio promedio.
- Las estadisticas muestran la tendencia de consumo en los ultimos 12 meses.', 0, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de consumo de alimento concentrado para generar estadisticas mensuales', 0, 1);
    $pdf->Ln(2);
}

// Add Feed Conversion Ratio Analysis
$pdf->AddPage();
$pdf->ChapterTitle('Conversion');

// SQL to calculate FCR using total feed and weight gain
$sql_fcr = "WITH AllAnimals AS (
    SELECT tagid, nombre, fecha_nacimiento, genero, etapa 
    FROM ovino 
    WHERE estatus = 'Activo'
),
MonthlyWeights AS (
    SELECT 
        oh_peso_tagid,
        DATE_FORMAT(oh_peso_fecha, '%Y-%m-01') as mes,
        AVG(oh_peso_animal) as peso_promedio
    FROM oh_peso
    GROUP BY oh_peso_tagid, DATE_FORMAT(oh_peso_fecha, '%Y-%m-01')
),
WeightChanges AS (
    SELECT 
        w1.oh_peso_tagid,
        w1.mes as mes_inicial,
        w2.mes as mes_final,
        w1.peso_promedio as peso_inicial,
        w2.peso_promedio as peso_final,
        w2.peso_promedio - w1.peso_promedio as ganancia_peso
    FROM MonthlyWeights w1
    JOIN MonthlyWeights w2 ON w1.oh_peso_tagid = w2.oh_peso_tagid
        AND w1.mes < w2.mes
        AND NOT EXISTS (
            SELECT 1 FROM MonthlyWeights w3
            WHERE w3.oh_peso_tagid = w1.oh_peso_tagid
            AND w3.mes > w1.mes AND w3.mes < w2.mes
        )
),
TotalFeed AS (
    SELECT 
        oh_concentrado_tagid,
        DATE_FORMAT(oh_concentrado_fecha_inicio, '%Y-%m-01') as mes,
        SUM(oh_concentrado_racion) as consumo_total
    FROM oh_concentrado
    GROUP BY oh_concentrado_tagid, DATE_FORMAT(oh_concentrado_fecha_inicio, '%Y-%m-01')
),
FCRCalculation AS (
    SELECT 
        wc.oh_peso_tagid,
        a.nombre,
        a.genero,
        a.etapa,
        wc.mes_inicial,
        wc.mes_final,
        wc.peso_inicial,
        wc.peso_final,
        wc.ganancia_peso,
        SUM(tf.consumo_total) as consumo_periodo,
        CASE 
            WHEN wc.ganancia_peso > 0 THEN SUM(tf.consumo_total) / wc.ganancia_peso
            ELSE NULL
        END as fcr
    FROM WeightChanges wc
    JOIN AllAnimals a ON wc.oh_peso_tagid = a.tagid
    LEFT JOIN TotalFeed tf ON wc.oh_peso_tagid = tf.oh_concentrado_tagid
        AND tf.mes >= wc.mes_inicial AND tf.mes <= wc.mes_final
    GROUP BY wc.oh_peso_tagid, a.nombre, a.genero, a.etapa, wc.mes_inicial, wc.mes_final, 
             wc.peso_inicial, wc.peso_final, wc.ganancia_peso
    HAVING consumo_periodo IS NOT NULL AND ganancia_peso > 0
)
SELECT 
    (SELECT COUNT(*) FROM AllAnimals) as total_animales_hato,
    COUNT(DISTINCT oh_peso_tagid) as animales_con_ica,
    ROUND(AVG(fcr), 2) as fcr_promedio,
    ROUND(MIN(fcr), 2) as fcr_minimo,
    ROUND(MAX(fcr), 2) as fcr_maximo,
    ROUND(SUM(consumo_periodo), 2) as consumo_total,
    ROUND(SUM(ganancia_peso), 2) as ganancia_total,
    ROUND(SUM(consumo_periodo) / SUM(ganancia_peso), 2) as fcr_global
FROM FCRCalculation";

$result_fcr = $conn->query($sql_fcr);

if ($result_fcr->num_rows > 0) {
    $fcr_data = $result_fcr->fetch_assoc();
    
    // Display FCR Statistics
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, 'ESTADISTICAS DE CONVERSION:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, sprintf('Total de Animales en el Hato: %d', $fcr_data['total_animales_hato']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Animales con Datos Suficientes para ICA: %d (%.1f%%)', 
        $fcr_data['animales_con_ica'],
        ($fcr_data['animales_con_ica'] / $fcr_data['total_animales_hato']) * 100
    ), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Consumo Total de Alimento: %.2f kg', $fcr_data['consumo_total']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Ganancia Total de Peso: %.2f kg', $fcr_data['ganancia_total']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('ICA Global del Hato: %.2f', $fcr_data['fcr_global']), 0, 1, 'L');
    $pdf->Ln(2);
    $pdf->Cell(0, 6, 'RANGOS DE ICA:', 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('ICA Promedio: %.2f', $fcr_data['fcr_promedio']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('ICA Minimo: %.2f', $fcr_data['fcr_minimo']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('ICA Maximo: %.2f', $fcr_data['fcr_maximo']), 0, 1, 'L');
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, sprintf('Notas:
- El Indice de Conversion Alimenticia (ICA) se calcula como: Alimento Consumido / Ganancia de Peso
- Un ICA mas bajo indica mejor eficiencia en la conversion de alimento a peso
- El ICA Global representa la eficiencia general del hato
- De los %d animales en el hato, solo %d tienen datos suficientes para calcular el ICA
- Se consideran solo periodos con registros completos de peso y consumo
- Los calculos se basan en promedios mensuales de peso y consumo total de alimento
- Solo se incluyen animales con ganancia de peso positiva
- El analisis requiere al menos dos pesajes y registros de consumo en el periodo', 
        $fcr_data['total_animales_hato'],
        $fcr_data['animales_con_ica']
    ), 0, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay suficientes datos para calcular el Indice de Conversion Alimenticia', 0, 1);
    $pdf->Ln(2);
}

// Add Monthly Molasses Consumption Statistics
$pdf->AddPage();
$pdf->ChapterTitle('Consumo Melaza');

// SQL to get monthly molasses consumption with daily calculations and costs
$sql_molasses_monthly = "WITH MonthlyMolasses AS (
    SELECT 
        DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m-01') as primer_dia_mes,
        oh_melaza_tagid,
        AVG(oh_melaza_racion) as consumo_diario_promedio,
        AVG(oh_melaza_costo) as costo_promedio
    FROM oh_melaza
    GROUP BY DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m-01'), oh_melaza_tagid
),
MonthlyStats AS (
    SELECT 
        primer_dia_mes as mes,
        COUNT(DISTINCT oh_melaza_tagid) as total_animales,
        ROUND(SUM(consumo_diario_promedio), 2) as consumo_diario_total,
        ROUND(AVG(consumo_diario_promedio), 2) as promedio_diario_por_animal,
        ROUND(AVG(costo_promedio), 2) as costo_promedio_mes
    FROM MonthlyMolasses
    GROUP BY primer_dia_mes
)
SELECT 
    mes,
    total_animales,
    consumo_diario_total,
    consumo_diario_total * DAY(LAST_DAY(mes)) as consumo_total_mes,
    promedio_diario_por_animal,
    promedio_diario_por_animal * DAY(LAST_DAY(mes)) as promedio_mensual_por_animal,
    costo_promedio_mes,
    ROUND(consumo_diario_total * DAY(LAST_DAY(mes)) * costo_promedio_mes, 2) as costo_total_mes
FROM MonthlyStats
ORDER BY mes DESC
LIMIT 12";

$result_molasses_monthly = $conn->query($sql_molasses_monthly);

if ($result_molasses_monthly->num_rows > 0) {
    $header = array('Mes', '# Animales', 'C. Diario', 'C. Mensual', 'Diario x Animal', 'Mensual x Animal', 'Precio Prom.', 'Total');
    $data = array();
    
    // Statistics tracking
    $total_consumption = 0;
    $total_cost = 0;
    $total_months = 0;
    $min_daily_per_animal = PHP_FLOAT_MAX;
    $max_daily_per_animal = 0;
    $total_daily_per_animal = 0;
    
    while ($row = $result_molasses_monthly->fetch_assoc()) {
        // Format the month to Spanish format
        $date = DateTime::createFromFormat('Y-m-d', $row['mes']);
        $mes_espanol = strftime('%B %Y', $date->getTimestamp());
        $mes_espanol = ucfirst(mb_strtolower($mes_espanol, 'UTF-8'));
        
        $data[] = array(
            $mes_espanol,
            $row['total_animales'],
            number_format($row['consumo_diario_total'], 2),
            number_format($row['consumo_total_mes'], 2),
            number_format($row['promedio_diario_por_animal'], 2),
            number_format($row['promedio_mensual_por_animal'], 2),
            number_format($row['costo_promedio_mes'], 2),
            number_format($row['costo_total_mes'], 2)
        );
        
        // Track statistics
        $total_consumption += $row['consumo_total_mes'];
        $total_cost += $row['costo_total_mes'];
        $total_daily_per_animal += $row['promedio_diario_por_animal'];
        $min_daily_per_animal = min($min_daily_per_animal, $row['promedio_diario_por_animal']);
        $max_daily_per_animal = max($max_daily_per_animal, $row['promedio_diario_por_animal']);
        $total_months++;
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add statistics summary
    if ($total_months > 0) {
        $avg_daily_per_animal = $total_daily_per_animal / $total_months;
        $avg_monthly_consumption = $total_consumption / $total_months;
        $avg_monthly_cost = $total_cost / $total_months;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(5);
        $pdf->Cell(0, 6, 'ESTADISTICAS GENERALES:', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, sprintf('Consumo Mensual Promedio: %.2f kg', $avg_monthly_consumption), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Promedio Diario por Animal: %.2f kg', $avg_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Minimo Diario por Animal: %.2f kg', $min_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Maximo Diario por Animal: %.2f kg', $max_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Costo Mensual Promedio: $%.2f', $avg_monthly_cost), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, sprintf('Costo Total (12 meses): $%.2f', $total_cost), 0, 1, 'L');
    }
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, 'Notas:
- El consumo se calcula como un promedio diario por animal por mes.
- Si hay varios registros para un animal en el mismo mes, se usa el promedio.
- El consumo diario total es la suma de los promedios diarios de todos los animales.
- El consumo mensual se calcula multiplicando el consumo diario por los dias del mes.
- El promedio por animal representa el consumo individual promedio.
- Los precios mostrados son promedios mensuales por kilogramo.
- El costo total incluye el consumo mensual multiplicado por el precio promedio.
- Las estadisticas muestran la tendencia de consumo en los ultimos 12 meses.', 0, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de consumo de melaza para generar estadisticas mensuales', 0, 1);
    $pdf->Ln(2);
}

// Add Monthly Salt Consumption Statistics
$pdf->AddPage();
$pdf->ChapterTitle('Consumo Sal');

// SQL to get monthly salt consumption with daily calculations and costs
$sql_salt_monthly = "WITH MonthlySalt AS (
    SELECT 
        DATE_FORMAT(oh_sal_fecha_inicio, '%Y-%m-01') as primer_dia_mes,
        oh_sal_tagid,
        AVG(oh_sal_racion) as consumo_diario_promedio,
        AVG(oh_sal_costo) as costo_promedio
    FROM oh_sal
    GROUP BY DATE_FORMAT(oh_sal_fecha_inicio, '%Y-%m-01'), oh_sal_tagid
),
MonthlyStats AS (
    SELECT 
        primer_dia_mes as mes,
        COUNT(DISTINCT oh_sal_tagid) as total_animales,
        ROUND(SUM(consumo_diario_promedio), 2) as consumo_diario_total,
        ROUND(AVG(consumo_diario_promedio), 2) as promedio_diario_por_animal,
        ROUND(AVG(costo_promedio), 2) as costo_promedio_mes
    FROM MonthlySalt
    GROUP BY primer_dia_mes
)
SELECT 
    mes,
    total_animales,
    consumo_diario_total,
    consumo_diario_total * DAY(LAST_DAY(mes)) as consumo_total_mes,
    promedio_diario_por_animal,
    promedio_diario_por_animal * DAY(LAST_DAY(mes)) as promedio_mensual_por_animal,
    costo_promedio_mes,
    ROUND(consumo_diario_total * DAY(LAST_DAY(mes)) * costo_promedio_mes, 2) as costo_total_mes
FROM MonthlyStats
ORDER BY mes DESC
LIMIT 12";

$result_salt_monthly = $conn->query($sql_salt_monthly);

if ($result_salt_monthly->num_rows > 0) {
    $header = array('Mes', '# Animales', 'C. Diario', 'C. Mensual', 'Diario x Animal', 'Mensual x Animal', 'Precio Prom.', 'Total');
    $data = array();
    
    // Statistics tracking
    $total_consumption = 0;
    $total_cost = 0;
    $total_months = 0;
    $min_daily_per_animal = PHP_FLOAT_MAX;
    $max_daily_per_animal = 0;
    $total_daily_per_animal = 0;
    
    while ($row = $result_salt_monthly->fetch_assoc()) {
        // Format the month to Spanish format
        $date = DateTime::createFromFormat('Y-m-d', $row['mes']);
        $mes_espanol = strftime('%B %Y', $date->getTimestamp());
        $mes_espanol = ucfirst(mb_strtolower($mes_espanol, 'UTF-8'));
        
        $data[] = array(
            $mes_espanol,
            $row['total_animales'],
            number_format($row['consumo_diario_total'], 2),
            number_format($row['consumo_total_mes'], 2),
            number_format($row['promedio_diario_por_animal'], 2),
            number_format($row['promedio_mensual_por_animal'], 2),
            number_format($row['costo_promedio_mes'], 2),
            number_format($row['costo_total_mes'], 2)
        );
        
        // Track statistics
        $total_consumption += $row['consumo_total_mes'];
        $total_cost += $row['costo_total_mes'];
        $total_daily_per_animal += $row['promedio_diario_por_animal'];
        $min_daily_per_animal = min($min_daily_per_animal, $row['promedio_diario_por_animal']);
        $max_daily_per_animal = max($max_daily_per_animal, $row['promedio_diario_por_animal']);
        $total_months++;
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add statistics summary
    if ($total_months > 0) {
        $avg_daily_per_animal = $total_daily_per_animal / $total_months;
        $avg_monthly_consumption = $total_consumption / $total_months;
        $avg_monthly_cost = $total_cost / $total_months;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(5);
        $pdf->Cell(0, 6, 'ESTADISTICAS GENERALES:', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, sprintf('Consumo Mensual Promedio: %.2f kg', $avg_monthly_consumption), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Promedio Diario por Animal: %.2f kg', $avg_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Minimo Diario por Animal: %.2f kg', $min_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Maximo Diario por Animal: %.2f kg', $max_daily_per_animal), 0, 1, 'L');
        $pdf->Cell(0, 6, sprintf('Costo Mensual Promedio: $%.2f', $avg_monthly_cost), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, sprintf('Costo Total (12 meses): $%.2f', $total_cost), 0, 1, 'L');
    }
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, 'Notas:
- El consumo se calcula como un promedio diario por animal por mes.
- Si hay varios registros para un animal en el mismo mes, se usa el promedio.
- El consumo diario total es la suma de los promedios diarios de todos los animales.
- El consumo mensual se calcula multiplicando el consumo diario por los dias del mes.
- El promedio por animal representa el consumo individual promedio.
- Los precios mostrados son promedios mensuales por kilogramo.
- El costo total incluye el consumo mensual multiplicado por el precio promedio.
- Las estadisticas muestran la tendencia de consumo en los ultimos 12 meses.', 0, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de consumo de sal para generar estadisticas mensuales', 0, 1);
    $pdf->Ln(2);
}

// Add Vaccination Summary
$pdf->AddPage();
$pdf->ChapterTitle('Vacunas');

// SQL to get vaccination counts
$sql_vacc_summary = "
WITH AllAnimals AS (
    SELECT DISTINCT tagid FROM ovino WHERE estatus = 'Activo'
),
VaccinationCounts AS (
    SELECT 
        (SELECT COUNT(*) FROM AllAnimals) as total_animals,
        (SELECT COUNT(DISTINCT oh_aftosa_tagid) FROM oh_aftosa) as aftosa_count,
        (SELECT COUNT(DISTINCT oh_brucelosis_tagid) FROM oh_brucelosis) as brucelosis_count,
        (SELECT COUNT(DISTINCT oh_clostridiosis_tagid) FROM oh_clostridiosis) as clostridiosis_count,
        (SELECT COUNT(DISTINCT oh_neumonia_tagid) FROM oh_neumonia) as neumonia_count,
        (SELECT COUNT(DISTINCT oh_ectima_tagid) FROM oh_ectima) as ectima_count,
        (SELECT COUNT(DISTINCT oh_garrapatas_tagid) FROM oh_garrapatas) as garrapatas_count,
        (SELECT COUNT(DISTINCT oh_parasitos_tagid) FROM oh_parasitos) as parasitos_count,
        (SELECT COALESCE(SUM(oh_aftosa_costo * oh_aftosa_dosis), 0) FROM oh_aftosa) as aftosa_cost,
        (SELECT COALESCE(SUM(oh_brucelosis_costo * oh_brucelosis_dosis), 0) FROM oh_brucelosis) as brucelosis_cost,
        (SELECT COALESCE(SUM(oh_clostridiosis_costo * oh_clostridiosis_dosis), 0) FROM oh_clostridiosis) as clostridiosis_cost,
        (SELECT COALESCE(SUM(oh_neumonia_costo * oh_neumonia_dosis), 0) FROM oh_neumonia) as neumonia_cost,
        (SELECT COALESCE(SUM(oh_ectima_costo * oh_ectima_dosis), 0) FROM oh_ectima) as ectima_cost,
        (SELECT COALESCE(SUM(oh_garrapatas_costo * oh_garrapatas_dosis), 0) FROM oh_garrapatas) as garrapatas_cost,
        (SELECT COALESCE(SUM(oh_parasitos_costo * oh_parasitos_dosis), 0) FROM oh_parasitos) as parasitos_cost
)
SELECT 
    total_animals,
    aftosa_count,
    total_animals - aftosa_count as aftosa_pending,
    aftosa_cost,
    brucelosis_count,
    total_animals - brucelosis_count as brucelosis_pending,
    brucelosis_cost,
    clostridiosis_count,
    total_animals - clostridiosis_count as clostridiosis_pending,
    clostridiosis_cost,
    neumonia_count,
    total_animals - neumonia_count as neumonia_pending,
    neumonia_cost,
    ectima_count,
    total_animals - ectima_count as ectima_pending,
    ectima_cost,
    garrapatas_count,
    total_animals - garrapatas_count as garrapatas_pending,
    garrapatas_cost,
    parasitos_count,
    total_animals - parasitos_count as parasitos_pending,
    parasitos_cost
FROM VaccinationCounts";

$result_vacc = $conn->query($sql_vacc_summary);
$vacc_data = $result_vacc->fetch_assoc();

// Create summary table
$header = array('Tratamiento', 'Animales Tratados', 'Animales Pendientes', 'Costo Total');
$data = array(
    array('Aftosa', $vacc_data['aftosa_count'], $vacc_data['aftosa_pending'], '$' . number_format($vacc_data['aftosa_cost'], 2)),
    array('Brucelosis', $vacc_data['brucelosis_count'], $vacc_data['brucelosis_pending'], '$' . number_format($vacc_data['brucelosis_cost'], 2)),
    array('CLOSTRIOSIS', $vacc_data['clostridiosis_count'], $vacc_data['clostridiosis_pending'], '$' . number_format($vacc_data['clostridiosis_cost'], 2)),
    array('NEUMONIA', $vacc_data['neumonia_count'], $vacc_data['neumonia_pending'], '$' . number_format($vacc_data['neumonia_cost'], 2)),
    array('ECTIMA', $vacc_data['ectima_count'], $vacc_data['ectima_pending'], '$' . number_format($vacc_data['ectima_cost'], 2)),
    array('Garrapatas', $vacc_data['garrapatas_count'], $vacc_data['garrapatas_pending'], '$' . number_format($vacc_data['garrapatas_cost'], 2)),
    array('Parasitos', $vacc_data['parasitos_count'], $vacc_data['parasitos_pending'], '$' . number_format($vacc_data['parasitos_cost'], 2))
);

$pdf->SimpleTable($header, $data);

// Calculate total vaccination cost
$total_vacc_cost = $vacc_data['aftosa_cost'] + 
                   $vacc_data['brucelosis_cost'] + 
                   $vacc_data['clostridiosis_cost'] + 
                   $vacc_data['neumonia_cost'] + 
                   $vacc_data['ectima_cost'] + 
                   $vacc_data['garrapatas_cost'] + 
                   $vacc_data['parasitos_cost'];

// Add total cost line
$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(3);
$pdf->Cell(0, 6, sprintf('Costo Total en Tratamientos: $%.2f', $total_vacc_cost), 0, 1, 'R');

// Add explanatory note
$pdf->SetFont('Arial', 'I', 9);
$pdf->Ln(2);
$pdf->MultiCell(0, 5, sprintf('Nota: Basado en un total de %d animales activos en el sistema. Los animales pendientes son aquellos que no tienen ningun registro historico del tratamiento correspondiente. Los costos totales incluyen todos los tratamientos historicos realizados.', $vacc_data['total_animals']), 0, 'L');

// Add Pregnancy Duration Statistics
$pdf->AddPage();
$pdf->ChapterTitle('Preñez');

// SQL to calculate pregnancy duration including current pregnancies
$sql_preg_duration = "SELECT 
    g.oh_gestacion_tagid,
    v.nombre,
    g.oh_gestacion_numero,
    g.oh_gestacion_fecha,
    p.oh_parto_fecha,
    CASE 
        WHEN p.oh_parto_fecha IS NOT NULL THEN 'Completada'
        ELSE 'En Curso'
    END as estado,
    DATEDIFF(COALESCE(p.oh_parto_fecha, CURDATE()), g.oh_gestacion_fecha) as dias_gestacion
FROM oh_gestacion g
LEFT JOIN oh_parto p ON g.oh_gestacion_tagid = p.oh_parto_tagid 
    AND g.oh_gestacion_numero = p.oh_parto_numero
LEFT JOIN ovino v ON g.oh_gestacion_tagid = v.tagid
ORDER BY g.oh_gestacion_tagid, g.oh_gestacion_fecha DESC";

$stmt_preg_duration = $conn->prepare($sql_preg_duration);
if (!$stmt_preg_duration) {
    error_log('Failed to prepare pregnancy duration query: ' . $conn->error);
    $result_preg_duration = false;
} else {
    $stmt_preg_duration->execute();
    $result_preg_duration = $stmt_preg_duration->get_result();
}

if ($result_preg_duration && $result_preg_duration->num_rows > 0) {
    $header = array('Tag ID', 'Nombre', 'Gest. Nro.', 'F. Gestacion', 'F. Parto', 'Estado', 'Dias');
    $data = array();
    $total_days_completed = 0;
    $count_completed = 0;
    $current_tag = '';
    $tag_stats = array();
    
    while ($row = $result_preg_duration->fetch_assoc()) {
        $parto_fecha = $row['oh_parto_fecha'] ? $row['oh_parto_fecha'] : 'En Curso';
        $data[] = array(
            $row['oh_gestacion_tagid'],
            $row['nombre'],
            $row['oh_gestacion_numero'],
            $row['oh_gestacion_fecha'],
            $parto_fecha,
            $row['estado'],
            $row['dias_gestacion']
        );
        
        // Collect statistics per animal
        $tagid = $row['oh_gestacion_tagid'];
        if (!isset($tag_stats[$tagid])) {
            $tag_stats[$tagid] = array(
                'total_days' => 0,
                'count' => 0,
                'nombre' => $row['nombre']
            );
        }
        
        // Only include completed pregnancies in the statistics
        if ($row['estado'] === 'Completada') {
            $total_days_completed += $row['dias_gestacion'];
            $count_completed++;
            $tag_stats[$tagid]['total_days'] += $row['dias_gestacion'];
            $tag_stats[$tagid]['count']++;
        }
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add overall statistics
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln(5);
    $pdf->Cell(0, 6, 'ESTADISTICAS GENERALES:', 0, 1, 'L');
    
    if ($count_completed > 0) {
        $average_days = round($total_days_completed / $count_completed, 1);
        $pdf->Cell(0, 6, sprintf('Promedio General de Duracion (Gestaciones Completadas): %s dias', $average_days), 0, 1, 'L');
    }
    
    // Add per-animal statistics
    $pdf->Ln(2);
    $pdf->Cell(0, 6, 'PROMEDIOS POR ANIMAL (Solo Gestaciones Completadas):', 0, 1, 'L');
    foreach ($tag_stats as $tagid => $stats) {
        if ($stats['count'] > 0) {
            $avg = round($stats['total_days'] / $stats['count'], 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, sprintf('Tag ID: %s - %s: %s dias (de %d gestaciones)', 
                $tagid, 
                $stats['nombre'],
                $avg,
                $stats['count']
            ), 0, 1, 'L');
        }
    }
    
    // Add explanatory note
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, 'Nota: La duracion se calcula como la diferencia en dias entre la fecha de confirmacion de gestacion y la fecha del parto. Para gestaciones en curso, se utiliza la fecha actual para calcular los dias transcurridos. Los promedios solo consideran las gestaciones completadas.', 0, 'L');
    $pdf->Ln(2);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de gestaciones', 0, 1);
    $pdf->Ln(2);
}

// Add Open Days Statistics
$pdf->AddPage();
$pdf->ChapterTitle('Dias Abiertos');

// SQL to calculate open days between birth and next pregnancy for all animals
$sql_open_days = "WITH OrderedEvents AS (
    SELECT 
        oh_parto_tagid as tagid,
        oh_parto_fecha as fecha,
        oh_parto_numero as numero,
        'Parto' as tipo
    FROM oh_parto
    
    UNION ALL
    
    SELECT 
        oh_gestacion_tagid as tagid,
        oh_gestacion_fecha as fecha,
        oh_gestacion_numero as numero,
        'Gestacion' as tipo
    FROM oh_gestacion
),
NextPregnancy AS (
    SELECT 
        e1.tagid,
        e1.fecha as fecha_parto,
        e1.numero as parto_numero,
        MIN(e2.fecha) as fecha_siguiente_gestacion,
        DATEDIFF(MIN(e2.fecha), e1.fecha) as dias_abiertos
    FROM OrderedEvents e1
    LEFT JOIN OrderedEvents e2 ON e1.tagid = e2.tagid 
        AND e2.tipo = 'Gestacion'
        AND e2.fecha > e1.fecha
    WHERE e1.tipo = 'Parto'
    GROUP BY e1.tagid, e1.fecha, e1.numero
)
SELECT 
    np.tagid,
    v.nombre,
    v.etapa,
    np.parto_numero,
    np.fecha_parto,
    np.fecha_siguiente_gestacion,
    CASE 
        WHEN np.fecha_siguiente_gestacion IS NOT NULL THEN np.dias_abiertos
        WHEN np.fecha_parto IS NOT NULL THEN DATEDIFF(CURDATE(), np.fecha_parto)
    END as dias_abiertos,
    CASE 
        WHEN np.fecha_siguiente_gestacion IS NOT NULL THEN 'Cerrado'
        WHEN np.fecha_parto IS NOT NULL THEN 'Abierto'
    END as estado
FROM NextPregnancy np
LEFT JOIN ovino v ON np.tagid = v.tagid
WHERE v.genero = 'Hembra'
ORDER BY np.tagid, np.fecha_parto DESC";

$stmt_open_days = $conn->prepare($sql_open_days);
if (!$stmt_open_days) {
    error_log('Failed to prepare open days query: ' . $conn->error);
    $result_open_days = false;
} else {
    $stmt_open_days->execute();
    $result_open_days = $stmt_open_days->get_result();
}

if ($result_open_days && $result_open_days->num_rows > 0) {
    $header = array('Tag ID', 'Nombre', 'Etapa', 'Parto Nro.', 'F. Parto', 'F. Nueva Gestacion', 'Dias Abiertos', 'Estado');
    $data = array();
    $total_days_closed = 0;
    $count_closed = 0;
    $tag_stats = array();
    
    while ($row = $result_open_days->fetch_assoc()) {
        $siguiente_gestacion = $row['fecha_siguiente_gestacion'] ? $row['fecha_siguiente_gestacion'] : 'Pendiente';
        $data[] = array(
            $row['tagid'],
            $row['nombre'],
            $row['etapa'],
            $row['parto_numero'],
            $row['fecha_parto'],
            $siguiente_gestacion,
            $row['dias_abiertos'],
            $row['estado']
        );
        
        // Collect statistics per animal
        $tagid = $row['tagid'];
        if (!isset($tag_stats[$tagid])) {
            $tag_stats[$tagid] = array(
                'nombre' => $row['nombre'],
                'etapa' => $row['etapa'],
                'total_days' => 0,
                'count' => 0,
                'open_periods' => 0,
                'current_open_days' => null
            );
        }
        
        // Track statistics
        if ($row['estado'] === 'Cerrado') {
            $tag_stats[$tagid]['total_days'] += $row['dias_abiertos'];
            $tag_stats[$tagid]['count']++;
            $total_days_closed += $row['dias_abiertos'];
            $count_closed++;
        } else {
            $tag_stats[$tagid]['open_periods']++;
            if ($tag_stats[$tagid]['current_open_days'] === null || 
                $row['dias_abiertos'] > $tag_stats[$tagid]['current_open_days']) {
                $tag_stats[$tagid]['current_open_days'] = $row['dias_abiertos'];
            }
        }
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add overall statistics
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln(5);
    $pdf->Cell(0, 6, 'ESTADISTICAS GENERALES:', 0, 1, 'L');
    
    if ($count_closed > 0) {
        $average_days = round($total_days_closed / $count_closed, 1);
        $pdf->Cell(0, 6, sprintf('Promedio General de Dias Abiertos (Periodos Cerrados): %s dias', $average_days), 0, 1, 'L');
    }
    
    // Add per-animal statistics
    $pdf->Ln(2);
    $pdf->Cell(0, 6, 'PROMEDIOS POR ANIMAL:', 0, 1, 'L');
    foreach ($tag_stats as $tagid => $stats) {
        $pdf->SetFont('Arial', '', 10);
        
        // Show average for closed periods if any
        if ($stats['count'] > 0) {
            $avg = round($stats['total_days'] / $stats['count'], 1);
            $pdf->Cell(0, 6, sprintf('Tag ID: %s - %s (%s)', $tagid, $stats['nombre'], $stats['etapa']), 0, 1, 'L');
            $pdf->Cell(0, 6, sprintf('   Promedio Periodos Cerrados: %s dias (de %d periodos)', 
                $avg, $stats['count']), 0, 1, 'L');
        }
        
        // Show current open period if any
        if ($stats['current_open_days'] !== null) {
            $pdf->Cell(0, 6, sprintf('   Periodo Abierto Actual: %d dias', 
                $stats['current_open_days']), 0, 1, 'L');
        }
        
        $pdf->Ln(1);
    }
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, 'Notas:
- Dias abiertos: Periodo entre un parto y la siguiente confirmacion de gestacion.
- Estado "Cerrado": El animal ya tiene confirmada la siguiente gestacion.
- Estado "Abierto": El animal aun no tiene confirmada la siguiente gestacion.
- Para periodos abiertos, se calcula usando la fecha actual.
- Los promedios de periodos cerrados solo consideran gestaciones confirmadas.
- Se muestran unicamente animales hembra con historial de partos.', 0, 'L');
    $pdf->Ln(2);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay registros de partos para calcular dias abiertos', 0, 1);
    $pdf->Ln(2);
}

// Add section for females with no pregnancy records
$pdf->AddPage();
$pdf->ChapterTitle('Descartes');

// SQL to find females with no pregnancy records
$sql_no_preg = "SELECT 
    v.tagid,
    v.nombre,
    v.fecha_nacimiento,
    TIMESTAMPDIFF(MONTH, v.fecha_nacimiento, CURDATE()) as edad_meses
FROM ovino v
LEFT JOIN oh_gestacion g ON v.tagid = g.oh_gestacion_tagid
WHERE v.genero = 'Hembra' 
    AND g.oh_gestacion_tagid IS NULL
    AND v.estatus = 'Activo'
ORDER BY v.fecha_nacimiento ASC";

$stmt_no_preg = $conn->prepare($sql_no_preg);
if (!$stmt_no_preg) {
    error_log('Failed to prepare no pregnancy query: ' . $conn->error);
    $result_no_preg = false;
} else {
    $stmt_no_preg->execute();
    $result_no_preg = $stmt_no_preg->get_result();
}

if ($result_no_preg && $result_no_preg->num_rows > 0) {
    $header = array('Tag ID', 'Nombre', 'F. Nacimiento', 'Edad (Meses)');
    $data = array();
    $count_by_age = array(
        'menos_12' => 0,
        '12_24' => 0,
        'mas_24' => 0
    );
    
    while ($row = $result_no_preg->fetch_assoc()) {
        $data[] = array(
            $row['tagid'],
            $row['nombre'],
            $row['fecha_nacimiento'],
            $row['edad_meses']
        );
        
        // Count animals by age range
        if ($row['edad_meses'] < 12) {
            $count_by_age['menos_12']++;
        } elseif ($row['edad_meses'] <= 24) {
            $count_by_age['12_24']++;
        } else {
            $count_by_age['mas_24']++;
        }
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add summary statistics
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln(5);
    $pdf->Cell(0, 6, 'RESUMEN POR EDAD:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, sprintf('Menores de 12 meses: %d animales', $count_by_age['menos_12']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Entre 12 y 24 meses: %d animales', $count_by_age['12_24']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Mayores de 24 meses: %d animales', $count_by_age['mas_24']), 0, 1, 'L');
    
    // Add explanatory note
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, 'Nota: Esta lista muestra las hembras activas que no tienen ningun registro de gestacion en el sistema. Las edades se calculan en meses desde la fecha de nacimiento hasta la fecha actual. Animales mayores de 24 meses sin registro de gestacion podrian requerir atencion especial.', 0, 'L');
    $pdf->Ln(2);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'Todas las hembras activas tienen al menos un registro de gestacion', 0, 1);
    $pdf->Ln(2);
}

// Add section for animals with extended time since last birth
$pdf->AddPage();
$pdf->ChapterTitle('sin parir');

// SQL to find animals with more than 365 days since last birth
$sql_extended_period = "WITH LastBirth AS (
    SELECT 
        oh_parto_tagid,
        MAX(oh_parto_fecha) as oh_parto_fecha,
        COUNT(*) as total_partos
    FROM oh_parto
    GROUP BY oh_parto_tagid
)
SELECT 
    v.tagid,
    v.nombre,
    v.etapa,
    lb.oh_parto_fecha,
    DATEDIFF(CURDATE(), lb.oh_parto_fecha) as dias_desde_parto,
    lb.total_partos
FROM ovino v
JOIN LastBirth lb ON v.tagid = lb.oh_parto_tagid
LEFT JOIN oh_parto p ON v.tagid = p.oh_parto_tagid 
    AND p.oh_parto_fecha > lb.oh_parto_fecha
WHERE v.genero = 'Hembra' 
    AND v.estatus = 'Activo'
    AND DATEDIFF(CURDATE(), lb.oh_parto_fecha) > 365
    AND p.oh_parto_tagid IS NULL
ORDER BY dias_desde_parto DESC";

$stmt_extended = $conn->prepare($sql_extended_period);
if (!$stmt_extended) {
    error_log('Failed to prepare extended period query: ' . $conn->error);
    $result_extended = false;
} else {
    $stmt_extended->execute();
    $result_extended = $stmt_extended->get_result();
}

if ($result_extended && $result_extended->num_rows > 0) {
    $header = array('Tag ID', 'Nombre', 'Etapa', 'Ultimo Parto', 'Dias Sin Parir', 'Total Partos');
    $data = array();
    
    // Statistics counters
    $count_by_days = array(
        '365_540' => 0,  // 1-1.5 years
        '541_730' => 0,  // 1.5-2 years
        'over_730' => 0  // over 2 years
    );
    
    while ($row = $result_extended->fetch_assoc()) {
        $data[] = array(
            $row['tagid'],
            $row['nombre'],
            $row['etapa'],
            $row['oh_parto_fecha'],
            $row['dias_desde_parto'],
            $row['total_partos']
        );
        
        // Count by days range
        if ($row['dias_desde_parto'] <= 540) {
            $count_by_days['365_540']++;
        } elseif ($row['dias_desde_parto'] <= 730) {
            $count_by_days['541_730']++;
        } else {
            $count_by_days['over_730']++;
        }
    }
    
    $pdf->SimpleTable($header, $data);
    
    // Add statistics
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln(5);
    $pdf->Cell(0, 6, 'RESUMEN POR PERIODO:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, sprintf('Entre 365 y 517 dias sin parir: %d animales', $count_by_days['365_540']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Entre 518 y 720 dias sin parir: %d animales', $count_by_days['541_730']), 0, 1, 'L');
    $pdf->Cell(0, 6, sprintf('Mas de 720 dias sin parir: %d animales', $count_by_days['over_730']), 0, 1, 'L');
    
    // Add explanatory notes
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, 'Notas:
- Esta tabla muestra hembras activas con mas de 365 dias desde su ultimo parto.
- Solo se incluyen animales que no tienen una gestacion registrada despues de su ultimo parto.
- Los dias sin parir se calculan desde el ultimo parto hasta la fecha actual.
- Animales con mas de 540 dias sin parir requieren atencion especial.
- Considerar revision veterinaria para animales con periodos extendidos sin parir.', 0, 'L');
    $pdf->Ln(2);
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'No hay animales con mas de 365 dias desde su ultimo parto sin nueva gestacion', 0, 1);
    $pdf->Ln(2);
}


// At the end of the file:
// Clean any output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Sanitize animal name for filename (remove special characters and spaces)
$sanitized_name = preg_replace('/[^a-zA-Z0-9]/', '_', $animal['nombre']);
$sanitized_name = trim($sanitized_name, '_'); // Remove leading/trailing underscores

// Generate filename with timestamp to avoid conflicts
$filename = $sanitized_name . '_' . $tagid . '_' . date('Y-m-d_His') . '.pdf';
$filepath = __DIR__ . '/reports/' . $filename;

try {
    // Make sure reports directory exists
    $reportsDir = __DIR__ . '/reports';
    if (!file_exists($reportsDir)) {
        mkdir($reportsDir, 0777, true);
    }

    // Log the file path for debugging
    error_log("Attempting to generate PDF at: " . $filepath);
    error_log("Reports directory: " . $reportsDir);
    error_log("Directory exists: " . (file_exists($reportsDir) ? 'Yes' : 'No'));
    error_log("Directory writable: " . (is_writable($reportsDir) ? 'Yes' : 'No'));

    // First save the PDF to file
    $pdf->Output('F', $filepath);
    
    // Verify the file was created and is a PDF
    if (!file_exists($filepath)) {
        error_log("PDF file was not created at: " . $filepath);
        throw new Exception('Failed to create PDF file');
    }
    
    if (filesize($filepath) === 0) {
        error_log("PDF file is empty at: " . $filepath);
        unlink($filepath); // Delete empty file
        throw new Exception('Generated PDF file is empty');
    }
    
    // Log success
    error_log("PDF generated successfully: " . $filepath);
    error_log("File size: " . filesize($filepath) . " bytes");
    
    // Verify the file is readable
    if (!is_readable($filepath)) {
        error_log("Generated PDF file is not readable: " . $filepath);
        throw new Exception('Generated PDF file is not readable');
    }
    
    // Check if the share file exists
    $share_file = __DIR__ . '/ovino_share.php';
    if (!file_exists($share_file)) {
        error_log("Share file not found: " . $share_file);
        throw new Exception('Share file not found');
    }
    
    if ($isAjax) {
        // Check if ChatPDF upload is requested
        $upload_to_chatpdf = isset($_GET['upload_to_chatpdf']) && $_GET['upload_to_chatpdf'] == '1';
        $upload_result = null;
        
        if ($upload_to_chatpdf) {
            // Upload to ChatPDF
            try {
                // Read the PDF file
                $pdfContent = file_get_contents($filepath);
                if ($pdfContent === false) {
                    throw new Exception('Failed to read PDF file for upload');
                }
                
                // Prepare multipart form data for ChatPDF upload
                $boundary = uniqid();
                $delimiter = '-------------' . $boundary;
                $postData = '--' . $delimiter . "\r\n" .
                    'Content-Disposition: form-data; name="file"; filename="' . $filename . '"' . "\r\n" .
                    'Content-Type: application/pdf' . "\r\n\r\n" .
                    $pdfContent . "\r\n" .
                    '--' . $delimiter . "--\r\n";
                
                // Upload to ChatPDF API
                $curl = curl_init('https://api.chatpdf.com/v1/sources/add-file');
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postData,
                    CURLOPT_HTTPHEADER => [
                        'x-api-key: sec_AdQUXMlHjjhyrwud6dGCP9DFtUt8ZS7T',
                        'Content-Type: multipart/form-data; boundary=' . $delimiter,
                        'Accept: application/json'
                    ]
                ]);
                
                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                
                if ($httpCode === 200) {
                    $upload_result = json_decode($response, true);
                    $upload_result['success'] = true;
                    error_log('ChatPDF upload successful: ' . $response);
                } else {
                    $upload_result = [
                        'success' => false,
                        'error' => 'Upload failed with HTTP code: ' . $httpCode,
                        'response' => $response
                    ];
                    error_log('ChatPDF upload failed: HTTP ' . $httpCode . ' - ' . $response);
                }
                
            } catch (Exception $e) {
                $upload_result = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
                error_log('ChatPDF upload error: ' . $e->getMessage());
            }
        }
        
        // Return JSON response for AJAX requests
        header('Content-Type: application/json');
        $response = [
            'success' => true,
            'filename' => $filename,
            'filepath' => $filepath,
            'message' => 'PDF generated successfully'
        ];
        
        if ($upload_result) {
            $response['upload_result'] = $upload_result;
        }
        
        echo json_encode($response);
        exit;
    } else {
        // Redirect for direct browser requests
        $redirect_url = 'ovino_share.php?file=' . urlencode($filename) . '&tagid=' . urlencode($tagid);
        error_log("Redirecting to: " . $redirect_url);
        
        // Ensure no output has been sent
        if (headers_sent()) {
            error_log("Headers already sent, cannot redirect");
            throw new Exception('Headers already sent, cannot redirect');
        }
        
        header('Location: ' . $redirect_url);
        exit;
    }
} catch (Exception $e) {
    // Log error
    error_log('PDF Generation Error: ' . $e->getMessage());
    error_log('Error occurred at: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    if (file_exists($filepath)) {
        error_log("Cleaning up failed file: " . $filepath);
        unlink($filepath); // Clean up any failed file
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error generating PDF: ' . $e->getMessage() . '. Please try again.']);
        exit;
    } else {
        die('Error generating PDF: ' . $e->getMessage() . '. Please try again.');
    }
}