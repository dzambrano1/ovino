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
<title>Ovino Configuracion Garrapatas</title>
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

<!-- Professional Sanitary Plan Table Styling -->
<style>
.sanitary-plan-container {
    margin: 2rem 0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 15px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.sanitary-plan-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}

.sanitary-plan-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.sanitary-plan-title {
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

.sanitary-plan-body {
    background: #ffffff;
    padding: 0;
}

.sanitary-plan-table {
    margin: 0;
    border: none;
    font-size: 0.95rem;
    line-height: 1.6;
}

.sanitary-plan-table thead th {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: #ffffff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border: none;
    font-size: 0.85rem;
    position: relative;
}

.sanitary-plan-table thead th:first-child {
    border-top-left-radius: 0;
}

.sanitary-plan-table thead th:last-child {
    border-top-right-radius: 0;
}

.sanitary-plan-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.sanitary-plan-table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.sanitary-plan-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.sanitary-plan-table tbody tr:nth-child(even):hover {
    background: linear-gradient(135deg, #f0f8ff 0%, #e1f5fe 100%);
}

.sanitary-plan-table tbody td {
    padding: 0.875rem 0.75rem;
    border: none;
    vertical-align: middle;
    position: relative;
}

.sanitary-plan-table tbody td:first-child {
    border-left: 4px solid transparent;
    transition: border-left-color 0.3s ease;
}

.sanitary-plan-table tbody tr:hover td:first-child {
    border-left-color: #667eea;
}

.sanitary-plan-table tbody td strong {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1rem;
}

.sanitary-plan-table tbody td:nth-child(2) {
    font-weight: 500;
    color: #495057;
}

.sanitary-plan-table tbody td:nth-child(3),
.sanitary-plan-table tbody td:nth-child(4) {
    font-weight: 600;
    color: #28a745;
    text-align: center;
}

.sanitary-plan-table tbody td:nth-child(4) {
    color: #17a2b8;
}

.sanitary-plan-recommendations {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border: none;
    border-radius: 10px;
    margin-top: 1.5rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.sanitary-plan-recommendations h5 {
    color: #1565c0;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sanitary-plan-recommendations ul {
    margin: 0;
    padding-left: 1.25rem;
}

.sanitary-plan-recommendations li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
    color: #37474f;
}

.sanitary-plan-recommendations li strong {
    color: #1565c0;
    font-weight: 600;
}

.sanitary-plan-recommendations li:last-child {
    margin-bottom: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .sanitary-plan-table {
        font-size: 0.85rem;
    }
    
    .sanitary-plan-table thead th,
    .sanitary-plan-table tbody td {
        padding: 0.5rem 0.4rem;
    }
    
    .sanitary-plan-title {
        font-size: 1.2rem;
    }
    
    .sanitary-plan-recommendations {
        padding: 1rem;
    }
}

/* Print styles */
@media print {
    .sanitary-plan-container {
        box-shadow: none;
        border: 2px solid #2c3e50;
    }
    
    .sanitary-plan-table tbody tr:hover {
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

<!-- Sanitary Plan Section -->
<div class="container mt-4">
    <div class="sanitary-plan-container">
        <div class="sanitary-plan-header">
            <h4 class="sanitary-plan-title">
                <i class="fas fa-shield-virus"></i>
                <span>🛡️ Plan de Vacunación y Tratamientos Antiparasitarios – Ovinos Venezuela 2025</span>
            </h4>
        </div>
        <div class="sanitary-plan-body">
            <div class="table-responsive">
                <table class="sanitary-plan-table table table-hover">
                    <thead>
                        <tr>
                            <th>Etapa / Edad</th>
                            <th>Vacuna / Tratamiento</th>
                            <th>Dosis</th>
                            <th>Vía</th>
                            <th>Observaciones clave</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Nacimiento (0–7 días)</strong></td>
                            <td>Ninguna vacuna / Antiparasitario externo (si necesario)</td>
                            <td>Según producto</td>
                            <td>Tópica</td>
                            <td>Solo si hay infestación visible. Evitar estrés. No aplicar ivermectina aún.</td>
                        </tr>
                        <tr>
                            <td><strong>Corderos (4–6 semanas)</strong></td>
                            <td>Clostridiosis (C y D) + Tétanos (Pulpyvax®, Covexin®)</td>
                            <td>2 mL</td>
                            <td>Subcutánea</td>
                            <td>Primera dosis. Refuerzo a las 2–4 semanas. Aplicar en cuello.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Antiparasitario interno (albendazol 10%)</td>
                            <td>5–7 mg/kg</td>
                            <td>Oral</td>
                            <td>Solo si hay evidencia de carga parasitaria. Evaluar con coprología.</td>
                        </tr>
                        <tr>
                            <td><strong>Corderos (8–10 semanas)</strong></td>
                            <td>Refuerzo Clostridiosis + Tétanos</td>
                            <td>2 mL</td>
                            <td>Subcutánea</td>
                            <td>Consolidación inmunológica. Registrar lote y fecha.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Antiparasitario externo (cypermethrina 1%)</td>
                            <td>Según etiqueta</td>
                            <td>Tópica</td>
                            <td>Aplicar en línea dorsal. Repetir cada 30 días si hay infestación.</td>
                        </tr>
                        <tr>
                            <td><strong>Desarrollo (3–5 meses)</strong></td>
                            <td>Pasteurella spp. (si disponible)</td>
                            <td>2 mL</td>
                            <td>Subcutánea</td>
                            <td>En sistemas intensivos o zonas húmedas. Refuerzo anual.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Ivermectina 1%</td>
                            <td>0.2 mg/kg</td>
                            <td>Subcutánea</td>
                            <td>Control de nematodos y ectoparásitos. Aplicar cada 90 días según carga parasitaria.</td>
                        </tr>
                        <tr>
                            <td><strong>Adultos (≥6 meses)</strong></td>
                            <td>Fiebre Aftosa (según campaña INSAI)</td>
                            <td>2 mL</td>
                            <td>Subcutánea</td>
                            <td>Obligatoria en zonas designadas. Registrar en módulo oficial.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Ectima contagioso (si endémico)</td>
                            <td>1 mL</td>
                            <td>Intradérmica</td>
                            <td>Vacuna viva. Aplicar en zona sin lana. Evitar autoinoculación.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Albendazol / Levamisol / Ivermectina</td>
                            <td>Según producto</td>
                            <td>Oral / Subcutánea</td>
                            <td>Rotar moléculas cada 6 meses. Evaluar resistencia.</td>
                        </tr>
                        <tr>
                            <td><strong>Gestantes (último mes)</strong></td>
                            <td>Refuerzo Clostridiosis + Tétanos</td>
                            <td>2 mL</td>
                            <td>Subcutánea</td>
                            <td>Protege madre y cordero vía calostro. Aplicar 4 semanas antes del parto.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Antiparasitario interno (seguro en gestación)</td>
                            <td>Según etiqueta</td>
                            <td>Oral</td>
                            <td>Usar solo productos aprobados para gestantes. Consultar veterinario.</td>
                        </tr>
                        <tr>
                            <td><strong>Sementales</strong></td>
                            <td>Brucelosis ovina (si zona de control)</td>
                            <td>2 mL</td>
                            <td>Subcutánea</td>
                            <td>Solo en zonas con diagnóstico positivo. Requiere prueba previa.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Ivermectina / Doramectina</td>
                            <td>0.2 mg/kg</td>
                            <td>Subcutánea</td>
                            <td>Control estratégico. Mantener condición corporal óptima.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Recommendations Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="sanitary-plan-recommendations">
                        <h5><i class="fas fa-lightbulb"></i>📌 Recomendaciones para trazabilidad digital</h5>
                        <ul class="mb-0">
                            <li><strong>Campos por evento:</strong> especie, lote, edad, producto, dosis, vía, fecha, responsable, respuesta clínica.</li>
                            <li><strong>Alertas automáticas:</strong> por edad, etapa productiva, zona epidemiológica, historial de aplicación.</li>
                            <li><strong>Rotación antiparasitaria:</strong> lógica de alternancia por molécula, con historial y resistencia estimada.</li>
                            <li><strong>Integración:</strong> módulos de salud, eficiencia, bioseguridad, cierre técnico, planificación estratégica.</li>
                        </ul>
                    </div>
                </div>
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
  CONFIGURACION VACUNAS GARRAPATAS
  </h3>
</div> 
<!-- New Entry Modal Configuracion Garrapatas -->

<!-- Add New Vacuna Garrapatas Button -->
<div class="container my-3 text-center">
  <button type="button" class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#newEntryModal">
    <i class="fas fa-plus-circle me-2"></i>Nueva Vacuna Garrapatas
  </button>
</div>

<div class="modal fade" id="newEntryModal" tabindex="-1" aria-labelledby="newEntryModalLabel">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="newEntryModalLabel">
                  <i class="fas fa-plus-circle me-2"></i>Configurar Nueva Vacuna Garrapatas
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="newGarrapatasForm">
              <input type="hidden" id="new_id" name="id" value="">
                  <div class="mb-4">                        
                      <div class="input-group">
                          <span class="input-group-text">
                              <i class="fa-solid fa-syringe"></i>
                              <label for="new_vacuna" class="form-label">Vacuna</label>
                              <input type="text" class="form-control" id="new_vacuna" name="vacuna" required>
                          </span>                            
                      </div>
                  </div>
                  <div class="mb-4">                        
                      <div class="input-group">
                          <span class="input-group-text">
                              <i class="fa-solid fa-eye-dropper"></i>
                              <label for="new_dosis" class="form-label">Dosis (ml)</label>
                              <input type="number" step="0.01" class="form-control" id="new_dosis" name="dosis" required>
                          </span>
                      </div>
                  </div>
                  <div class="mb-4">                        
                      <div class="input-group">
                          <span class="input-group-text">
                              <i class="fa-solid fa-money-bill-1-wave"></i>
                              <label for="new_costo" class="form-label">Costo ($)</label>
                              <input type="number" step="0.01" class="form-control" id="new_costo" name="costo" required>
                          </span>                            
                      </div>
                  </div>
                  <div class="mb-4">                        
                      <div class="input-group">
                          <span class="input-group-text">
                              <i class="fa-solid fa-calendar-days"></i>
                              <label for="new_vigencia" class="form-label">Vigencia (dias)</label>
                              <input type="number" class="form-control" id="new_vigencia" name="vigencia" required>
                          </span>
                      </div>
                  </div>
              </form>
          </div>
          <div class="modal-footer btn-group">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="fas fa-times me-1"></i>Cancelar
              </button>
              <button type="button" class="btn btn-success" id="saveNewGarrapatas">
                  <i class="fas fa-save me-1"></i>Guardar
              </button>
          </div>
      </div>
  </div>
</div>
  
  <!-- DataTable for oh_garrapatas records -->
  
<div class="container table-section" style="display: block;">
      <div class="table-responsive">
          <table id="garrapatasTable" class="table table-striped table-bordered">
              <thead>
                  <tr>
                    <th class="text-center">Acciones</th>
                    <th class="text-center">Vacuna</th>
                    <th class="text-center">Dosis (ml)</th>
                    <th class="text-center">Costo ($)</th>
                    <th class="text-center">Vigencia (dias)</th>                                 
                  </tr>
              </thead>
              <tbody>
                  <?php
                      $garrapatasQuery = "SELECT * FROM oc_garrapatas";

                      $stmt = $conn->prepare($garrapatasQuery);
                      $stmt->execute();
                      $garrapatassData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      if (empty($garrapatassData)) {
                          echo "<tr><td colspan='5' class='text-center'>No hay registros disponibles</td></tr>";
                      } else {
                          foreach ($garrapatassData as $row) {
                              echo "<tr>";
                              
                              // Column 0: Actions
                              echo '<td class="text-center">';
                              echo '    <div class="btn-group" role="group">';
                              echo '        <button class="btn btn-warning btn-sm edit-garrapatas" 
                                              data-id="' . htmlspecialchars($row['id'] ?? '') . '" 
                                              data-vacuna="' . htmlspecialchars($row['oc_garrapatas_vacuna'] ?? '') . '" 
                                              data-dosis="' . htmlspecialchars($row['oc_garrapatas_dosis'] ?? '') . '" 
                                              data-costo="' . htmlspecialchars($row['oc_garrapatas_costo'] ?? '') . '" 
                                              data-vigencia="' . htmlspecialchars($row['oc_garrapatas_vigencia'] ?? '') . '"
                                              title="Editar Configuracion Vacuna Garrapatas">
                                              <i class="fas fa-edit"></i>
                                          </button>';
                              echo '        <button class="btn btn-danger btn-sm delete-garrapatas" 
                                              data-id="' . htmlspecialchars($row['id'] ?? '') . '"
                                              title="Eliminar Configuracion Vacuna Garrapatas">
                                              <i class="fas fa-trash"></i>
                                          </button>';
                              echo '    </div>';
                              echo '</td>';
                              
                              // Column 1: Vacuna
                              echo "<td>" . htmlspecialchars($row['oc_garrapatas_vacuna'] ?? '') . "</td>";
                              
                              // Column 2: Dosis
                              echo "<td>" . htmlspecialchars($row['oc_garrapatas_dosis'] ?? 'N/A') . "</td>";
                              
                              // Column 3: Costo
                              echo "<td>" . htmlspecialchars($row['oc_garrapatas_costo'] ?? 'N/A') . "</td>";
                              
                              // Column 4: Vigencia
                              echo "<td>" . htmlspecialchars($row['oc_garrapatas_vigencia'] ?? 'N/A') . "</td>";

                              echo "</tr>";
                          }
                      }
                  ?>
              </tbody>
          </table>
      </div>
</div>


<!-- Initialize DataTable for VH garrapatas -->
<script>
$(document).ready(function() {
    $('#garrapatasTable').DataTable({
        // Set initial page length
        pageLength: 5,
        
        // Configure length menu options
        lengthMenu: [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Todos"]
        ],
        
        // Order by Vigencia column descending (column index 4)
        order: [[4, 'desc']],
        
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
                targets: [2, 3], // Dosis, Costo columns
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
                targets: [4], // Vigencia column
                orderable: true,
                searchable: true
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
    // Note: editGarrapatasModal is created dynamically later, so no need to initialize here.

    // Handle new entry form submission
    $('#saveNewGarrapatas').click(function() {
        // Validate the form
        var form = document.getElementById('newGarrapatasForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get form data
        var formData = {
            vacuna: $('#new_vacuna').val(),
            dosis: $('#new_dosis').val(),
            costo: $('#new_costo').val(),
            vigencia: $('#new_vigencia').val()
        };
        
        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: '¿Confirmar registro?',
            text: `¿Desea registrar la dosis de garrapatas ${formData.dosis} ml ?`,
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
                    url: 'process_configuracion_garrapatas.php',
                    type: 'POST',
                    data: {
                        action: 'insert',
                        vacuna: formData.vacuna,
                        dosis: formData.dosis,
                        costo: formData.costo,
                        vigencia: formData.vigencia
                    },
                    success: function(response) {
                        // Close the modal
                        newEntryModalInstance.hide();
                        
                        // Show success message
                        Swal.fire({
                            title: '¡Registro exitoso!',
                            text: 'El registro de garrapatas ha sido guardado correctamente',
                            icon: 'success',
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            // Reload the page to show updated data
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        let errorMsg = 'Error al procesar la solicitud';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
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
    $('.edit-garrapatas').click(function() {
        var id = $(this).data('id');
        var vacuna = $(this).data('vacuna');
        var dosis = $(this).data('dosis');
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

        // Edit Configuracion Garrapatas Modal dialog for editing
        var modalHtml = `
        <div class="modal fade" id="editGarrapatasModal" tabindex="-1" aria-labelledby="editGarrapatasModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGarrapatasModalLabel">
                            <i class="fas fa-weight me-2"></i>Editar Vacuna Garrapatas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editGarrapatasForm">
                            <input type="hidden" id="edit_id" name="id" value="${id}">
                            <div class="mb-2">                                
                                    
                            <div class="mb-2">                            
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-syringe"></i>
                                        <label for="edit_vacuna" class="form-label">Vacuna</label>                                    
                                        <input type="text" class="form-control" id="edit_vacuna" value="${vacuna}" required>
                                    </span>                                    
                                </div>
                            </div>
                            <div class="mb-2">                                
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-eye-dropper"></i>
                                        <label for="edit_dosis" class="form-label">Dosis (ml)</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_dosis" value="${dosis}" required>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-2">                                
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                        <label for="edit_costo" class="form-label">Costo ($)</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_costo" value="${costo}" required>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-days"></i>
                                        <label for="edit_vigencia" class="form-label">Vigencia (dias)</label>
                                        <input type="number" class="form-control" id="edit_vigencia" value="${vigencia}" required>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer btn-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="saveEditGarrapatas">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Remove any existing modal
        $('#editGarrapatasModal').remove();
        
        // Add the modal to the page
        $('body').append(modalHtml);
        
        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('editGarrapatasModal'));
        editModal.show();
        
        // Handle save button click
        $('#saveEditGarrapatas').click(function() {
            var formData = {
                id: $('#edit_id').val(),
                vacuna: $('#edit_vacuna').val(),
                dosis: $('#edit_dosis').val(),
                costo: $('#edit_costo').val(),
                vigencia: $('#edit_vigencia').val()
            };
            
            console.log('Save changes clicked. Form Data being sent:', formData); // Debug log 2
            
            // Show confirmation dialog
            Swal.fire({
                title: '¿Guardar cambios?',
                text: `¿Desea actualizar la configuracion de garrapatas?`,
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
                        url: 'process_configuracion_garrapatas.php',
                        type: 'POST',
                        data: {
                            action: 'update',
                            id: formData.id,
                            vacuna: formData.vacuna,
                            dosis: formData.dosis,
                            costo: formData.costo,
                            vigencia: formData.vigencia
                        },
                        success: function(response) {
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
                            // Show error message
                            let errorMsg = 'Error al procesar la solicitud';
                            
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
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
    $('.delete-garrapatas').click(function() {
        var id = $(this).data('id');
        
        // Confirm before deleting using SweetAlert2
        Swal.fire({
            title: '¿Eliminar registro?',
            text: `¿Está seguro de que desea eliminar la configuracion de garrapatas? Esta acción no se puede deshacer.`,
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
                    url: 'process_configuracion_garrapatas.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
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
                        // Show error message
                        let errorMsg = 'Error al procesar la solicitud';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
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
    $(document).on('click', '.register-new-garrapatas-btn', function() { 
        // Get tagid from the button's data-tagid-prefill attribute
        var tagid = $(this).data('tagid-prefill'); 
        
        // Clear previous data in the modal
        $('#newGarrapatasForm')[0].reset();
        $('#new_id').val(''); // Ensure ID is cleared
        
      
        
        // Show the new entry modal using the existing instance
        newEntryModalInstance.show(); 
    });
});
</script>
</body>
</html>