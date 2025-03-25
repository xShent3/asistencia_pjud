<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/asistencia_generalsemana_datos.php';
verificarAcceso([2]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asistencia General por Semana</title>
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
            <li><a class="dropdown-item" href="asistencia_general_semana.php">Asistencia General por Semana</a></li>
            <li><a class="dropdown-item" href="asistencia_general_mes.php">Asistencia General Mensual</a></li>
            <li><a class="dropdown-item" href="asistencia_general_fecha.php">Asistencia General Rango</a></li>
            <li><a class="dropdown-item" href="asistencia_registros.php">Asistencia Total de Registros</a></li>
          </ul>
      </div> 
      <div>
        <?php if ($_SESSION['id_rol'] == 1) : ?>
          <a href="../adm_total/crear_usuarios.php">Crear Usuario</a>
        <?php elseif ($_SESSION['id_rol'] == 2) : ?>
          <a href="formulario_usuario.php">Crear Usuario</a>
        <?php elseif ($_SESSION['id_rol'] == 3) : ?>
          <a href="formulario_usuario.php">Crear Usuario</a>
        <?php endif; ?>
          <a href="buscar_usuario.php">Buscar Usuario</a> 
          <a href="ver_usuarios.php">Inicio</a>
          <a href="../cambio_contrasena.php">Cambio de contraseña</a>
      </div>
</header>
  <div class="container mt-4">
    <h2 class="mb-4">Reporte Semanal de Asistencia</h2>
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-3">
        <select name="start_year" class="form-select">
          <?php while($row = $result_periodos->fetch_assoc()): ?>
            <option value="<?= $row['anio'] ?>" <?= $row['anio'] == $startYear ? 'selected' : '' ?>>
              <?= $row['anio'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="start_month" class="form-select">
          <?php for($m=1; $m<=12; $m++): ?>
            <option value="<?= $m ?>" <?= $m == $startMonth ? 'selected' : '' ?>>
              <?= DateTime::createFromFormat('!m', $m)->format('F') ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="end_year" class="form-select">
          <?php 
            $result_periodos->data_seek(0);
            while($row = $result_periodos->fetch_assoc()): ?>
            <option value="<?= $row['anio'] ?>" <?= $row['anio'] == $endYear ? 'selected' : '' ?>>
              <?= $row['anio'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="end_month" class="form-select">
          <?php for($m=1; $m<=12; $m++): ?>
            <option value="<?= $m ?>" <?= $m == $endMonth ? 'selected' : '' ?>>
              <?= DateTime::createFromFormat('!m', $m)->format('F') ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-md-12 text-center">
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
                <a href="asistencia_semanal_detalle.php?inicio=<?= $semana['inicio'] ?>">
                  <?= $semana['rango'] ?>
                </a>
              </td>
              <td>
                <div class="progress">
                  <div class="progress-bar" role="progressbar" 
                       style="width: <?= $semana['porcentaje_cumplimiento'] ?>%" 
                       aria-valuenow="<?= $semana['porcentaje_cumplimiento'] ?>" 
                       aria-valuemin="0" aria-valuemax="100">
                    <?= $semana['porcentaje_cumplimiento'] ?>%
                  </div>
                </div>
              </td>
              <td>
                <div class="progress">
                  <div class="progress-bar bg-danger" role="progressbar" 
                       style="width: <?= $semana['porcentaje_inasistencia'] ?>%" 
                       aria-valuenow="<?= $semana['porcentaje_inasistencia'] ?>" 
                       aria-valuemin="0" aria-valuemax="100">
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
