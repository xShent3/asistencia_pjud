<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/horario_diario_datos.php';
verificarAcceso([1,2,3]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poder Judicial - Asistencia Diaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../modelo/estilos.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
</style>

</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
        <a href="../pagina_login.php">Cerrar Sesión</a>
        <div class="dropdown">
          <a href="#" class="text-white fw-bold dropdown-toggle" data-bs-toggle="dropdown">Reportes</a>
          <?php if ($_SESSION['id_rol'] == 1) : ?>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_mensual.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_registros.php">Asistencia Total de Registros</a></li>
            </ul>
          <?php elseif ($_SESSION['id_rol'] == 2) : ?>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="asistencia_general_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="asistencia_general_mes.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="asistencia_general_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="asistencia_registros.php">Asistencia Total de Registros</a></li>
            </ul>
          <?php elseif ($_SESSION['id_rol'] == 3) : ?>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="asistencia_general_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="asistencia_general_mes.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="asistencia_general_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="asistencia_registros.php">Asistencia Total de Registros</a></li>
            </ul>
          <?php endif; ?>
      </div> 
        <div>
            <a href="formulario_usuario.php">Crear Usuario</a>
            <a href="ver_usuarios.php">Inicio</a>
            <a href="buscar_usuario.php">Buscar Usuario</a>
            <a href="../cambio_contrasena.php">Cambio de contraseña</a>
        </div>
    </header>
    <div class="fecha-horario">
        <?php

            echo "<span class='dia-semana'>{$nombre_dia}</span>, ";
        ?>
        <span id="fecha-actual"><?= date('d/m/Y', strtotime($fecha)) ?></span>
        <br>
        <span id="total-atraso">Total de Atraso: <?= $total_atraso ?></span>
    </div>
    
    <div class="d-flex justify-content-end mb-3">
      <a href="export_excel_horario_diario.php?fecha=<?= $fecha ?>&tribunal=<?= $_SESSION['id_tribunal'] ?>&t=<?= time() ?>" class="btn btn-success">
        Excel
      </a>
    </div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="table-responsive-lg">
                <?php if ($resultado->num_rows > 0): ?>
                    <table id="usuariospjud" class="table table-striped mx-auto">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Horario Programado</th>
                                <th>Hora de Llegada</th>
                                <th>Hora de Salida</th>
                                <th>Autorización</th>
                                <th>Tiempo Excedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($fila = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $fila['nombre_completo'] ?></td>
                                    <td><?= $fila['horario_inicio'] . " - " . $fila['horario_termino'] ?></td>
                                    <td><?= $fila['hora_inicio'] ?></td>
                                    <td><?= $fila['hora_fin'] ?></td>
                                    <td><?= ($fila['auth'] == 1) ? 'Sí' : 'No' ?></td>
                                    <td><?= $fila['tiempo_excedido'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No hay registros de asistencia para el día seleccionado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../modelo/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
$conn->close();
?>
