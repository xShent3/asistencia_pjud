<?php
$rut = $_SESSION['rut'];
$sql_anios = "SELECT YEAR(dia) as anio FROM dias_usuario WHERE RUT = ? GROUP BY YEAR(dia) ORDER BY anio DESC";
$stmt_anios = $conn->prepare($sql_anios);
$stmt_anios->bind_param("i", $rut);
$stmt_anios->execute();
$result_anios = $stmt_anios->get_result();

$anios = [];
while ($row = $result_anios->fetch_assoc()) {
    $anios[] = $row['anio'];
}
$mes_seleccionado = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio_seleccionado = isset($_GET['anio']) ? $_GET['anio'] : (count($anios) > 0 ? max($anios) : date('Y'));


$sql_usuario = "SELECT usuario.*, horarios.nombre_horario, horarios.hora_inicio, horarios.hora_termino 
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
function calcularPuntualidad($dia) {
    $sin_registro = empty($dia['hora_inicio']) && empty($dia['hora_fin']) && $dia['tiempo_excedido'] == '00:00:00';
    if ($sin_registro) return '❌ Falta';
    return ($dia['tiempo_excedido'] != '00:00:00') ? '⚠️ Ingreso tarde' : '✅ Puntual';
}
?>