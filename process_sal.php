<?php
require_once './pdo_conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = array();
    
    if ($_POST['action'] === 'insert' && isset($_POST['tagid'], $_POST['racion'], $_POST['producto'], $_POST['etapa'], $_POST['costo'], $_POST['fecha_inicio'], $_POST['fecha_fin'])) {
        try {
            // Start transaction to ensure both operations succeed or fail together
            $conn->beginTransaction();
            
            // Insert into oh_sal table
            $stmt = $conn->prepare("INSERT INTO oh_sal (oh_sal_tagid, oh_sal_racion, oh_sal_producto, oh_sal_etapa, oh_sal_costo, oh_sal_fecha_inicio, oh_sal_fecha_fin) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['tagid'],
                $_POST['racion'],
                $_POST['producto'],
                $_POST['etapa'],
                $_POST['costo'],
                $_POST['fecha_inicio'],
                $_POST['fecha_fin']
            ]);
            
            // Update the ovino table with the new etapa for the specific animal
            $stmt_ovino = $conn->prepare("UPDATE ovino SET etapa = ? WHERE tagid = ?");
            $stmt_ovino->execute([
                $_POST['etapa'],
                $_POST['tagid']
            ]);
            
            // Commit the transaction
            $conn->commit();
            
            $response = array(
                'success' => true,
                'message' => 'Registro agregado correctamente en oh_sal y ovino',
                'redirect' => 'ovino_register_sal.php'
            );
            
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $conn->rollBack();
            $response = array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            );
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM oh_sal WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            
            if ($stmt->rowCount() > 0) {
                $response = array(
                    'success' => true,
                    'message' => 'Registro eliminado correctamente',
                    'redirect' => 'ovino_register_sal.php'
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'No se encontró el registro a eliminar'
                );
            }
            
        } catch (PDOException $e) {
            $response = array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            );
        }
    } elseif ($_POST['action'] === 'update' && isset($_POST['id'], $_POST['racion'], $_POST['producto'], $_POST['etapa'], $_POST['costo'], $_POST['fecha_inicio'], $_POST['fecha_fin'])) {
        try {
            // Start transaction to ensure both updates succeed or fail together
            $conn->beginTransaction();
            
            // First, update the oh_sal table
            $stmt = $conn->prepare("UPDATE oh_sal SET oh_sal_racion = ?, oh_sal_producto = ?, oh_sal_etapa = ?, oh_sal_costo = ?, oh_sal_fecha_inicio = ?, oh_sal_fecha_fin = ? WHERE id = ?");
            $stmt->execute([
                $_POST['racion'],
                $_POST['producto'],
                $_POST['etapa'],
                $_POST['costo'],
                $_POST['fecha_inicio'],
                $_POST['fecha_fin'],
                $_POST['id']
            ]);
            
            // Then, update the ovino table with the new etapa for the specific animal
            $stmt_ovino = $conn->prepare("UPDATE ovino SET etapa = ? WHERE tagid = ?");
            $stmt_ovino->execute([
                $_POST['etapa'],
                $_POST['tagid']
            ]);
            
            // Commit the transaction
            $conn->commit();
            
            $response = array(
                'success' => true,
                'message' => 'Registro actualizado correctamente en oh_sal y ovino',
                'redirect' => 'ovino_register_sal.php'
            );
            
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $conn->rollBack();
            $response = array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Acción no válida o datos no proporcionados'
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If we get here, something went wrong
header('Content-Type: application/json');
echo json_encode(array(
    'success' => false,
    'message' => 'Solicitud no válida'
));