<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/asistencia_generalmes_datos.php';
verificarAcceso([2,3]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asistencia General por Mes</title>
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
      <h2 class="mb-4">Reporte Mensual de Asistencia - <?= $selected_year ?></h2>
      
      <form method="POST" class="row g-3 mb-4">
          <div class="col-md-6 text-center">
              <select name="anio" class="form-select w-auto mx-auto">
                  <?php while($row = $result_years->fetch_assoc()): ?>
                      <option value="<?= $row['anio'] ?>" <?= $row['anio'] == $selected_year ? 'selected' : '' ?>>
                          <?= $row['anio'] ?>
                      </option>
                  <?php endwhile; ?>
              </select>
              <button type="submit" class="btn btn-primary mt-2">Generar Reporte</button>
          </div>
      </form>
      
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Mes</th>
                    <th>% Cumplimiento</th>
                    <th>Inasistencia Horaria</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($meses_con_registros as $m): 
                    $datos = $datos_mes[$m];
                ?>
                    <tr>
                        <td>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#modalMes<?= $m ?>">
                              <?= $meses_es[$m] ?>
                          </a>
                        </td>
                        <td>
                          <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: <?= $datos['porcentaje_cumplimiento'] ?>%;" 
                                 aria-valuenow="<?= $datos['porcentaje_cumplimiento'] ?>" 
                                 aria-valuemin="0" aria-valuemax="100">
                                <?= $datos['porcentaje_cumplimiento'] ?>%
                            </div>
                          </div>
                        </td>
                        <td>
                            <?= $datos['porcentaje_inasistencia'] ?>%
                            <small><?= number_format($datos['inasistencias_horas'], 2) ?> / <?= number_format($datos['total_horas_tribunal'], 2) ?> hrs</small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
      
      
      <?php foreach($meses_con_registros as $m): ?>
      <div class="modal fade" id="modalMes<?= $m ?>" tabindex="-1" aria-labelledby="modalMes<?= $m ?>Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalMes<?= $m ?>Label">Detalle de Incumplimientos - <?= $meses_es[$m] ?> <?= $selected_year ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <?php if(isset($detalles_mes[$m]) && count($detalles_mes[$m]) > 0): ?>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Usuario</th>
                      <th>Total de Tardanza</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($detalles_mes[$m] as $detalle): ?>
                      <tr>
                        <td>
                          <a href="ver_detalle.php?rut=<?= $detalle['RUT'] ?>" target="_blank">
                              <?= $detalle['nombre_completo'] ?>
                          </a>
                        </td>
                        <td><?= $detalle['total_tardanza'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php else: ?>
                <p>No hay registros de incumplimiento para este mes.</p>
              <?php endif; ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
  </div>
</body>
</html>
<?php
$conn->close();
?>
