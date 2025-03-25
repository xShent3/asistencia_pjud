<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
include '../../controlador/admt_asist_diaria.php';
verificarAcceso([1]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poder Judicial - Asistencia Diaria</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../modelo/estilos.css">
</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
        <a href="../pagina_login.php">Cerrar Sesión</a>
        <div>
            <a href="formulario_usuario.php">Crear Usuario</a>
            <a href="ver_usuarios.php">Inicio</a>
        </div>
    </header>
    <div class="fecha-horario">
        <?php echo "<span class='dia-semana'>{$nombre_dia}</span>, "; ?>
        <span id="fecha-actual"><?= date('d/m/Y', strtotime($fecha)) ?></span>
        <br>
        <span id="total-atraso">Total de Atraso: <?= $total_atraso ?></span>
    </div>
    <div class="d-flex justify-content-end mb-3">
      <a href="export_excel_asistencia_diaria.php?fecha=<?= $fecha ?>&tribunal=<?= $_SESSION['id_tribunal'] ?>&t=<?= time() ?>" class="btn btn-success">
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($fila = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $fila['nombre_completo'] ?></td>
                                    <td><?= $fila['horario_inicio'] . " - " . $fila['horario_fin'] ?></td>
                                    <td><?= $fila['hora_inicio'] ? $fila['hora_inicio'] : 'No se ha presentado' ?></td>
                                    <td><?= $fila['hora_fin'] ? $fila['hora_fin'] : 'No se ha presentado' ?></td>
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
