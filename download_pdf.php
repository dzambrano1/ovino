<?php
// download_pdf.php - Handle PDF file downloads from the reports directory

// Check if file parameter is provided
if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    die('Error: No file specified');
}

// Sanitize the filename to prevent directory traversal attacks
$filename = basename($_GET['file']);

// Construct the full file path
$filepath = './reports/' . $filename;

// Verify the file exists
if (!file_exists($filepath)) {
    http_response_code(404);
    die('Error: File not found - ' . htmlspecialchars($filename));
}

// Verify it's a PDF file
$fileExtension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
if ($fileExtension !== 'pdf') {
    http_response_code(400);
    die('Error: Invalid file type. Only PDF files are allowed.');
}

// Get file size
$fileSize = filesize($filepath);

// Set headers for PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . $fileSize);
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Clear any output buffers
if (ob_get_level()) {
    ob_end_clean();
}

// Read and output the file
readfile($filepath);
exit;
?>

