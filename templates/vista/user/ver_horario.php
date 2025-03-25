<?php
session_start();
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
verificarAcceso([4]);
include '../../controlador/horario_user_datos.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poder Judicial - Ver detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../modelo/estilos.css">
</head>
<body>
<header class="d-flex justify-content-between align-items-center">
    <form id="filtrosForm" method="get" class="d-flex align-items-center gap-3 me-auto">
        <div class="d-flex align-items-center">
            <label for="selectMes" class="form-label text-white mb-0">Mes:</label>
            <select id="selectMes" name="mes" class="form-select ms-2" onchange="this.form.submit()">
                <?php
                $meses = [
                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
                    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
                    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
                    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                ];
                foreach ($meses as $numero => $nombre) {
                    $selected = $numero == $mes_seleccionado ? 'selected' : '';
                    echo "<option value='$numero' $selected>$nombre</option>";
                }
                ?>
            </select>
        </div>
        <div class="d-flex align-items-center">
            <label for="selectAnio" class="form-label text-white mb-0 ms-4">Año:</label>
            <select id="selectAnio" name="anio" class="form-select ms-2" onchange="this.form.submit()">
                <?php
                foreach ($anios as $anio) {
                    $selected = $anio == $anio_seleccionado ? 'selected' : '';
                    echo "<option value='$anio' $selected>$anio</option>";
                }
                if (empty($anios)) {
                    echo "<option value='" . date('Y') . "' selected>" . date('Y') . "</option>";
                }
                ?>
            </select>
        </div>
    </form>
    <a href="../cambio_contrasena.php" class="ms-4 me-3">Cambio de contraseña</a>
    <a href="../pagina_login.php" class="ms-4 me-3">Cerrar sesión</a>
</header>

<div class="row min-vh-100">
    <div class="col-12 p-4">
        <p id="text_horario">
            <?= $usuario['nombre_horario'] ?>
            (<?= date('H:i', strtotime($usuario['hora_inicio'])) ?> - 
            <?= date('H:i', strtotime($usuario['hora_termino'])) ?>)
        </p>
        <span id="total-atraso">Total de Atraso: <?= $total_atraso ?></span>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Hora de Entrada</th>
                    <th>Hora de Salida</th>
                    <th>Atraso</th>
                    <th>Puntualidad</th>
                    <th>Autorización</th>
                    <th>Modificado por</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador = 1;
                while ($dia = $dias->fetch_assoc()) {
                    $fecha_formateada = date('d/m/Y', strtotime($dia['dia']));
                    $hora_inicio = $dia['hora_inicio'] ? date('H:i', strtotime($dia['hora_inicio'])) : 'No se ha presentado';
                    $hora_fin = $dia['hora_fin'] ? date('H:i', strtotime($dia['hora_fin'])) : 'No se ha presentado';
                    $autorizado = $dia['auth'] == 1 ? 'Sí' : 'No';
                    $fila_style = $dia['auth'] == 1 ? 'style="background-color: rgba(144, 238, 144, 0.5) !important;"' : '';
                    $modificado = !empty($dia['mod_por']) ? $dia['mod_por'] : 'Sin modificación';
                
                    echo "<tr $fila_style>
                        <th>$contador</th>
                        <td>$fecha_formateada</td>
                        <td>$hora_inicio</td>
                        <td>$hora_fin</td>
                        <td>{$dia['tiempo_excedido']}</td>
                        <td>" . calcularPuntualidad($dia) . "</td>
                        <td>$autorizado</td>
                        <td>$modificado</td>
                    </tr>";
                    $contador++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php $conn->close(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
