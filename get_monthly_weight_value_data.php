<?php
header('Content-Type: application/json');

// Include database connection details
require_once "./pdo_conexion.php"; // Adjust path if necessary

// Use mysqli for connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit();
}

mysqli_set_charset($conn, "utf8");

$data = [];

try {
    // Query to get total monthly weight value from oh_peso table
    // *** IMPORTANT: Assumes weight column is oh_peso_animal and price/kg column is oh_peso_precio ***
    // *** Please adjust column names if they are different in your oh_peso table. ***
    $sql = "
        SELECT 
            DATE_FORMAT(oh_peso_fecha, '%Y-%m') AS month, 
            SUM(oh_peso_animal * oh_peso_precio) AS total_weight_value,
            UNIX_TIMESTAMP(MIN(oh_peso_fecha)) as month_timestamp -- Timestamp for the start of the month
        FROM oh_peso 
        WHERE oh_peso_animal > 0 AND oh_peso_precio > 0 -- Ensure valid weight and price
        GROUP BY month 
        ORDER BY month ASC;
    ";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'month' => $row['month'],
                'total_weight_value' => (float)$row['total_weight_value'], // Ensure value is a float
                'month_timestamp' => (int)$row['month_timestamp'] // Add timestamp to output
            ];
        }
        mysqli_free_result($result);
    } else {
        throw new Exception("Error executing weight value query: " . mysqli_error($conn));
    }

    echo json_encode($data);

} catch (Exception $e) {
    error_log("Error fetching monthly weight value data: " . $e->getMessage());
    echo json_encode(['error' => 'Error processing request: ' . $e->getMessage()]);
} finally {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}