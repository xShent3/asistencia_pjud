<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/admt_asistencia_registros.php';
verificarAcceso([1]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Asistencia - Rango de Fechas - Admin Total</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="../../modelo/estilos.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <header class="d-flex justify-content-between align-items-center p-3">
    <a href="../pagina_login.php">Cerrar Sesión</a>
    <div class="dropdown">
        <a href="#" class="text-white fw-bold dropdown-toggle" data-bs-toggle="dropdown">Reportes</a>
        <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../adm_tribunal/horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_mensual.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_registros.php">Asistencia Total de Registros</a></li>
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
    <h2 class="mb-4">
        Reporte de Asistencia (Registros Completos) <br>
        <small>
          Rango: <?= date('d/m/Y', strtotime($fecha_inicio)) ?> a <?= date('d/m/Y', strtotime($fecha_fin)) ?> -
          Tribunal: 
          <?php

            foreach ($tribunales as $t) {
              if ($t['id_tribunal'] == $selected_tribunal) {
                echo $t['nombre_tribunal'];
                break;
              }
            }
          ?>
        </small>
    </h2>
    
    
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-4">
          <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
          <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>" required>
      </div>
      <div class="col-md-4">
          <label for="fecha_fin" class="form-label">Fecha Fin</label>
          <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= $fecha_fin ?>" required>
      </div>
      <div class="col-md-4">
          <label for="tribunal" class="form-label">Tribunal / Corporación</label>
          <select name="tribunal" id="tribunal" class="form-select" required>
            <?php foreach ($tribunales as $t): 
                  $sel = ($t['id_tribunal'] == $selected_tribunal) ? 'selected' : ''; ?>
                <option value="<?= $t['id_tribunal'] ?>" <?= $sel ?>>
                  <?= $t['nombre_tribunal'] ?>
                </option>
            <?php endforeach; ?>
          </select>
      </div>
      <div class="col-md-12 text-center">
          <button type="submit" class="btn btn-primary">Actualizar Reporte</button>
      </div>
    </form>
    
    
    <div class="d-flex justify-content-end mb-3">
      <a href="../adm_tribunal/export_excel_asistencia_registros.php?fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>&tribunal=<?= $selected_tribunal ?>&t=<?= time() ?>" class="btn btn-success">
           Excel
      </a>
    </div>
    
    
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
          <thead class="table-dark">
              <tr>
                  <th>Fecha</th>
                  <th>RUT</th>
                  <th>Nombre</th>
                  <th>Hora de Entrada</th>
                  <th>Hora de Salida </th>
                  <th>Tiempo Excedido</th>
                  <th>Autorización</th>
                  <th>Teletrabajo</th>
                  <th>Modificado por</th>
              </tr>
          </thead>
          <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                  <td><?= date('d/m/Y', strtotime($row['dia'])) ?></td>
                  <td><?= $row['RUT'] ?></td>
                  <td><?= htmlspecialchars($row['nombre_completo']) ?></td>
                  <td><?= $row['hora_inicio'] ? date('H:i:s', strtotime($row['hora_inicio'])) : '' ?></td>
                  <td><?= $row['hora_fin'] ? date('H:i:s', strtotime($row['hora_fin'])) : '' ?></td>
                  <td><?= $row['tiempo_excedido'] ?: '00:00:00' ?></td>
                  <td><?= $row['auth'] == 1 ? 'Sí' : 'No' ?></td>
                  <td><?= $row['teletrabajo'] == 1 ? 'Sí' : 'No' ?></td>
                  <td><?= !empty($row['mod_por']) ? $row['mod_por'] : '-' ?></td>
              </tr>
              <?php endwhile; ?>
              <?php if ($result->num_rows === 0): ?>
                <tr>
                  <td colspan="10" class="text-center">No existen registros en el rango seleccionado</td>
                </tr>
              <?php endif; ?>
          </tbody>
      </table>
    </div>
  </div>
  
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
