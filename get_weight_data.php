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
    
    // Query to get weight data ordered by date
    $query = "SELECT                 
                oh_peso_fecha as fecha, 
                oh_peso_animal as peso,
                v.nombre as animal_nombre,
                oh_peso_tagid as tagid,
                UNIX_TIMESTAMP(oh_peso_fecha) as timestamp_fecha
              FROM oh_peso p
              LEFT JOIN ovino v ON p.oh_peso_tagid = v.tagid 
              ORDER BY p.oh_peso_fecha ASC";
    
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