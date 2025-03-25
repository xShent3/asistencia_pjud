<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/detalle_datos.php';
include '../../controlador/verificacion.php';
verificarAcceso([1,2,3]);
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
  <header class="d-flex justify-content-between align-items-center p-3">
    <form id="filtrosForm" method="get" class="d-flex align-items-center gap-3 me-auto">
      <input type="hidden" name="rut" value="<?= $rut ?>">
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
            $selected = ($numero == $mes_seleccionado) ? 'selected' : '';
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
            $selected = ($anio == $anio_seleccionado) ? 'selected' : '';
            echo "<option value='$anio' $selected>$anio</option>";
          }

          if (empty($anios)) {
            echo "<option value='" . date('Y') . "' selected>" . date('Y') . "</option>";
          }
          ?>
        </select>
      </div>
    </form>
    <a href="ver_usuarios.php" class="ms-4 me-3 text-white">Volver al Menú</a>
  </header>

  <div class="container my-4">
    <p id="text_horario">
      <?= $usuario['nombre_completo'] ?> /
      <?= $usuario['nombre_horario'] ?>
      (<?= date('H:i', strtotime($usuario['hora_inicio'])) ?> -
       <?= date('H:i', strtotime($usuario['hora_termino'])) ?>)
    </p>
    <p id="text_horario">Total de horas de atraso: <?= $total_atraso ?></p>
    
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Fecha</th>
          <th scope="col">Hora de Entrada</th>
          <th scope="col">Hora de Salida</th>
          <th scope="col">Atraso</th>
          <th scope="col">Puntualidad</th>
          <th scope="col">Autorización</th>
          <th scope="col">Teletrabajo</th>
          <th scope="col">Modificado por</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $contador = 1;
        if ($dias->num_rows > 0) {
          while ($dia = $dias->fetch_assoc()) {
            $fecha         = date('d/m/Y', strtotime($dia['dia']));
            $hora_entrada  = $dia['hora_inicio'] ? date('H:i', strtotime($dia['hora_inicio'])) : '';
            $hora_salida   = $dia['hora_fin']    ? date('H:i', strtotime($dia['hora_fin']))     : '';
            $autorizado    = $dia['auth'] == 1 ? 'Sí' : 'No';
            $teletrabajo   = $dia['teletrabajo'] == 1 ? 'Sí' : 'No';
            $fila_style    = $dia['auth'] == 1 ? 'style="background-color: rgba(144, 238, 144, 0.5) !important;"' : '';
            $modificado_por = !empty($dia['mod_por']) ? $dia['mod_por'] : 'no se ha hecho modificacion';
            ?>
            <tr <?= $fila_style ?>>
              <th scope="row"><?= $contador ?></th>
              <td><?= $fecha ?></td>
              <td class="hora-entrada"><?= $hora_entrada ?></td>
              <td class="hora-salida"><?= $hora_salida ?></td>
              <td><?= $dia['tiempo_excedido'] ?></td>
              <td><?= calcularPuntualidad($dia) ?></td>
              <td><?= $autorizado ?></td>
              <td><?= $teletrabajo ?></td>
              <td><?= $modificado_por ?></td>
              <td>
                <button class="btn btn-primary btn-modificar"
                        data-id="<?= $dia['id_dia'] ?>"
                        data-hora-entrada="<?= $hora_entrada ?>"
                        data-hora-salida="<?= $hora_salida ?>">
                  Modificar
                </button>
              </td>
            </tr>
            <?php
            $contador++;
          }
        } else {
          echo "<tr><td colspan='10' class='text-center'>No hay registros para este período</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>


  
  <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formModificar" method="post" action="../../controlador/detalle_datos.php">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalModificarLabel">Modificar Horas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            
            <input type="hidden" name="accion" value="modificar_horas">
            
            <input type="hidden" name="registro_id" id="registro_id">
            
            <input type="hidden" name="original_hora_entrada" id="original_hora_entrada">
            <input type="hidden" name="original_hora_salida" id="original_hora_salida">
            <div class="mb-3">
              <label for="hora_entrada" class="form-label">Hora de Entrada</label>
              <input type="time" class="form-control" id="hora_entrada" name="hora_entrada">
            </div>
            <div class="mb-3">
              <label for="hora_salida" class="form-label">Hora de Salida</label>
              <input type="time" class="form-control" id="hora_salida" name="hora_salida">
            </div>
            <div class="form-text">Debe modificar al menos uno de los campos.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function(){

      $('.btn-modificar').click(function(){
        var registroId   = $(this).data('id');
        var horaEntrada  = $(this).data('hora-entrada');
        var horaSalida   = $(this).data('hora-salida');
        
        $('#registro_id').val(registroId);
        $('#hora_entrada').val(horaEntrada);
        $('#hora_salida').val(horaSalida);
        $('#original_hora_entrada').val(horaEntrada);
        $('#original_hora_salida').val(horaSalida);
        
        var myModal = new bootstrap.Modal(document.getElementById('modalModificar'));
        myModal.show();
      });
      

      $('#formModificar').submit(function(e){
        var newHoraEntrada = $('#hora_entrada').val();
        var newHoraSalida  = $('#hora_salida').val();
        var origHoraEntrada = $('#original_hora_entrada').val();
        var origHoraSalida  = $('#original_hora_salida').val();
        
        if(newHoraEntrada === origHoraEntrada && newHoraSalida === origHoraSalida){
          alert("Debe modificar al menos uno de los campos.");
          e.preventDefault();
        }
      });
    });
  </script>
  <?php
  function calcularPuntualidad($dia) {
    $sin_registro = empty($dia['hora_inicio']) && empty($dia['hora_fin']) && $dia['tiempo_excedido'] == '00:00:00';
    if ($sin_registro) return '❌ Falta';
    $excedido = $dia['tiempo_excedido'] != '00:00:00';
    return ($excedido) ? '⚠️ Ingreso tarde' : '✅ Puntual';
  }
  $conn->close();
  ?>
</body>
</html>
