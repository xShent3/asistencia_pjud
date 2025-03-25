<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/asistencia_fecha_datos.php';
include '../../controlador/verificacion.php';
verificarAcceso([1,2,3]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asistencia General por Día - Rango de Fechas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="../../modelo/estilos.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header class="d-flex justify-content-between align-items-center">
  <a href="../pagina_login.php">Cerrar Sesión</a>
  <div class="dropdown">
    <a href="#" class="text-white fw-bold dropdown-toggle" data-bs-toggle="dropdown">Reportes</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
      <li><a class="dropdown-item" href="asistencia_general_dia.php">Asistencia General por Día</a></li>
      <li><a class="dropdown-item" href="asistencia_general_mes.php">Asistencia General Mensual</a></li>
      <li><a class="dropdown-item" href="asistencia_general_fecha.php">Asistencia General Rango</a></li>
      <li><a class="dropdown-item" href="asistencia_registros.php">Asistencia Total de Registros</a></li>
    </ul>
  </div> 
  <div>
    <?php if ($_SESSION['id_rol'] == 1) : ?>
      <a href="../adm_total/crear_usuarios.php">Crear Usuario</a>
    <?php else: ?>
      <a href="formulario_usuario.php">Crear Usuario</a>
    <?php endif; ?>
    <a href="buscar_usuario.php">Buscar Usuario</a> 
    <a href="ver_usuarios.php">Inicio</a>
    <a href="../cambio_contrasena.php">Cambio de contraseña</a>
  </div>
</header>
<div class="container mt-4">
  <h2 class="mb-4">
    Asistencia Diaria por rango de fechas <br> - Dotación: <?= $total_usuarios ?> <br>- Total de Tardanza del Rango: <?= $total_tardanza_range ?>
  </h2>
  <form method="POST" class="row g-3 mb-4">
    <div class="col-md-6">
      <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
      <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
    </div>
    <div class="col-md-6">
      <label for="fecha_fin" class="form-label">Fecha Fin</label>
      <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
    </div>
    <div class="col-md-12 text-center">
      <button type="submit" class="btn btn-primary">Actualizar Reporte</button>
    </div>
  </form>

<div class="d-flex justify-content-end mb-3">
  <a href="export_excel_asistencia_fecha.php?fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>&t=<?= time() ?>" class="btn btn-success">
    Excel
  </a>
</div>

  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Retraso</th>
          <th>N° de retrasos</th>
          <th>Inasistencia total</th>
          <th>Asistencia total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($datos as $fecha => $valores): ?>
          <?php 
            $nombresIncumplieron = $valores['lista_incumplieron'];
            $nombresCumplieron = $valores['lista_cumplieron'];
            $nombresAusentes = $valores['lista_ausentes'];
            $nombresAsistieron = array_merge($nombresIncumplieron, $nombresCumplieron);
            
            $tooltipIncumplieron = count($nombresIncumplieron) ? implode(", ", $nombresIncumplieron) : "Ninguno";
            $tooltipCumplieron = count($nombresCumplieron) ? implode(", ", $nombresCumplieron) : "Ninguno";
            $tooltipAusentes = count($nombresAusentes) ? implode(", ", $nombresAusentes) : "Ninguno";
            $tooltipAsistieron = count($nombresAsistieron) ? implode(", ", $nombresAsistieron) : "Ninguno";
          ?>
          <tr <?= $valores['es_fin_de_semana'] ? 'style="background-color: rgba(255, 0, 0, 0.2);"' : '' ?>>
            <td>
              <a href="asistencia_diaria.php?fecha=<?= $fecha ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>" target="_blank">
                <?= DateTime::createFromFormat('Y-m-d', $fecha)->format('d/m/Y') ?>
              </a>
            </td>
            <td><?= $valores['total_tardanza'] ?></td>
            <td data-bs-toggle="tooltip" data-bs-placement="top" title="<?= htmlspecialchars($tooltipIncumplieron) ?>">
              <?= $valores['incumplieron'] ?>
            </td>
            <td data-bs-toggle="tooltip" data-bs-placement="top" title="<?= htmlspecialchars($tooltipCumplieron) ?>">
              <?= $valores['cumplieron'] ?>
            </td>
            <td data-bs-toggle="tooltip" data-bs-placement="top" title="<?= htmlspecialchars($tooltipAusentes) ?>">
              <?= $valores['ausentes'] ?>
            </td>
            <td data-bs-toggle="tooltip" data-bs-placement="top" title="<?= htmlspecialchars($tooltipAsistieron) ?>">
              <?= count($nombresAsistieron) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>
</body>
</html>
<?php
$conn->close();
?>
