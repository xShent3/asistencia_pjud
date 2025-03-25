<?php
include_once __DIR__ . '/../modelo/conexion_bd.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'modificar_horas') {
  $registro_id            = $_POST['registro_id'];
  $nueva_hora_entrada     = $_POST['hora_entrada'];
  $nueva_hora_salida      = $_POST['hora_salida'];
  $original_hora_entrada  = $_POST['original_hora_entrada'];
  $original_hora_salida   = $_POST['original_hora_salida'];
  if ($nueva_hora_entrada === $original_hora_entrada && $nueva_hora_salida === $original_hora_salida) {
    echo "<script>alert('Debe modificar al menos un campo.');</script>";
  } else {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $admin = isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : 'desconocido';

    
    
    $update_sql = "UPDATE dias_usuario 
    SET hora_inicio = ?, hora_fin = ?, mod_por = ? 
    WHERE id_dia = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sssi", $nueva_hora_entrada, $nueva_hora_salida, $admin, $registro_id);

    if ($stmt_update->execute()) {
      
      
      
      $query_info = "SELECT du.dia, du.hora_inicio, u.RUT, u.id_tribunal, 
                            h.hora_inicio AS horario_inicio, h.hora_inicio_u 
                     FROM dias_usuario du
                     JOIN usuario u ON du.RUT = u.RUT
                     JOIN horarios h ON u.id_horario = h.id_horario
                     WHERE du.id_dia = ?";
      $stmt_info = $conn->prepare($query_info);
      $stmt_info->bind_param("i", $registro_id);
      $stmt_info->execute();
      $result_info = $stmt_info->get_result();
      
      if ($record = $result_info->fetch_assoc()) {
        $dia = $record['dia'];
        
        $dia_semana = date('N', strtotime($dia));
        $id_tribunal = $record['id_tribunal'];
        
        
        if ($dia_semana == 6 && $id_tribunal != 29) {
          $hora_inicio_base = $record['hora_inicio_u'];
        } elseif ($dia_semana == 5 && $id_tribunal == 29) {
          $hora_inicio_base = $record['hora_inicio_u'];
        } else {
          $hora_inicio_base = $record['horario_inicio'];
        }
        
        
        $tiempo_excedido = "00:00:00";
        
        if (strtotime($record['hora_inicio']) > strtotime($hora_inicio_base)) {
          $dtRegistrada = new DateTime($record['hora_inicio']);
          $dtEsperada   = new DateTime($hora_inicio_base);
          $interval     = $dtRegistrada->diff($dtEsperada);
          $tiempo_excedido = $interval->format('%H:%I:%S');
        }
        
        
        $tiempo_salida = "00:00:00";
        
        
        $updateTimesQuery = "UPDATE dias_usuario 
                             SET tiempo_excedido = ?, tiempo_salida = ?
                             WHERE id_dia = ?";
        $stmtTimes = $conn->prepare($updateTimesQuery);
        $stmtTimes->bind_param("ssi", $tiempo_excedido, $tiempo_salida, $registro_id);
        $stmtTimes->execute();
        $stmtTimes->close();
      }
      $stmt_info->close();
      
      if ($stmt_update->execute()) {
        echo "<script>
                 alert('Registro actualizado y tiempos recalculados correctamente.');
                 window.location.href = document.referrer;
              </script>";
        exit();
    } 
    } else {
      echo "<script>
               alert('Error al actualizar el registro.');
               window.location.href = document.referrer;
            </script>";
      exit();
  }
    $stmt_update->close();
  }
}

if (!isset($_GET['rut'])) die("RUT no especificado");
$rut = $_GET['rut'];


$sql_anios = "SELECT YEAR(dia) as anio FROM dias_usuario WHERE RUT = ? GROUP BY YEAR(dia) ORDER BY anio DESC";
$stmt_anios = $conn->prepare($sql_anios);
$stmt_anios->bind_param("i", $rut);
$stmt_anios->execute();
$result_anios = $stmt_anios->get_result();

$anios = [];
while ($row = $result_anios->fetch_assoc()) {
  $anios[] = $row['anio'];
}


$mes_seleccionado  = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio_seleccionado = isset($_GET['anio']) ? $_GET['anio'] : (count($anios) > 0 ? max($anios) : date('Y'));


$sql_usuario = "SELECT nombre_completo, usuario.*, horarios.nombre_horario, horarios.hora_inicio, horarios.hora_termino 
                FROM usuario 
                JOIN horarios ON usuario.id_horario = horarios.id_horario 
                WHERE usuario.rut = ?";
$stmt = $conn->prepare($sql_usuario);
$stmt->bind_param("i", $rut);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();


$sql_dias = "SELECT * FROM dias_usuario 
             WHERE RUT = ? 
             AND MONTH(dia) = ?
             AND YEAR(dia) = ?
             ORDER BY dia DESC";
$stmt = $conn->prepare($sql_dias);
$stmt->bind_param("iii", $rut, $mes_seleccionado, $anio_seleccionado);
$stmt->execute();
$dias = $stmt->get_result();


$sql_total_atraso = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_excedido))) AS total_atraso 
                     FROM dias_usuario 
                     WHERE RUT = ? 
                     AND MONTH(dia) = ? 
                     AND YEAR(dia) = ?";
$stmt_total_atraso = $conn->prepare($sql_total_atraso);
$stmt_total_atraso->bind_param("iii", $rut, $mes_seleccionado, $anio_seleccionado);
$stmt_total_atraso->execute();
$total_atraso_result = $stmt_total_atraso->get_result()->fetch_assoc();
$total_atraso = $total_atraso_result['total_atraso'] ?? '00:00:00';
$stmt_total_atraso->close();
?>
