<?php
header('Content-Type: application/json');

// Include database connection details
require_once "./pdo_conexion.php"; // Adjust path if necessary

// Use mysqli for connection as in the previous examples
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit();
}

mysqli_set_charset($conn, "utf8");

$data = [];

try {
    // Array of vaccine tables and their corresponding column names
    $vaccines = [
        'Aftosa' => ['table' => 'oh_aftosa', 'dosis' => 'oh_aftosa_dosis', 'costo' => 'oh_aftosa_costo'],
        'Brucelosis' => ['table' => 'oh_brucelosis', 'dosis' => 'oh_brucelosis_dosis', 'costo' => 'oh_brucelosis_costo'],
        'Clostridiosis' => ['table' => 'oh_clostridiosis', 'dosis' => 'oh_clostridiosis_dosis', 'costo' => 'oh_clostridiosis_costo'],
        'Neumonia' => ['table' => 'oh_neumonia', 'dosis' => 'oh_neumonia_dosis', 'costo' => 'oh_neumonia_costo'],
        'Ectima' => ['table' => 'oh_ectima', 'dosis' => 'oh_ectima_dosis', 'costo' => 'oh_ectima_costo'],
        'Garrapatas' => ['table' => 'oh_garrapatas', 'dosis' => 'oh_garrapatas_dosis', 'costo' => 'oh_garrapatas_costo'],
        'Parasitos' => ['table' => 'oh_parasitos', 'dosis' => 'oh_parasitos_dosis', 'costo' => 'oh_parasitos_costo']
    ];

    foreach ($vaccines as $vaccineName => $vaccineInfo) {
        $table = $vaccineInfo['table'];
        $dosisColumn = $vaccineInfo['dosis'];
        $costoColumn = $vaccineInfo['costo'];

        // Query to get total cost for this vaccine type
        $sql = "
            SELECT 
                SUM({$dosisColumn} * {$costoColumn}) AS total_cost
            FROM {$table} 
            WHERE {$dosisColumn} > 0 AND {$costoColumn} > 0
        ";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalCost = $row['total_cost'] ? (float)$row['total_cost'] : 0;
            
            $data[] = [
                'vaccine_name' => $vaccineName,
                'total_cost' => $totalCost
            ];
            
            mysqli_free_result($result);
        } else {
            error_log("Error querying {$table}: " . mysqli_error($conn));
            // Continue with other vaccines even if one fails
            $data[] = [
                'vaccine_name' => $vaccineName,
                'total_cost' => 0
            ];
        }
    }

    echo json_encode($data);

} catch (Exception $e) {
    // Log error if needed
    error_log("Error fetching vaccine costs data: " . $e->getMessage());
    echo json_encode(['error' => 'Error processing request: ' . $e->getMessage()]);
} finally {
    // Close connection
    if (isset($conn)) {
        mysqli_close($conn);
    }
}