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
    
    // Query to get brucelosis data ordered by date
    $query = "SELECT         
                oh_brucelosis_fecha as fecha, 
                oh_brucelosis_dosis as dosis,
                oh_brucelosis_costo as costo,
                oh_brucelosis_producto as vacuna,
                v.nombre as animal_nombre,
                oh_brucelosis_tagid as tagid
              FROM oh_brucelosis
              LEFT JOIN vacuno v ON oh_brucelosis_tagid = v.tagid 
              ORDER BY oh_brucelosis_fecha ASC";
    
    // Fetch all records as associative array
    $result = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    // Output the result as JSON
    echo json_encode($result);
    
} catch (Exception $e) {
    // Return error as JSON
    echo json_encode(['error' => $e->getMessage()]);
}
?> 