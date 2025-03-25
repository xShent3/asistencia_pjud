<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/admt_asistencia_fecha.php';
verificarAcceso([1]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asistencia General por Día - Rango de Fechas - Admin Total</title>
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
        Asistencia General por Día - Período: 
        <?= date('d/m/Y', strtotime($fecha_inicio)) ?> a 
        <?= date('d/m/Y', strtotime($fecha_fin)) ?> 
        - Tribunal: 
        <?php 

            $query_t = "SELECT nombre_tribunal FROM tribunales WHERE id_tribunal = ?";
            $stmt_t = $conn->prepare($query_t);
            $stmt_t->bind_param("i", $selected_tribunal);
            $stmt_t->execute();
            $nombre_tribunal = $stmt_t->get_result()->fetch_assoc()['nombre_tribunal'] ?? '';
            echo $nombre_tribunal;
        ?>
        <br>- Dotación: <?= $total_usuarios ?>
        <br>- Total de Tardanza del Período: <?= $total_tardanza_periodo ?>
    </h2>

    
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <select name="tribunal" class="form-select">
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

        <div class="col-md-4">
            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
        </div>
        <div class="col-md-4">
            <label for="fecha_fin" class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Actualizar Reporte</button>
        </div>
    </form>

    
    <div class="d-flex justify-content-end mb-3">
      <a href="export_excel_asistencia_fecha.php?fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>&tribunal=<?= $selected_tribunal ?>&t=<?= time() ?>" class="btn btn-success">
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
                  $nombresAusentes     = $valores['lista_ausentes'];
                  $nombresAsistieron   = array_merge($valores['lista_incumplieron'], $valores['lista_cumplieron'] ?? []);
                  
                  $tooltipIncumplieron = count($nombresIncumplieron) ? implode(", ", $nombresIncumplieron) : "Ninguno";
                  $tooltipAusentes     = count($nombresAusentes) ? implode(", ", $nombresAusentes) : "Ninguno";
                  $tooltipAsistieron   = count($nombresAsistieron) ? implode(", ", $nombresAsistieron) : "Ninguno";
                ?>
                <tr <?= $valores['es_fin_de_semana'] ? 'style="background-color: rgba(255, 0, 0, 0.2);"' : '' ?>>
                    <td>
                      <a href="asistencia_diaria.php?fecha=<?= $fecha ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>&tribunal=<?= $selected_tribunal ?>" target="_blank">
                        <?= DateTime::createFromFormat('Y-m-d', $fecha)->format('d/m/Y') ?>
                      </a>
                    </td>
                    <td><?= $valores['total_tardanza'] ?: '00:00:00' ?></td>
                    <td data-bs-toggle="tooltip" data-bs-placement="top" title="<?= htmlspecialchars($tooltipIncumplieron) ?>">
                      <?= $valores['incumplieron'] ?>
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
