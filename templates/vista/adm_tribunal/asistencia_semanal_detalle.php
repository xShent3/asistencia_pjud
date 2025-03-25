<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/asistencia_detalle_semanal_datos.php';
verificarAcceso([1,2,3]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle Semanal de Asistencia</title>
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
    </div>
</header>
<div class="container mt-4">
  <h2 class="mb-4">Detalle Semanal de Asistencia</h2>
  <p>Semana: <?= $inicio_semana->format('d/m/Y') ?> - <?= $fin_semana->format('d/m/Y') ?></p>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Fecha</th>
          <th>Tiempo Total de Tardanza</th>
          <th>Incumplieron Horario</th>
          <th>Cumplieron Horario</th>
          <th>Ausentes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($datos as $fecha => $valores): ?>
        <tr>
          <td>
            <a href="asistencia_diaria.php?fecha=<?= $fecha ?>">
              <?= DateTime::createFromFormat('Y-m-d', $fecha)->format('d/m/Y') ?>
            </a>
          </td>
          <td><?= $valores['total_tardanza'] ?: '00:00:00' ?></td>
          <td><?= $valores['incumplieron'] ?></td>
          <td><?= $valores['cumplieron'] ?></td>
          <td><?= $valores['ausentes'] ?></td>
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
