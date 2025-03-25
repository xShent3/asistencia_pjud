<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/admt_asistencia_semanal.php';
verificarAcceso([1]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asistencia General por Semana - Admin Total</title>
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
              <li><a class="dropdown-item" href="../adm_tribunal/horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_semanal.php">Asistencia General por Semana</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_mensual.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_fecha.php">Asistencia General Rango</a></li>
        </ul>
    </div>
    <div>
    <a href="crear_usuarios.php">Crear Usuario</a>
        <a href="../adm_tribunal/buscar_usuario.php">Buscar Usuario</a>
        <a href="../adm_tribunal/ver_usuarios.php">Inicio</a>
        <a href="../cambio_contrasena.php">Cambio de contraseña</a>
    </div>
</header>
  <div class="container mt-4">
    <h2 class="mb-4">Reporte Semanal de Asistencia</h2>
    <form method="POST" class="mb-4">
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label for="tribunal" class="form-label fw-bold">Seleccionar Tribunal:</label>
          <select name="tribunal" id="tribunal" class="form-select">
            <?php 
            $result_tribunales->data_seek(0);
            while ($row = $result_tribunales->fetch_assoc()):
            ?>
            <option value="<?= $row['id_tribunal'] ?>" <?= $row['id_tribunal'] == $selected_tribunal ? 'selected' : '' ?>>
              <?= $row['nombre_tribunal'] ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label for="start_year" class="form-label fw-bold">Año Inicio:</label>
          <select name="start_year" id="start_year" class="form-select">
            <?php 
            $result_years->data_seek(0);
            while ($row = $result_years->fetch_assoc()):
            ?>
            <option value="<?= $row['anio'] ?>" <?= $row['anio'] == $startYear ? 'selected' : '' ?>>
              <?= $row['anio'] ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="start_month" class="form-label fw-bold">Mes Inicio:</label>
          <select name="start_month" id="start_month" class="form-select">
            <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= $m == $startMonth ? 'selected' : '' ?>>
              <?= $meses_es[$m] ?>
            </option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="end_year" class="form-label fw-bold">Año Fin:</label>
          <select name="end_year" id="end_year" class="form-select">
            <?php 
            $result_years->data_seek(0);
            while ($row = $result_years->fetch_assoc()):
            ?>
            <option value="<?= $row['anio'] ?>" <?= $row['anio'] == $endYear ? 'selected' : '' ?>>
              <?= $row['anio'] ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="end_month" class="form-label fw-bold">Mes Fin:</label>
          <select name="end_month" id="end_month" class="form-select">
            <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= $m == $endMonth ? 'selected' : '' ?>>
              <?= $meses_es[$m] ?>
            </option>
            <?php endfor; ?>
          </select>
        </div>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Semana</th>
            <th>% Cumplimiento</th>
            <th>% Inasistencia Horaria</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reporte as $semana): ?>
          <tr>
            <td>
              <a href="asistencia_semanal_detalle.php?inicio=<?= $semana['inicio'] ?>&tribunal=<?= $selected_tribunal ?>">
                <?= $semana['rango'] ?>
              </a>
            </td>
            <td>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?= $semana['porcentaje_cumplimiento'] ?>%" aria-valuenow="<?= $semana['porcentaje_cumplimiento'] ?>" aria-valuemin="0" aria-valuemax="100">
                  <?= $semana['porcentaje_cumplimiento'] ?>%
                </div>
              </div>
            </td>
            <td>
              <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $semana['porcentaje_inasistencia'] ?>%" aria-valuenow="<?= $semana['porcentaje_inasistencia'] ?>" aria-valuemin="0" aria-valuemax="100">
                  <?= $semana['porcentaje_inasistencia'] ?>%
                </div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
<?php
$conn->close();
?>
