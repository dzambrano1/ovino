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
<title>Ovino Configuracion Sal</title>
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

<style>
/* Professional form styling with left-justified alignment */
.form-label {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
    text-align: left;
}

.form-control,
.form-select {
    text-align: left !important;
    padding: 0.75rem 1rem !important;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: #ffffff;
    height: auto;
    min-height: 48px;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    outline: none;
    background-color: #ffffff;
}

.form-control::placeholder {
    color: #adb5bd;
    font-style: italic;
}

/* Modal styling */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.modal-header {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 12px 12px 0 0;
    border-bottom: none;
    padding: 1.5rem;
}

.modal-title {
    font-weight: 600;
    font-size: 1.25rem;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem;
}

/* Form spacing and layout */
.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mb-2 {
    margin-bottom: 1rem !important;
}

/* Button styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    border: none;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
}

/* Icon styling in labels */
.form-label i {
    color: #007bff;
    width: 20px;
    text-align: center;
}

/* Input focus animation */
.form-control:focus,
.form-select:focus {
    transform: translateY(-1px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-body {
        padding: 1.5rem;
    }
    
    .form-control,
    .form-select {
        padding: 0.625rem 0.875rem !important;
        min-height: 44px;
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

<!-- Add back button before the header container -->
<a href="./ovino_configuracion.php" class="back-btn">
    <i class="fas fa-arrow-left"></i>
</a>
<div class="container text-center">
  <h3  class="container mt-4 text-white" class="collapse" id="section-historial-produccion-ovino">
  CONFIGURACION SAL
  </h3>
</div> 
<!-- New Entry Modal Configuracion Sal -->

<!-- Add New Vacuna Sal Button -->
<div class="container my-3 text-center">
  <button type="button" class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#newEntryModal">
    <i class="fas fa-plus-circle me-2"></i>Nueva Sal
  </button>
</div>

<div class="modal fade" id="newEntryModal" tabindex="-1" aria-labelledby="newEntryModalLabel">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="newEntryModalLabel">
                  <i class="fas fa-plus-circle me-2"></i>Configurar Nueva Sal
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
               <form id="newSalForm">
                   <input type="hidden" id="new_id" name="id" value="">
                   
                   <div class="mb-4">
                       <label for="new_sal" class="form-label fw-bold">
                           <i class="fa-solid fa-syringe me-2"></i>Sal Producto
                       </label>
                       <input type="text" class="form-control" id="new_sal" name="sal" placeholder="Ingrese el nombre del producto" required>
                   </div>
                   
                   <div class="mb-4">
                       <label for="new_etapa" class="form-label fw-bold">
                           <i class="fa-solid fa-syringe me-2"></i>Etapa
                       </label>
                       <select class="form-select" id="new_etapa" name="etapa" required>
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
                   
                   <div class="mb-4">
                       <label for="new_racion" class="form-label fw-bold">
                           <i class="fa-solid fa-eye-dropper me-2"></i>Ración (Kg)
                       </label>
                       <input type="number" step="0.01" class="form-control" id="new_racion" name="racion" placeholder="0.00" required>
                   </div>
                   
                   <div class="mb-4">
                       <label for="new_costo" class="form-label fw-bold">
                           <i class="fa-solid fa-money-bill-1-wave me-2"></i>Costo ($)
                       </label>
                       <input type="number" step="0.01" class="form-control" id="new_costo" name="costo" placeholder="0.00" required>
                   </div>
                   
                   <div class="mb-4">
                       <label for="new_vigencia" class="form-label fw-bold">
                           <i class="fa-solid fa-calendar-days me-2"></i>Vigencia (días)
                       </label>
                       <input type="number" class="form-control" id="new_vigencia" name="vigencia" placeholder="0" required>
                   </div>
               </form>
          </div>
          <div class="modal-footer btn-group">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="fas fa-times me-1"></i>Cancelar
              </button>
              <button type="button" class="btn btn-success" id="saveNewSal">
                  <i class="fas fa-save me-1"></i>Guardar
              </button>
          </div>
      </div>
  </div>
</div>
  
  <!-- DataTable for oh_sal records -->
  
<div class="container table-section" style="display: block;">
      <div class="table-responsive">
          <table id="salTable" class="table table-striped table-bordered">
              <thead>
                  <tr>
                    <th class="text-center">Acciones</th>
                    <th class="text-center">Sal Producto</th>
                    <th class="text-center">Etapa</th>
                    <th class="text-center">Racion (Kg)</th>
                    <th class="text-center">Costo ($)</th>
                    <th class="text-center">Vigencia (dias)</th>                                 
                  </tr>
              </thead>
              <tbody>
                  <?php
                      $salQuery = "SELECT * FROM oc_sal";

                      $stmt = $conn->prepare($salQuery);
                      $stmt->execute();
                      $salsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      if (empty($salsData)) {
                          echo "<tr><td colspan='6' class='text-center'>No hay registros disponibles</td></tr>";
                      } else {
                          foreach ($salsData as $row) {
                              echo "<tr>";
                              
                              // Column 0: Actions
                              echo '<td class="text-center">';
                              echo '    <div class="btn-group" role="group">';
                              echo '        <button class="btn btn-warning btn-sm edit-sal" 
                                              data-id="' . htmlspecialchars($row['id'] ?? '') . '" 
                                              data-sal="' . htmlspecialchars($row['oc_sal_nombre'] ?? '') . '" 
                                              data-etapa="' . htmlspecialchars($row['oc_sal_etapa'] ?? '') . '" 
                                              data-racion="' . htmlspecialchars($row['oc_sal_racion'] ?? '') . '" 
                                              data-costo="' . htmlspecialchars($row['oc_sal_costo'] ?? '') . '" 
                                              data-vigencia="' . htmlspecialchars($row['oc_sal_vigencia'] ?? '') . '"
                                              title="Editar Configuracion Vacuna Sal">
                                              <i class="fas fa-edit"></i>
                                          </button>';
                              echo '        <button class="btn btn-danger btn-sm delete-sal" 
                                              data-id="' . htmlspecialchars($row['id'] ?? '') . '"
                                              title="Eliminar Configuracion Vacuna Sal">
                                              <i class="fas fa-trash"></i>
                                          </button>';
                              echo '    </div>';
                              echo '</td>';
                              
                              // Column 1: Vacuna
                              echo "<td>" . htmlspecialchars($row['oc_sal_nombre'] ?? '') . "</td>";
                              // Columna 2: Etapa
                              echo "<td>" . htmlspecialchars($row['oc_sal_etapa'] ?? '') . "</td>";
                              
                              // Column 3: Dosis
                              echo "<td>" . htmlspecialchars($row['oc_sal_racion'] ?? 'N/A') . "</td>";
                              
                              // Column 4: Costo
                              echo "<td>" . htmlspecialchars($row['oc_sal_costo'] ?? 'N/A') . "</td>";
                              
                              // Column 5: Vigencia
                              echo "<td>" . htmlspecialchars($row['oc_sal_vigencia'] ?? 'N/A') . "</td>";

                              echo "</tr>";
                          }
                      }
                  ?>
              </tbody>
          </table>
      </div>
</div>


<!-- Initialize DataTable for VH sal -->
<script>
$(document).ready(function() {
    // Check if table has actual data rows (excluding the "no data" message row)
    var table = $('#salTable');
    var tbody = table.find('tbody');
    var hasData = false;
    
    // Check if there are any rows with actual data (not the "No hay registros disponibles" row)
    tbody.find('tr').each(function() {
        var row = $(this);
        var firstCell = row.find('td:first');
        // If the first cell doesn't contain "No hay registros disponibles", it's a data row
        if (firstCell.text().trim() !== 'No hay registros disponibles') {
            hasData = true;
            return false; // Break the loop
        }
    });
    
    if (hasData) {
        // Initialize DataTable only if there's actual data
        try {
            $('#salTable').DataTable({
                // Set initial page length
                pageLength: 5,
                
                // Configure length menu options
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "Todos"]
                ],
                
                // Order by Vigencia column descending (column index 5)
                order: [[5, 'desc']],
                
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
                
                // Column specific settings - Updated indices
                columnDefs: [
                     {
                         targets: [0], // Actions column
                         orderable: false,
                         searchable: false
                     },
                    {
                        targets: [3, 4], // Dosis, Costo columns
                        render: function(data, type, row) {
                            if (type === 'display' && data !== 'N/A' && data !== 'No Registrado') {
                                // Attempt to parse only if data looks like a number
                                 const num = parseFloat(data);
                                 if (!isNaN(num)) {
                                     return num.toLocaleString('es-ES', {
                                         minimumFractionDigits: 2,
                                         maximumFractionDigits: 2
                                     });
                                 }
                            }
                            return data; // Return original data if not display or not a valid number
                        }
                    },
                    {
                        targets: [5], // Vigencia column
                        orderable: true,
                        searchable: true
                    }
                ]
            });
        } catch (error) {
            console.warn('DataTable initialization failed:', error);
            // Fallback to basic styling
            table.addClass('table-striped table-bordered');
        }
    } else {
        // If no data, just add basic styling without DataTable
        table.addClass('table-striped table-bordered');
        console.log('No data found, DataTable not initialized');
    }
});
</script>

<!-- JavaScript for Edit and Delete buttons -->
<script>
$(document).ready(function() {
    // --- Initialize Modals Once --- 
    var newEntryModalElement = document.getElementById('newEntryModal');
    var newEntryModalInstance = new bootstrap.Modal(newEntryModalElement); 
    // Note: editSalModal is created dynamically later, so no need to initialize here.

    // Handle new entry form submission
    $('#saveNewSal').click(function() {
        // Validate the form
        var form = document.getElementById('newSalForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get form data
        var formData = {
            sal: $('#new_sal').val(),
            etapa: $('#new_etapa').val(),
            racion: $('#new_racion').val(),
            costo: $('#new_costo').val(),
            vigencia: $('#new_vigencia').val()
        };
        
        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: '¿Confirmar registro?',
            text: `¿Desea registrar el alimento ${formData.sal} con ración de ${formData.racion} kg?`,
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
                    url: 'process_configuracion_sal.php',
                    type: 'POST',
                    data: {
                        action: 'insert',
                        sal: formData.sal,
                        etapa: formData.etapa,
                        racion: formData.racion,
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
                            text: 'El registro de sal ha sido guardado correctamente',
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
                            sal: formData.sal,
                            etapa: formData.etapa,
                            racion: formData.racion,
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
    $('.edit-sal').click(function() {
        var id = $(this).data('id');
        var sal = $(this).data('sal');
        var etapa = $(this).data('etapa');
        var racion = $(this).data('racion');
        var costo = $(this).data('costo');
        var vigencia = $(this).data('vigencia');

        console.log('Edit button clicked. Record ID captured:', id); // Debug log 1
        
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

        // Edit Configuracion Sal Modal dialog for editing
        var modalHtml = `
        <div class="modal fade" id="editSalModal" tabindex="-1" aria-labelledby="editSalModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSalModalLabel">
                            <i class="fas fa-weight me-2"></i>Editar Sal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editSalForm">
                            <input type="hidden" id="edit_id" name="id" value="${id}">
                            
                            <div class="mb-4">
                                <label for="edit_sal" class="form-label fw-bold">
                                    <i class="fas fa-syringe me-2"></i>Sal
                                </label>
                                <select class="form-select" id="edit_sal" name="sal" required>
                                    <option value="">Seleccionar sal</option>
                                    <?php
                                    // Fetch distinct names from the database
                                    $sql_names = "SELECT DISTINCT oc_sal_nombre FROM oc_sal ORDER BY oc_sal_nombre ASC";
                                    $stmt_names = $conn->prepare($sql_names);
                                    $stmt_names->execute();
                                    $names = $stmt_names->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($names as $name_row) {
                                        echo '<option value="' . htmlspecialchars($name_row['oc_sal_nombre']) . '">' . htmlspecialchars($name_row['oc_sal_nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="edit_etapa" class="form-label fw-bold">
                                    <i class="fas fa-syringe me-2"></i>Etapa
                                </label>
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
                            
                            <div class="mb-4">
                                <label for="edit_racion" class="form-label fw-bold">
                                    <i class="fa-solid fa-eye-dropper me-2"></i>Ración (Kg)
                                </label>
                                <input type="number" step="0.01" class="form-control" id="edit_racion" name="racion" value="${racion}" placeholder="0.00" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="edit_costo" class="form-label fw-bold">
                                    <i class="fas fa-dollar-sign me-2"></i>Costo ($)
                                </label>
                                <input type="number" step="0.01" class="form-control" id="edit_costo" name="costo" value="${costo}" placeholder="0.00" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="edit_vigencia" class="form-label fw-bold">
                                    <i class="fas fa-calendar-days me-2"></i>Vigencia (días)
                                </label>
                                <input type="number" class="form-control" id="edit_vigencia" name="vigencia" value="${vigencia}" placeholder="0" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer btn-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="saveEditSal">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Remove any existing modal
        $('#editSalModal').remove();
        
        // Add the modal to the page
        $('body').append(modalHtml);
        
        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('editSalModal'));
        editModal.show();
        
        // Set the selected value for the etapa field after the modal is shown
        setTimeout(function() {
            $('#edit_etapa').val(etapa);
            $('#edit_sal').val(sal);
        }, 100);
        
        // Handle save button click
        $('#saveEditSal').click(function() {
            // Create a form object to properly validate
            var form = document.getElementById('editSalForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            var formData = {
                id: $('#edit_id').val(),
                sal: $('#edit_sal').val(),
                etapa: $('#edit_etapa').val(),
                racion: $('#edit_racion').val(),
                costo: $('#edit_costo').val(),
                vigencia: $('#edit_vigencia').val()
            };
            
            console.log('Save changes clicked. Form Data being sent:', formData); // Debug log 2
            
            // Show confirmation dialog
            Swal.fire({
                title: '¿Guardar cambios?',
                text: `¿Desea actualizar la configuracion de sal?`,
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
                        url: 'process_configuracion_sal.php',
                        type: 'POST',
                        data: {
                            action: 'update',
                            id: formData.id,
                            sal: formData.sal,
                            etapa: formData.etapa,
                            racion: formData.racion,
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
                                sal: formData.sal,
                                etapa: formData.etapa,
                                racion: formData.racion,
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
    $('.delete-sal').click(function() {
        var id = $(this).data('id');
        
        // Confirm before deleting using SweetAlert2
        Swal.fire({
            title: '¿Eliminar registro?',
            text: `¿Está seguro de que desea eliminar la configuracion de sal? Esta acción no se puede deshacer.`,
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
                    url: 'process_configuracion_sal.php',
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
    $(document).on('click', '.register-new-sal-btn', function() { 
        // Get tagid from the button's data-tagid-prefill attribute
        var tagid = $(this).data('tagid-prefill'); 
        
        // Clear previous data in the modal
        $('#newSalForm')[0].reset();
        $('#new_id').val(''); // Ensure ID is cleared
        
      
        
        // Show the new entry modal using the existing instance
        newEntryModalInstance.show(); 
    });
});
</script>
</body>
</html>