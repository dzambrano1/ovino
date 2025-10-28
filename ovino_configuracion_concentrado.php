<?php
require_once './pdo_conexion.php';  

// Debug connection type
if (!($conn instanceof PDO)) {
    die("Error: Connection is not a PDO instance. Please check your connection setup.");
}
// Enable PDO error mode to get better error messages
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ovino PLAN ALIMENTO CONCENTRADO</title>
<!-- Link to the Favicon -->
<link rel="icon" href="images/default_image.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Bootstrap 5.3.2 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- DataTables 1.13.7 / Responsive 2.5.0 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<!-- DataTables Buttons 2.4.1 -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="./ovino.css">

<!-- Professional Feeding Plan Table Styling -->
<style>
.feeding-plan-container {
    margin: 2rem 0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 15px;
    overflow: hidden;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.feeding-plan-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}

.feeding-plan-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.feeding-plan-title {
    font-weight: 700;
    font-size: 1.4rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    margin: 0;
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.feeding-plan-subtitle {
    font-size: 1rem;
    font-weight: 500;
    margin-top: 0.5rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

.feeding-plan-body {
    background: #ffffff;
    padding: 0;
}

.feeding-plan-table {
    margin: 0;
    border: none;
    font-size: 0.9rem;
    line-height: 1.5;
}

.feeding-plan-table thead th {
    background: linear-gradient(135deg, #155724 0%, #0f5132 100%);
    color: #ffffff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.5rem;
    border: none;
    font-size: 0.8rem;
    position: relative;
    text-align: center;
}

.feeding-plan-table thead th:first-child {
    border-top-left-radius: 0;
    text-align: left;
}

.feeding-plan-table thead th:last-child {
    border-top-right-radius: 0;
}

.feeding-plan-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.feeding-plan-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.feeding-plan-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.feeding-plan-table tbody tr:nth-child(even):hover {
    background: linear-gradient(135deg, #f0fff0 0%, #e6ffe6 100%);
}

.feeding-plan-table tbody td {
    padding: 0.75rem 0.5rem;
    border: none;
    vertical-align: middle;
    position: relative;
    text-align: center;
}

.feeding-plan-table tbody td:first-child {
    border-left: 4px solid transparent;
    transition: border-left-color 0.3s ease;
    text-align: left;
}

.feeding-plan-table tbody tr:hover td:first-child {
    border-left-color: #28a745;
}

.feeding-plan-table tbody td strong {
    color: #155724;
    font-weight: 600;
    font-size: 0.95rem;
}

.feeding-plan-table tbody td:nth-child(2),
.feeding-plan-table tbody td:nth-child(3) {
    font-weight: 600;
    color: #dc3545;
}

.feeding-plan-table tbody td:nth-child(4),
.feeding-plan-table tbody td:nth-child(5) {
    font-weight: 600;
    color: #fd7e14;
}

.feeding-plan-table tbody td:nth-child(6) {
    font-weight: 500;
    color: #6f42c1;
}

.feeding-plan-table tbody td:nth-child(7) {
    font-size: 0.85rem;
    color: #495057;
    text-align: left;
    line-height: 1.4;
}

.feeding-plan-components {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border: none;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.feeding-plan-components h6 {
    color: #155724;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .feeding-plan-table {
        font-size: 0.8rem;
    }
    
    .feeding-plan-table thead th,
    .feeding-plan-table tbody td {
        padding: 0.4rem 0.3rem;
    }
    
    .feeding-plan-title {
        font-size: 1.2rem;
    }
    
    .feeding-plan-components {
        padding: 0.75rem 1rem;
    }
}

/* Print styles */
@media print {
    .feeding-plan-container {
        box-shadow: none;
        border: 2px solid #155724;
    }
    
    .feeding-plan-table tbody tr:hover {
        background: transparent !important;
        transform: none !important;
        box-shadow: none !important;
    }
}
</style>

<!-- JS -->
<!-- jQuery 3.7.0 -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<!-- Bootstrap 5.3.2 Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables 1.13.7 / Responsive 2.5.0 -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<!-- DataTables Buttons 2.4.1 -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

</head>
<body>
<!-- Add back button before the header container -->
<a href="./ovino_configuracion.php" class="back-btn">
    <i class="fas fa-arrow-left"></i>
</a>
<!-- Icon Navigation Buttons -->

<div class="container nav-icons-container">
    <div class="icon-button-container">
        <button onclick="window.location.href='../inicio.php'" class="icon-button">
            <img src="./images/default_image.png" alt="Inicio" class="nav-icon">
        </button>
        <span class="button-label">INICIO</span>
    </div>
    <div class="icon-button-container">
        <button onclick="window.location.href='./ovino_registros.php'" class="icon-button">
            <img src="./images/registros.png" alt="Inicio" class="nav-icon">
        </button>
        <span class="button-label">REGISTROS</span>
    </div>
    <div class="icon-button-container">
        <button onclick="window.location.href='./inventario_ovino.php'" class="icon-button">
            <img src="./images/robot-de-chat.png" alt="Inicio" class="nav-icon">
        </button>
        <span class="button-label">VETERINARIO</span>
    </div>    
    <div class="icon-button-container">
        <button onclick="window.location.href='./ovino_indices.php'" class="icon-button">
            <img src="./images/indices.png" alt="Inicio" class="nav-icon">
        </button>
        <span class="button-label">INDICES</span>
    </div>
</div>

<!-- Feeding Plan Section -->
<div class="container mt-4">
    <div class="feeding-plan-container">
        <div class="feeding-plan-header">
            <h4 class="feeding-plan-title">
                <i class="fas fa-seedling"></i>
                <span>🐑 Plan Alimentario Ovino Venezuela 2025</span>
            </h4>
            <div class="feeding-plan-components">
                <h6><i class="fas fa-list-ul"></i>Componentes: Concentrado + Melaza + Sal Mineral + Vitaminas</h6>
            </div>
        </div>
        <div class="feeding-plan-body">
            <div class="table-responsive">
                <table class="feeding-plan-table table table-hover">
                    <thead>
                        <tr>
                            <th>Etapa / Edad</th>
                            <th>Concentrado (g/día)</th>
                            <th>Melaza (% en mezcla)</th>
                            <th>Sal Mineral (g/día)</th>
                            <th>Vitaminas / Suplemento</th>
                            <th>Vía / Forma</th>
                            <th>Observaciones clave</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Corderos (1–2 meses)</strong></td>
                            <td>150–250</td>
                            <td>5–8%</td>
                            <td>5–8</td>
                            <td>ADE CALBOV® (1:50 con sal)</td>
                            <td>Oral / mezcla seca</td>
                            <td>Introducir alimento iniciador con melaza para palatabilidad. Sal mineral libre.</td>
                        </tr>
                        <tr>
                            <td><strong>Crecimiento (2–4 meses)</strong></td>
                            <td>300–500</td>
                            <td>8–10%</td>
                            <td>10–15</td>
                            <td>ADE CALBOV® + premezcla B-complex</td>
                            <td>Oral / mezcla seca</td>
                            <td>Alta demanda proteica. Melaza mejora consumo. Sal mineral con vitaminas.</td>
                        </tr>
                        <tr>
                            <td><strong>Desarrollo (4–6 meses)</strong></td>
                            <td>400–600</td>
                            <td>5–8%</td>
                            <td>15–20</td>
                            <td>ADE CALBOV® + minerales traza</td>
                            <td>Oral / mezcla seca</td>
                            <td>Ajustar según peso. Introducir rotación de forrajes y leguminosas.</td>
                        </tr>
                        <tr>
                            <td><strong>Engorde / Finalización</strong></td>
                            <td>600–800</td>
                            <td>10–12%</td>
                            <td>20–25</td>
                            <td>ADE CALBOV® + Vitamina E extra</td>
                            <td>Oral / mezcla húmeda</td>
                            <td>Maximizar conversión. Melaza como fuente energética. Sal mineral con selenio.</td>
                        </tr>
                        <tr>
                            <td><strong>Mantenimiento adultos</strong></td>
                            <td>200–300</td>
                            <td>0–5%</td>
                            <td>10–15</td>
                            <td>ADE CALBOV® (1:50 con sal)</td>
                            <td>Oral / libre acceso</td>
                            <td>Bajo requerimiento. Pasto base + suplemento estratégico.</td>
                        </tr>
                        <tr>
                            <td><strong>Gestantes (último mes)</strong></td>
                            <td>400–500</td>
                            <td>5–8%</td>
                            <td>20</td>
                            <td>ADE CALBOV® + Vitamina A/D/E</td>
                            <td>Oral / mezcla seca</td>
                            <td>Evitar toxemia gestacional. Alta demanda energética y mineral.</td>
                        </tr>
                        <tr>
                            <td><strong>Lactancia (0–2 meses post-parto)</strong></td>
                            <td>500–700</td>
                            <td>8–10%</td>
                            <td>25–30</td>
                            <td>ADE CALBOV® + Vitamina E + Calcio</td>
                            <td>Oral / mezcla húmeda</td>
                            <td>Máximo requerimiento. Crucial para producción de leche y recuperación postparto.</td>
                        </tr>
                        <tr>
                            <td><strong>Sementales</strong></td>
                            <td>400–600</td>
                            <td>5–8%</td>
                            <td>20</td>
                            <td>ADE CALBOV® + Zinc / Selenio</td>
                            <td>Oral / mezcla seca</td>
                            <td>Mantener condición corporal. Apoyo reproductivo.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container text-center">
  <h3  class="container mt-4 text-white" class="collapse" id="section-historial-produccion-ovino">
  CONFIGURACION PRODUCTOS ALIMENTOS CONCENTRADOS
  </h3>
  <p class="text-dark-50 text-center mb-4">Esta tabla muestra la configuración de productos alimenticios concentrados</p>
</div> 

<!-- Add New Concentrado Button -->
<div class="container my-3 d-flex justify-content-center">
  <button type="button" class="new-concentrado-btn" data-bs-toggle="modal" data-bs-target="#newEntryModal" >
    <i class="fas fa-plus-circle me-2"></i>Nuevo Concentrado
  </button>
</div>

<div class="modal fade" id="newEntryModal" tabindex="-1" aria-labelledby="newEntryModalLabel">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="newEntryModalLabel">
                  <i class="fas fa-plus-circle me-2"></i>Configurar Nuevo Concentrado
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                    <form id="newConcentradoForm">
                            <input type="hidden" id="new_id" name="id" value="">
                            
                            <div class="mb-3">
                                <label for="new_concentrado" class="form-label">Alimento</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-utensils"></i>
                                    </span>
                                    <input type="text" class="form-control" id="new_concentrado" name="concentrado" placeholder="Nombre del alimento" required>
                                </div>                                
                            </div>
                            
                            <div class="mb-3">                            
                                <label for="new_etapa" class="form-label">Etapa</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-layer-group"></i>
                                    </span>
                                    <select class="form-select" id="new_etapa" name="etapa" required>
                                        <option value="">Seleccionar etapa</option>
                                        <?php
                                        // Fetch distinct names from the database
                                        $sql_etapas = "SELECT DISTINCT oc_etapas_nombre FROM oc_etapas ORDER BY oc_etapas_nombre ASC";
                                        $stmt_etapas = $conn->prepare($sql_etapas);
                                        $stmt_etapas->execute();
                                        $etapas = $stmt_etapas->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($etapas as $etapa_row) {
                                            echo '<option value="' . htmlspecialchars($etapa_row['oc_etapas_nombre']) . '">' . htmlspecialchars($etapa_row['oc_etapas_nombre']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_costo" class="form-label">Costo ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-1-wave"></i>
                                    </span>
                                    <input type="number" step="0.01" class="form-control" id="new_costo" name="costo" placeholder="0.00" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_vigencia" class="form-label">Vigencia (días)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-days"></i>
                                    </span>
                                    <input type="number" class="form-control" id="new_vigencia" name="vigencia" placeholder="0" required>
                                </div>
                            </div>
                        </form>
          </div>
          <div class="modal-footer btn-group">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="fas fa-times me-1"></i>Cancelar
              </button>
              <button type="button" class="btn btn-success" id="saveNewConcentrado">
                  <i class="fas fa-save me-1"></i>Guardar
              </button>
          </div>
      </div>
  </div>
</div>

  <!-- DataTable for ah_concentrado records -->
  <div class="container table-section" style="display: block;">
      <div class="table-responsive">
          <table id="concentradoTable" class="table table-striped table-bordered">
              <thead>
                  <tr>
                    <th class="text-center">Acciones</th>
                    <th class="text-center">Producto</th>
                    <th class="text-center">Etapa</th>
                    <th class="text-center">Costo ($/kg)</th>
                    <th class="text-center">Vigencia (dias)</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                      $concentradoQuery = "SELECT * FROM oc_concentrado";
                      
                      try {
                          $stmt = $conn->prepare($concentradoQuery);
                          $stmt->execute();
                          $concentradosData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          if (empty($concentradosData)) {
                              echo "<tr><td colspan='5' class='text-center'>No hay registros disponibles</td></tr>";
                          } else {
                              foreach ($concentradosData as $row) {
                              echo "<tr>";
                              
                              // Column 0: Actions
                              echo '<td class="text-center">';
                              echo '    <div class="btn-group" role="group">';
                              echo '        <button class="btn btn-warning btn-sm edit-concentrado" 
                                              data-id="' . htmlspecialchars($row['id'] ?? '') . '" 
                                              data-concentrado="' . htmlspecialchars($row['oc_concentrado_nombre'] ?? '') . '" 
                                              data-etapa="' . htmlspecialchars($row['oc_concentrado_etapa'] ?? '') . '"
                                              data-costo="' . htmlspecialchars($row['oc_concentrado_costo'] ?? '') . '" 
                                              data-vigencia="' . htmlspecialchars($row['oc_concentrado_vigencia'] ?? '') . '"
                                              title="Editar Configuración Concentrado">
                                              <i class="fas fa-edit"></i>
                                          </button>';
                              echo '        <button class="btn btn-danger btn-sm delete-concentrado" 
                                              data-id="' . htmlspecialchars($row['id'] ?? '') . '"
                                              title="Eliminar Configuración Concentrado">
                                              <i class="fas fa-trash"></i>
                                          </button>';
                              echo '    </div>';
                              echo '</td>';
                              
                              // Column 1: Producto
                              echo "<td>" . htmlspecialchars($row['oc_concentrado_nombre'] ?? '') . "</td>";
                              // Column 2: Etapa
                              echo "<td>" . htmlspecialchars($row['oc_concentrado_etapa'] ?? '') . "</td>";
                              // Column 3: Costo
                              echo "<td>" . htmlspecialchars($row['oc_concentrado_costo'] ?? 'N/A') . "</td>";
                              // Column 4: Vigencia
                              echo "<td>" . htmlspecialchars($row['oc_concentrado_vigencia'] ?? 'N/A') . "</td>";
                              echo "</tr>";
                          }
                      }
                  } catch (PDOException $e) {
                      echo "<tr><td colspan='5' class='text-center text-danger'>Error al cargar datos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                  }
                  ?>
              </tbody>
          </table>
      </div>
</div>

<!-- Initialize DataTable for concentradoTable -->
<script>
$(document).ready(function() {
    $('#concentradoTable').DataTable({
        // Set initial page length
        pageLength: 10,
        
        // Configure length menu options
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"]
        ],
        
        // Order by Producto column ascending (column index 1)
        order: [[1, 'asc']],
        
        // Spanish language
        language: {
            url: './es-ES.json',
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        
        // Enable responsive features
        responsive: true,
        
        // Configure DOM layout and buttons
        dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12 col-md-6"l>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        
        buttons: [
            {
                extend: 'collection',
                text: 'Exportar',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
        ],
        
        // Column specific settings for 5 columns
        columnDefs: [
            {
                targets: [0], // Actions column
                orderable: false,
                searchable: false,
                width: '120px'
            },
            {
                targets: [1, 2], // Producto, Etapa columns
                orderable: true,
                searchable: true
            },
            {
                targets: [3], // Costo column
                orderable: true,
                searchable: true,
                render: function(data, type, row) {
                    if (type === 'display' && data !== 'N/A' && data !== 'No Registrado') {
                        // Attempt to parse only if data looks like a number
                        const num = parseFloat(data);
                        if (!isNaN(num)) {
                            return '$' + num.toLocaleString('es-ES', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                    return data; // Return original data if not display or not a valid number
                }
            },
            {
                targets: [4], // Vigencia column
                orderable: true,
                searchable: true,
                render: function(data, type, row) {
                    if (type === 'display' && data !== 'N/A' && data !== 'No Registrado') {
                        // Attempt to parse only if data looks like a number
                        const num = parseFloat(data);
                        if (!isNaN(num)) {
                            return num.toLocaleString('es-ES', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }) + ' días';
                        }
                    }
                    return data; // Return original data if not display or not a valid number
                }
            }
        ]
    });
});
</script>

<!-- JavaScript for Edit and Delete buttons -->
<script>
$(document).ready(function() {
    // --- Initialize Modals Once --- 
    var newEntryModalElement = document.getElementById('newEntryModal');
    var newEntryModalInstance = new bootstrap.Modal(newEntryModalElement); 
    // Note: editConcentradoModal is created dynamically later, so no need to initialize here.

    // Handle new entry form submission
    $('#saveNewConcentrado').click(function() {
        // Validate the form
        var form = document.getElementById('newConcentradoForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get form data
        var formData = {
            concentrado: $('#new_concentrado').val(),
            etapa: $('#new_etapa').val(),
            costo: $('#new_costo').val(),
            vigencia: $('#new_vigencia').val()
        };
        
        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: '¿Confirmar registro?',
            text: `¿Desea registrar el alimento ${formData.concentrado} ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor espere mientras se procesa la información',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Send AJAX request to insert the record
                $.ajax({
                    url: 'process_configuracion_concentrado.php',
                    type: 'POST',
                    data: {
                        action: 'insert',
                        concentrado: formData.concentrado,
                        etapa: formData.etapa,
                        costo: formData.costo,
                        vigencia: formData.vigencia
                    },
                    success: function(response) {
                        console.log('Success response:', response);
                        // Close the modal
                        newEntryModalInstance.hide();
                        
                        // Show success message
                        Swal.fire({
                            title: '¡Registro exitoso!',
                            text: 'El registro de concentrado ha sido guardado correctamente',
                            icon: 'success',
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            // Reload the page to show updated data
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', xhr, status, error);
                        console.log('Request data:', {
                            action: 'insert',
                            concentrado: formData.concentrado,
                            etapa: formData.etapa,
                            costo: formData.costo,
                            vigencia: formData.vigencia
                        });
                        
                        // Show error message
                        let errorMsg = 'Error al procesar la solicitud';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            console.log('Error response:', response);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            // Use default error message
                        }
                        
                        Swal.fire({
                            title: 'Error',
                            text: errorMsg,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    });

    // Handle edit button click
    $('.edit-concentrado').click(function() {
        var id = $(this).data('id');
        var concentrado = $(this).data('concentrado');
        var etapa = $(this).data('etapa');
        var costo = $(this).data('costo');
        var vigencia = $(this).data('vigencia');

        console.log('Edit button clicked. All data captured:', {
            id: id,
            concentrado: concentrado,
            etapa: etapa,
            costo: costo,
            vigencia: vigencia
        }); // Debug log 1
        
        // Simple check if ID is missing before creating modal
        if (!id) {
             console.error('Attempting to edit a record with a missing ID.');
             Swal.fire({
                 title: 'Error',
                 text: 'No se puede editar este registro porque falta el ID.',
                 icon: 'error',
                 confirmButtonColor: '#dc3545'
             });
             return; // Stop execution if ID is missing
        }

        // Edit PLAN ALIMENTO CONCENTRADO Modal dialog for editing
        var modalHtml = `
        <div class="modal fade" id="editConcentradoModal" tabindex="-1" aria-labelledby="editConcentradoModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editConcentradoModalLabel">
                            <i class="fas fa-edit me-2"></i>Editar Concentrado
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editConcentradoForm">
                            <input type="hidden" id="edit_id" name="id" value="${id}">
                            
                            <div class="mb-3">
                                <label for="edit_concentrado" class="form-label">Alimento</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-utensils"></i>
                                    </span>
                                    <input type="text" class="form-control" id="edit_concentrado" name="concentrado" value="${concentrado}" required>
                                </div>                                
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_etapa" class="form-label">Etapa</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-layer-group"></i>
                                    </span>
                                    <select class="form-select" id="edit_etapa" name="etapa" required>
                                        <option value="">Seleccionar etapa</option>
                                        <?php
                                        $sql_etapas = "SELECT DISTINCT oc_etapas_nombre FROM oc_etapas ORDER BY oc_etapas_nombre ASC";
                                        $stmt_etapas = $conn->prepare($sql_etapas);
                                        $stmt_etapas->execute();
                                        $etapas = $stmt_etapas->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($etapas as $etapa_row) {
                                            echo '<option value="' . htmlspecialchars($etapa_row['oc_etapas_nombre']) . '">' . htmlspecialchars($etapa_row['oc_etapas_nombre']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_costo" class="form-label">Costo ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-1-wave"></i>
                                    </span>
                                    <input type="number" step="0.01" class="form-control" id="edit_costo" name="costo" value="${costo}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_vigencia" class="form-label">Vigencia (días)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-days"></i>
                                    </span>
                                    <input type="number" class="form-control" id="edit_vigencia" name="vigencia" value="${vigencia}" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer btn-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="saveEditConcentrado">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Remove any existing modal
        $('#editConcentradoModal').remove();
        
        // Add the modal to the page
        $('body').append(modalHtml);
        
        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('editConcentradoModal'));
        editModal.show();
        
        // Set the selected values in the form
        $('#edit_concentrado').val(concentrado);
        $('#edit_etapa').val(etapa);
        
        // Debug: Log the values being set
        console.log('Setting form values:', {
            concentrado: concentrado,
            etapa: etapa,
            costo: costo,
            vigencia: vigencia
        });
        
        // Handle save button click
        $('#saveEditConcentrado').click(function() {
            // Create a form object to properly validate
            var form = document.getElementById('editConcentradoForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            var formData = {
                id: $('#edit_id').val(),
                concentrado: $('#edit_concentrado').val(),
                etapa: $('#edit_etapa').val(),
                costo: $('#edit_costo').val(),
                vigencia: $('#edit_vigencia').val()
            };
            
            console.log('Save changes clicked. Form Data being sent:', formData); // Debug log 2
            
            // Show confirmation dialog
            Swal.fire({
                title: '¿Guardar cambios?',
                text: `¿Desea actualizar la configuracion de concentrado?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Actualizando...',
                        text: 'Por favor espere mientras se procesa la información',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Send AJAX request to update the record
                    $.ajax({
                        url: 'process_configuracion_concentrado.php',
                        type: 'POST',
                        data: {
                            action: 'update',
                            id: formData.id,
                            concentrado: formData.concentrado,
                            etapa: formData.etapa,
                            costo: formData.costo,
                            vigencia: formData.vigencia
                        },
                        success: function(response) {
                            console.log('Update success response:', response);
                            // Close the modal
                            editModal.hide();
                            
                            // Show success message
                            Swal.fire({
                                title: '¡Actualización exitosa!',
                                text: 'El registro ha sido actualizado correctamente',
                                icon: 'success',
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                // Reload the page to show updated data
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Update AJAX error:', xhr, status, error);
                            console.log('Update request data:', {
                                action: 'update',
                                id: formData.id,
                                concentrado: formData.concentrado,
                                etapa: formData.etapa,
                                costo: formData.costo,
                                vigencia: formData.vigencia
                            });
                            
                            // Show error message
                            let errorMsg = 'Error al procesar la solicitud';
                            
                            try {
                                const response = JSON.parse(xhr.responseText);
                                console.log('Update error response:', response);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                console.error('Error parsing update response:', e);
                                // Use default error message
                            }
                            
                            Swal.fire({
                                title: 'Error',
                                text: errorMsg,
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        });
    });
    
    // Handle delete button click
    $('.delete-concentrado').click(function() {
        var id = $(this).data('id');
        
        // Confirm before deleting using SweetAlert2
        Swal.fire({
            title: '¿Eliminar registro?',
            text: `¿Está seguro de que desea eliminar la configuracion de concentrado? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor espere mientras se procesa la solicitud',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Send AJAX request to delete the record
                $.ajax({
                    url: 'process_configuracion_concentrado.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        console.log('Delete success response:', response);
                        // Show success message
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El registro ha sido eliminado correctamente',
                            icon: 'success',
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            // Reload the page to show updated data
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete AJAX error:', xhr, status, error);
                        console.log('Delete request data:', {
                            action: 'delete',
                            id: id
                        });
                        
                        // Show error message
                        let errorMsg = 'Error al procesar la solicitud';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            console.log('Delete error response:', response);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
                            console.error('Error parsing delete response:', e);
                            // Use default error message
                        }
                        
                        Swal.fire({
                            title: 'Error',
                            text: errorMsg,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    });

    // Handle new register button click for animals without history
    $(document).on('click', '.register-new-concentrado-btn', function() { 
        // Get tagid from the button's data-tagid-prefill attribute
        var tagid = $(this).data('tagid-prefill'); 
        
        // Clear previous data in the modal
        $('#newConcentradoForm')[0].reset();
        $('#new_id').val(''); // Ensure ID is cleared
        
      
        
        // Show the new entry modal using the existing instance
        newEntryModalInstance.show(); 
    });
});
</script>

<!-- Sheep Daily Portion Calculator Section -->
<div class="container text-center mt-5">
    <h3 class="container mt-4 text-white">
        CALCULADORA RACION DIARIA OVINOS Vs RETORNO INVERSION
    </h3>
    <p class="text-dark-50 text-center mb-4">Herramienta de asesoría financiera para determinar la inversión óptima en alimentación concentrada y forraje para ovinos</p>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Calculator Form -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Parámetros de Cálculo</h5>
                </div>
                <div class="card-body">
                    <form id="goatCalculatorForm">
                        <div class="mb-3">
                            <label for="peso_inicial" class="form-label">Peso Inicial (kg) (Peso Tipico 2.5 Kg al nacer)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-weight"></i></span>
                                <input type="number" step="0.1" class="form-control" id="peso_inicial" name="peso_inicial" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="precio_kg_inicial" class="form-label">Precio en pie inicial ($/kg)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" step="0.01" class="form-control" id="precio_kg_inicial" name="precio_kg_inicial" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="peso_final" class="form-label">Peso final (kg)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-weight"></i></span>
                                <input type="number" step="0.1" class="form-control" id="peso_final" name="peso_final" placeholder="0.0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="precio_kg_final" class="form-label">Precio en pie final ($/kg)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" step="0.1" class="form-control" id="precio_kg_final" name="precio_kg_final" placeholder="0.0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="duracion_dias" class="form-label">Periodo de Evaluación (días)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-days"></i></span>
                                <input type="number" class="form-control" id="duracion_dias" name="duracion_dias" placeholder="0" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fcr_ajustable" class="form-label">FCR (Factor de Conversión) - Rango: 3.5 - 6.0</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>
                                <input type="number" step="0.1" class="form-control" id="fcr_ajustable" name="fcr_ajustable" placeholder="4.5" min="3.5" max="6.0" value="4.5" required>
                                <button class="btn btn-outline-info" type="button" id="optimizeFcrBtn" title="Calcular FCR Óptimo">
                                    <i class="fas fa-magic"></i> Óptimo
                                </button>
                            </div>
                            <small class="form-text text-muted">FCR típico para ovinos: 3.5-6.0. Menor FCR = más eficiente. Use "Óptimo" para calcular el FCR que maximiza ROI.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="porcentaje_concentrado" class="form-label">% Concentrado en la dieta</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                <input type="number" step="1" class="form-control" id="porcentaje_concentrado" name="porcentaje_concentrado" placeholder="50" min="40" max="70" value="50" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="form-text text-muted">Para ovinos en crecimiento/engorde: 40-70%. Típico: 50%</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="costo_concentrado_kg" class="form-label">Costo Concentrado ($/kg)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" step="0.01" class="form-control" id="costo_concentrado_kg" name="costo_concentrado_kg" placeholder="0.00" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="costo_forraje_kg" class="form-label">Costo Forraje ($/kg) (Minimo 0.01)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-leaf"></i></span>
                                <input type="number" step="0.01" class="form-control" id="costo_forraje_kg" name="costo_forraje_kg" placeholder="0.01" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" id="calculateBtn">
                                <i class="fas fa-calculator me-2"></i>RETORNO INVERSION
                            </button>
                            <button type="button" class="btn btn-secondary" id="clearBtn">
                                <i class="fas fa-eraser me-2"></i>Limpiar Formulario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Display -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Resultados del Análisis</h5>
                </div>
                <div class="card-body" id="resultsContainer">
                    <div class="text-center text-muted" id="noResultsMessage">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Complete el formulario y presione "Calcular ROI" para ver los resultados del análisis financiero.</p>
                    </div>
                    
                    <div id="calculationResults" style="display: none;">
                        <!-- Step-by-step calculation results will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Calculate button click handler
    $('#calculateBtn').click(function() {
        calculateSheepROI();
    });
    
    // Clear button click handler
    $('#clearBtn').click(function() {
        $('#goatCalculatorForm')[0].reset();
        $('#fcr_ajustable').val('4.5'); // Reset FCR to default for sheep
        $('#porcentaje_concentrado').val('50'); // Reset percentage to default for sheep
        $('#calculationResults').hide();
        $('#noResultsMessage').show();
    });
    
    // Optimize FCR button click handler
    $('#optimizeFcrBtn').click(function() {
        const pesoInicial = parseFloat($('#peso_inicial').val()) || 0;
        const precioKgInicial = parseFloat($('#precio_kg_inicial').val()) || 0;
        const pesoFinal = parseFloat($('#peso_final').val()) || 0;
        const precioKgFinal = parseFloat($('#precio_kg_final').val()) || 0;
        const costoConcentradoKg = parseFloat($('#costo_concentrado_kg').val()) || 0;
        const costoForrajeKg = parseFloat($('#costo_forraje_kg').val()) || 0;
        const porcentajeConcentrado = parseFloat($('#porcentaje_concentrado').val()) || 50;
        
        // Check if we have enough data to optimize
        if (pesoInicial === 0 || pesoFinal === 0 || precioKgFinal === 0 || costoConcentradoKg === 0 || costoForrajeKg === 0) {
            Swal.fire({
                title: 'Datos Insuficientes',
                text: 'Complete peso inicial, peso final, precio final, costo del concentrado y forraje para calcular el FCR óptimo.',
                icon: 'warning',
                confirmButtonColor: '#ffc107'
            });
            return;
        }
        
        const kgGanados = pesoFinal - pesoInicial;
        if (kgGanados <= 0) {
            Swal.fire({
                title: 'Error de Datos',
                text: 'El peso final debe ser mayor al peso inicial.',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        // Calculate optimal FCR (minimize cost while maximizing gain)
        // The optimal FCR is the one that maximizes ROI for sheep
        let bestFcr = 6.0;
        let bestRoi = -1000;
        
        const porcentajeForraje = 100 - porcentajeConcentrado;
        
        for (let testFcr = 3.5; testFcr <= 6.0; testFcr += 0.1) {
            const alimentoTotalConsumido = kgGanados * testFcr;
            const concentradoConsumido = alimentoTotalConsumido * (porcentajeConcentrado / 100);
            const forrajeConsumido = alimentoTotalConsumido * (porcentajeForraje / 100);
            const costoTotalAlimento = (concentradoConsumido * costoConcentradoKg) + (forrajeConsumido * costoForrajeKg);
            const costoTotalCompra = pesoInicial * precioKgInicial;
            const costoTotal = costoTotalCompra + costoTotalAlimento;
            const ingresoVenta = pesoFinal * precioKgFinal;
            const roi = costoTotal > 0 ? ((ingresoVenta - costoTotal) / costoTotal * 100) : -1000;
            
            if (roi > bestRoi) {
                bestRoi = roi;
                bestFcr = testFcr;
            }
        }
        
        $('#fcr_ajustable').val(bestFcr.toFixed(1));
        
        Swal.fire({
            title: 'FCR Óptimo Calculado',
            text: `FCR óptimo: ${bestFcr.toFixed(1)} (ROI estimado: ${bestRoi.toFixed(2)}%)`,
            icon: 'success',
            confirmButtonColor: '#28a745'
        });
        
        // Trigger calculation if form is complete
        if (isFormComplete()) {
            calculateSheepROI();
        }
    });
    
    // FCR input change handler
    $('#fcr_ajustable').on('input', function() {
        // Trigger calculation if form is complete
        if (isFormComplete()) {
            calculateSheepROI();
        }
    });
    
    // Real-time calculation on input change
    $('#goatCalculatorForm input').on('input change', function() {
        if (isFormComplete()) {
            calculateSheepROI();
        }
    });
    
    function isFormComplete() {
        let complete = true;
        
        // Check all required fields
        $('#goatCalculatorForm input[required]').each(function() {
            if ($(this).val() === '') {
                complete = false;
                return false;
            }
        });
        
        return complete;
    }
    
    function calculateSheepROI() {
        // Validate form
        const form = document.getElementById('goatCalculatorForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get input values
        const pesoInicial = parseFloat($('#peso_inicial').val()) || 0;
        const precioKgInicial = parseFloat($('#precio_kg_inicial').val()) || 0;
        const pesoFinal = parseFloat($('#peso_final').val()) || 0;
        const precioKgFinal = parseFloat($('#precio_kg_final').val()) || 0;
        const costoConcentradoKg = parseFloat($('#costo_concentrado_kg').val()) || 0;
        const costoForrajeKg = parseFloat($('#costo_forraje_kg').val()) || 0;
        const duracionDias = parseInt($('#duracion_dias').val()) || 0;
        
        // Get FCR for sheep
        const fcr = parseFloat($('#fcr_ajustable').val()) || 4.5;
        
        // For sheep, we use mixed feeding (concentrate + forage)
        const porcentajeConcentrado = parseFloat($('#porcentaje_concentrado').val()) || 50;
        const porcentajeForraje = 100 - porcentajeConcentrado;
        
        // Calculate derived values
        const kgGanados = pesoFinal - pesoInicial;
        const gananciaDiaria = duracionDias > 0 ? (kgGanados / duracionDias) : 0;
        
        // Calculate food consumption using user-defined FCR
        const alimentoTotalConsumido = kgGanados * fcr;
        
        // For sheep, calculate separate concentrate and forage consumption
        const concentradoConsumido = alimentoTotalConsumido * (porcentajeConcentrado / 100);
        const forrajeConsumido = alimentoTotalConsumido * (porcentajeForraje / 100);
        
        // Calculate daily rations (this is the key result!)
        const racionDiariaTotal = duracionDias > 0 ? (alimentoTotalConsumido / duracionDias) : 0;
        const racionDiariaForraje = duracionDias > 0 ? (forrajeConsumido / duracionDias) : 0;
        const racionDiariaConcentrado = duracionDias > 0 ? (concentradoConsumido / duracionDias) : 0;
        
        // Calculate break-even point for feed cost
        const costoTotalCompra = pesoInicial * precioKgInicial;
        const ingresoVenta = pesoFinal * precioKgFinal;
        const margenDisponible = ingresoVenta - costoTotalCompra;
        
        // Perform financial calculations (mixed feeding for sheep)
        const costoTotalConcentrado = concentradoConsumido * costoConcentradoKg;
        const costoTotalForraje = forrajeConsumido * costoForrajeKg;
        const costoTotalAlimento = costoTotalConcentrado + costoTotalForraje;
        const costoTotal = costoTotalCompra + costoTotalAlimento;
        const roi = costoTotal > 0 ? ((ingresoVenta - costoTotal) / costoTotal * 100) : 0;
        const ganancia = ingresoVenta - costoTotal;
        
        // Format numbers for display
        const formatCurrency = (value) => '$' + value.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        const formatNumber = (value) => value.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        const formatPercent = (value) => value.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '%';
        
        // Determine ROI status and color
        let roiStatus = '';
        let roiColor = '';
        if (roi > 20) {
            roiStatus = 'Excelente';
            roiColor = 'text-success';
        } else if (roi > 10) {
            roiStatus = 'Bueno';
            roiColor = 'text-info';
        } else if (roi > 0) {
            roiStatus = 'Aceptable';
            roiColor = 'text-warning';
        } else {
            roiStatus = 'Pérdida';
            roiColor = 'text-danger';
        }
        
        // Display results
        const resultsHtml = `
            <div class="calculation-steps">
                <h6 class="text-primary mb-3"><i class="fas fa-list-ol me-2"></i>Cálculos Paso a Paso:</h6>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-primary me-2">1</span>
                        <strong>Kilogramos Ganados</strong>
                    </div>
                    <div class="step-calculation">
                        <code>kg_ganados = peso_final - peso_inicial</code>
                        <div class="step-result">
                            ${formatNumber(pesoFinal)} kg - ${formatNumber(pesoInicial)} kg = <strong>${formatNumber(kgGanados)} kg</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-primary me-2">2</span>
                        <strong>Ganancia Diaria</strong>
                    </div>
                    <div class="step-calculation">
                        <code>ganancia_diaria = kg_ganados ÷ duración_días</code>
                        <div class="step-result">
                            ${formatNumber(kgGanados)} kg ÷ ${duracionDias} días = <strong>${formatNumber(gananciaDiaria)} kg/día</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-primary me-2">3</span>
                        <strong>Factor de Conversión Alimenticia (FCR)</strong>
                    </div>
                    <div class="step-calculation">
                        <code>FCR para Ovinos: ${formatNumber(fcr)} kg alimento/kg ganancia</code>
                        <div class="step-result">
                            <strong>Composición de la dieta:</strong> ${formatNumber(porcentajeConcentrado)}% Concentrado + ${formatNumber(porcentajeForraje)}% Forraje
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-primary me-2">4</span>
                        <strong>Alimento Total Consumido</strong>
                    </div>
                    <div class="step-calculation">
                        <code>alimento_total = kg_ganados × FCR</code>
                        <div class="step-result">
                            ${formatNumber(kgGanados)} kg × ${formatNumber(fcr)} = <strong>${formatNumber(alimentoTotalConsumido)} kg</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-info me-2">5</span>
                        <strong>Distribución de Alimentos</strong>
                    </div>
                    <div class="step-calculation">
                        <code>concentrado_total = alimento_total × (${formatNumber(porcentajeConcentrado)}% ÷ 100)</code>
                        <code>forraje_total = alimento_total × (${formatNumber(porcentajeForraje)}% ÷ 100)</code>
                        <div class="step-result">
                            <strong>Concentrado Total:</strong> ${formatNumber(concentradoConsumido)} kg<br>
                            <strong>Forraje Total:</strong> ${formatNumber(forrajeConsumido)} kg
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-4 border border-success rounded p-3" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                    <div class="step-header">
                        <span class="badge bg-success me-2" style="font-size: 1.1em;">⭐</span>
                        <strong style="color: #155724; font-size: 1.2em;">RACIÓN DIARIA RECOMENDADA PARA OVINOS</strong>
                    </div>
                    <div class="step-calculation" style="background-color: #f8fff9; border: 2px solid #28a745;">
                        <code style="color: #155724; font-weight: bold;">ración_diaria_total = alimento_total ÷ duración_días</code>
                        <div class="step-result text-center">
                            <span style="font-size: 2.0em; color: #155724; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                                🐑 ${formatNumber(racionDiariaTotal)} kg/día
                            </span>
                            <div style="font-size: 1.1em; color: #155724; margin-top: 10px;">
                                <strong>Concentrado:</strong> ${formatNumber(racionDiariaConcentrado)} kg/día<br>
                                <strong>Forraje:</strong> ${formatNumber(racionDiariaForraje)} kg/día
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small style="color: #155724; font-weight: 500;">
                            💡 Los ovinos requieren una dieta mixta de concentrado y forraje para un desarrollo óptimo y salud ruminal.
                        </small>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-info me-2">7</span>
                        <strong>Análisis de Costos de Alimentación</strong>
                    </div>
                    <div class="step-calculation">
                        <code>costo_total_concentrado = concentrado_consumido × costo_concentrado_kg</code>
                        <code>costo_total_forraje = forraje_consumido × costo_forraje_kg</code>
                        <div class="step-result">
                            <strong>Costo Concentrado:</strong> ${formatNumber(concentradoConsumido)} kg × ${formatCurrency(costoConcentradoKg)}/kg = ${formatCurrency(costoTotalConcentrado)}<br>
                            <strong>Costo Forraje:</strong> ${formatNumber(forrajeConsumido)} kg × ${formatCurrency(costoForrajeKg)}/kg = ${formatCurrency(costoTotalForraje)}
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-warning me-2">8</span>
                        <strong>Costo Total de Alimentación</strong>
                    </div>
                    <div class="step-calculation">
                        <code>costo_total_alimento = costo_total_concentrado + costo_total_forraje</code>
                        <div class="step-result">
                            ${formatCurrency(costoTotalConcentrado)} + ${formatCurrency(costoTotalForraje)} = <strong>${formatCurrency(costoTotalAlimento)}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-warning me-2">9</span>
                        <strong>Costo Total de Compra</strong>
                    </div>
                    <div class="step-calculation">
                        <code>costo_total_compra = peso_inicial × precio_kg_inicial</code>
                        <div class="step-result">
                            ${formatNumber(pesoInicial)} kg × ${formatCurrency(precioKgInicial)}/kg = <strong>${formatCurrency(costoTotalCompra)}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-danger me-2">10</span>
                        <strong>Costo Total</strong>
                    </div>
                    <div class="step-calculation">
                        <code>costo_total = costo_total_compra + costo_total_concentrado</code>
                        <div class="step-result">
                            ${formatCurrency(costoTotalCompra)} + ${formatCurrency(costoTotalAlimento)} = <strong>${formatCurrency(costoTotal)}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-3">
                    <div class="step-header">
                        <span class="badge bg-success me-2">11</span>
                        <strong>Ingreso por Venta</strong>
                    </div>
                    <div class="step-calculation">
                        <code>ingreso_venta = peso_final × precio_kg_final</code>
                        <div class="step-result">
                            ${formatNumber(pesoFinal)} kg × ${formatCurrency(precioKgFinal)}/kg = <strong>${formatCurrency(ingresoVenta)}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-item mb-4">
                    <div class="step-header">
                        <span class="badge bg-info me-2">12</span>
                        <strong>ROI (Retorno de Inversión)</strong>
                    </div>
                    <div class="step-calculation">
                        <code>ROI = (ingreso_venta - costo_total) / costo_total × 100</code>
                        <div class="step-result">
                            (${formatCurrency(ingresoVenta)} - ${formatCurrency(costoTotal)}) / ${formatCurrency(costoTotal)} × 100 = <strong class="${roiColor}">${formatPercent(roi)}</strong>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="summary-section">
                    <!-- Destacar Raciones Diarias en el resumen -->
                    <div class="alert alert-success text-center mb-4" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 3px solid #28a745;">
                            <h4 class="alert-heading text-success mb-3">
                                <i class="fas fa-utensils me-2"></i>RACIÓN DIARIA PARA OVINOS
                            </h4>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div style="font-size: 2.5em; color: #155724; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                                    🐑 ${formatNumber(racionDiariaTotal)} kg/día
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <small style="color: #155724; font-weight: 600; font-size: 1.1em;">
                                            <strong>Concentrado:</strong><br>${formatNumber(racionDiariaConcentrado)} kg/día
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small style="color: #155724; font-weight: 600; font-size: 1.1em;">
                                            <strong>Forraje:</strong><br>${formatNumber(racionDiariaForraje)} kg/día
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="text-success mb-3"><i class="fas fa-chart-pie me-2"></i>Resumen Financiero:</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                <span class="summary-label">Inversión Total:</span>
                                <span class="summary-value text-danger">${formatCurrency(costoTotal)}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Ingreso por Venta:</span>
                                <span class="summary-value text-success">${formatCurrency(ingresoVenta)}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Ganancia/Pérdida:</span>
                                <span class="summary-value ${ganancia >= 0 ? 'text-success' : 'text-danger'}">${formatCurrency(ganancia)}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="roi-display text-center">
                                <div class="roi-value ${roiColor}" style="font-size: 2.5em; font-weight: bold;">
                                    ${formatPercent(roi)}
                                </div>
                                <div class="roi-status">
                                    <span class="badge ${roi > 0 ? 'bg-success' : 'bg-danger'} fs-6">${roiStatus}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="alert alert-info mb-2">
                            <h6 class="alert-heading">
                                <i class="fas fa-balance-scale me-1"></i>Análisis de Costos de Alimentación
                            </h6>
                            <p class="mb-1">
                                <strong>Costo total de concentrado:</strong> ${formatCurrency(costoTotalConcentrado)} (${formatNumber((costoTotalConcentrado/costoTotalAlimento*100))}%)
                            </p>
                            <p class="mb-1">
                                <strong>Costo total de forraje:</strong> ${formatCurrency(costoTotalForraje)} (${formatNumber((costoTotalForraje/costoTotalAlimento*100))}%)
                            </p>
                            <p class="mb-0">
                                <strong>Margen disponible para alimentación:</strong> 
                                <span class="${margenDisponible > costoTotalAlimento ? 'text-success' : 'text-danger'}">
                                    ${formatCurrency(margenDisponible)}
                                    ${margenDisponible > costoTotalAlimento ? '(Rentable)' : '(Revisar costos)'}
                                </span>
                            </p>
                        </div>
                        
                        <div class="alert alert-info mb-2">
                            <h6 class="alert-heading">
                                <i class="fas fa-magic me-1"></i>Optimización FCR para Ovinos
                            </h6>
                            <p class="mb-1">
                                <strong>FCR actual:</strong> ${formatNumber(fcr)} 
                                <small class="text-muted">(${fcr <= 4.0 ? 'Excelente' : fcr <= 4.5 ? 'Muy Bueno' : fcr <= 5.0 ? 'Bueno' : fcr <= 5.5 ? 'Aceptable' : 'Mejorable'})</small>
                            </p>
                            <p class="mb-0">
                                <small>💡 Use el botón "Óptimo" para calcular el FCR que maximiza el ROI con los precios actuales.</small>
                            </p>
                        </div>
                        
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            En ovinos, la alimentación mixta (concentrado + forraje) es esencial para la salud ruminal y eficiencia productiva.
                        </small>
                    </div>
                </div>
            </div>
        `;
        
        $('#noResultsMessage').hide();
        $('#calculationResults').html(resultsHtml).show();
    }
});
</script>

<style>
.calculation-steps .step-item {
    border-left: 3px solid #007bff;
    padding-left: 15px;
    margin-left: 15px;
}

.step-header {
    font-weight: 600;
    margin-bottom: 8px;
}

.step-calculation {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
}

.step-calculation code {
    background-color: #e9ecef;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 0.9em;
    display: block;
    margin-bottom: 8px;
}

.step-result {
    font-family: inherit;
    color: #495057;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    padding: 5px 0;
    border-bottom: 1px solid #dee2e6;
}

.summary-label {
    font-weight: 500;
}

.summary-value {
    font-weight: bold;
}

.roi-display {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    border: 2px solid #dee2e6;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

/* Professional Calculator Buttons Styling */
#goatCalculatorForm .d-grid {
    gap: 12px !important;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    padding: 0 20px;
}

#calculateBtn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 12px;
    padding: 17px 40px;
    font-weight: 600;
    font-size: 1.1em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    width: 100%;
}

#calculateBtn:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    transform: translateY(-2px);
}

#calculateBtn:active {
    transform: translateY(0px);
    box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
}

#calculateBtn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

#calculateBtn:hover::before {
    left: 100%;
}

#clearBtn {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    border-radius: 12px;
    padding: 15px 40px;
    font-weight: 500;
    font-size: 1em;
    color: white;
    box-shadow: 0 3px 12px rgba(108, 117, 125, 0.25);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    width: 100%;
}

#clearBtn:hover {
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    box-shadow: 0 5px 18px rgba(108, 117, 125, 0.35);
    transform: translateY(-1px);
    color: white;
}

#clearBtn:active {
    transform: translateY(0px);
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.25);
}

#clearBtn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
    transition: left 0.5s;
}

#clearBtn:hover::before {
    left: 100%;
}

/* Button Icons Animation */
#calculateBtn i, #clearBtn i {
    transition: transform 0.3s ease;
}

#calculateBtn:hover i {
    transform: scale(1.1) rotate(5deg);
}

#clearBtn:hover i {
    transform: scale(1.1) rotate(-5deg);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #calculateBtn, #clearBtn {
        padding: 15px 20px;
        font-size: 1em;
    }
    
    #calculateBtn {
        font-size: 1.05em;
    }
}
</style>
</body>
</html>