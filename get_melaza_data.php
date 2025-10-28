<?php
// Include database connection
require_once './pdo_conexion.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Verify connection is PDO
    if (!($conn instanceof PDO)) {
        throw new Exception("Error: La conexión no es una instancia de PDO");
    }
    
    // Enable PDO error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the type parameter from the request
    $type = isset($_GET['type']) ? $_GET['type'] : 'basic';
    
    $result = [];
    
    switch ($type) {
        case 'monthly_expense':
            // Monthly total expenses
            $query = "SELECT 
                        DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m') as month,
                        SUM(oh_melaza_racion * oh_melaza_costo) as total_expense
                      FROM oh_melaza 
                      WHERE oh_melaza_fecha_inicio IS NOT NULL
                      GROUP BY DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m')
                      ORDER BY month ASC";
            break;
            
        case 'monthly_weight':
            // Monthly total weight (racion)
            $query = "SELECT 
                        DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m') as month,
                        SUM(oh_melaza_racion) as total_weight
                      FROM oh_melaza 
                      WHERE oh_melaza_fecha_inicio IS NOT NULL
                      GROUP BY DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m')
                      ORDER BY month ASC";
            break;
            
        case 'monthly_feed_weight':
            // Monthly feed weight (same as monthly_weight for melaza)
            $query = "SELECT 
                        DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m') as month,
                        SUM(oh_melaza_racion) as total_feed_kg
                      FROM oh_melaza 
                      WHERE oh_melaza_fecha_inicio IS NOT NULL
                      GROUP BY DATE_FORMAT(oh_melaza_fecha_inicio, '%Y-%m')
                      ORDER BY month ASC";
            break;
            
        case 'animal_weight':
            // Monthly total animal weight from peso table
            $query = "SELECT 
                        DATE_FORMAT(oh_peso_fecha, '%Y-%m') as month,
                        SUM(oh_peso_animal) as total_weight
                      FROM oh_peso
                      WHERE oh_peso_fecha IS NOT NULL
                      GROUP BY DATE_FORMAT(oh_peso_fecha, '%Y-%m')
                      ORDER BY month ASC";
            break;
            
        default:
            // Basic melaza data (for line charts showing individual records)
            $query = "SELECT                 
                        oh_melaza_fecha_inicio as fecha, 
                        oh_melaza_racion as melaza,
                        oh_melaza_costo as costo,
                        oh_melaza_producto as producto,
                        v.nombre as animal_nombre,
                        oh_melaza_tagid as tagid
                      FROM oh_melaza
                      LEFT JOIN ovino v ON oh_melaza_tagid = v.tagid 
                      ORDER BY oh_melaza_fecha_inicio ASC";
            break;
    }
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Fetch all records as associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Output the result as JSON
    echo json_encode($result);
    
} catch (Exception $e) {
    // Return error as JSON
    echo json_encode(['error' => $e->getMessage()]);
}