<?php

$meses_es = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
  ];
  
  $query_tribunales = "SELECT id_tribunal, nombre_tribunal FROM tribunales ORDER BY nombre_tribunal";
  $result_tribunales = $conn->query($query_tribunales);
  
  $selected_tribunal = null;
  $startYear = date('Y');
  $startMonth = date('m');
  $endYear = date('Y');
  $endMonth = date('m');
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tribunal'])) {
      $selected_tribunal = $_POST['tribunal'];
    }
    if (isset($_POST['start_year'])) {
      $startYear = $_POST['start_year'];
    }
    if (isset($_POST['start_month'])) {
      $startMonth = $_POST['start_month'];
    }
    if (isset($_POST['end_year'])) {
      $endYear = $_POST['end_year'];
    }
    if (isset($_POST['end_month'])) {
      $endMonth = $_POST['end_month'];
    }
  } else {
    if (isset($_GET['tribunal'])) {
      $selected_tribunal = $_GET['tribunal'];
    }
    if (isset($_GET['start_year'])) {
      $startYear = $_GET['start_year'];
    }
    if (isset($_GET['start_month'])) {
      $startMonth = $_GET['start_month'];
    }
    if (isset($_GET['end_year'])) {
      $endYear = $_GET['end_year'];
    }
    if (isset($_GET['end_month'])) {
      $endMonth = $_GET['end_month'];
    }
    if (!$selected_tribunal && $result_tribunales->num_rows > 0) {
      $first = $result_tribunales->fetch_assoc();
      $selected_tribunal = $first['id_tribunal'];
      $result_tribunales->data_seek(0);
    }
  }
  
  $query_usuarios = "SELECT COUNT(*) as total FROM usuario WHERE id_tribunal = ? AND estado = 1";
  $stmt_usuarios = $conn->prepare($query_usuarios);
  $stmt_usuarios->bind_param("i", $selected_tribunal);
  $stmt_usuarios->execute();
  $total_usuarios = $stmt_usuarios->get_result()->fetch_assoc()['total'];
  
  $query_years = "SELECT DISTINCT YEAR(dia) as anio FROM dias_usuario ORDER BY anio DESC";
  $result_years = $conn->query($query_years);
  
  $startDate = new DateTime("$startYear-$startMonth-01");
  $endDate = (new DateTime("$endYear-$endMonth-01"))->modify('last day of this month');
  $fecha_inicio_str = $startDate->format('Y-m-d');
  $fecha_fin_str = $endDate->format('Y-m-d');
  
  $query = "SELECT 
              YEARWEEK(dia, 3) as semana,
              SUM(CASE WHEN tiempo_excedido > '00:00:00' OR COALESCE(tiempo_salida, '00:00:00') <> '00:00:00' THEN 1 ELSE 0 END) as incumplieron
            FROM dias_usuario du
            JOIN usuario u ON du.RUT = u.RUT
            WHERE u.id_tribunal = ?
              AND du.dia BETWEEN ? AND ?
            GROUP BY YEARWEEK(dia, 3)
            ORDER BY du.dia";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iss", $selected_tribunal, $fecha_inicio_str, $fecha_fin_str);
  $stmt->execute();
  $result = $stmt->get_result();
  $datos_semanas = $result->fetch_all(MYSQLI_ASSOC);
  $semanas_incumplimientos = [];
  foreach ($datos_semanas as $row) {
    $semanas_incumplimientos[$row['semana']] = $row['incumplieron'];
  }
  
  $query2 = "SELECT 
               YEARWEEK(dia, 3) as semana,
               SUM(TIME_TO_SEC(tiempo_excedido)) as total_excedido,
               SUM(TIME_TO_SEC(tiempo_salida)) as total_salida
             FROM dias_usuario du
             JOIN usuario u ON du.RUT = u.RUT
             WHERE u.id_tribunal = ?
               AND du.dia BETWEEN ? AND ?
             GROUP BY YEARWEEK(dia, 3)
             ORDER BY du.dia";
  $stmt2 = $conn->prepare($query2);
  $stmt2->bind_param("iss", $selected_tribunal, $fecha_inicio_str, $fecha_fin_str);
  $stmt2->execute();
  $result2 = $stmt2->get_result();
  $datos_semanas_duracion = $result2->fetch_all(MYSQLI_ASSOC);
  $semanas_duracion = [];
  foreach ($datos_semanas_duracion as $row) {
    $total_seconds = (isset($row['total_excedido']) ? $row['total_excedido'] : 0) + (isset($row['total_salida']) ? $row['total_salida'] : 0);
    $semanas_duracion[$row['semana']] = $total_seconds;
  }
  
  $interval = DateInterval::createFromDateString('1 week');
  $period = new DatePeriod($startDate, $interval, $endDate);
  
  $reporte = [];
  foreach ($period as $dt) {
    $inicio_semana = clone $dt;
    $inicio_semana->modify(($dt->format('w') == 1 ? '' : 'last monday'));
    $fin_semana = clone $inicio_semana;
    $fin_semana->modify('sunday this week');
    $semana_num = $dt->format("oW");
    if (!isset($semanas_incumplimientos[$semana_num]) && !isset($semanas_duracion[$semana_num])) {
      continue;
    }
    $total_possible = $total_usuarios * 7;
    $incumplimientos = $semanas_incumplimientos[$semana_num] ?? 0;
    $cumplidos = $total_possible - $incumplimientos;
    $porcentaje_cumplimiento = $total_possible > 0 ? round(($cumplidos / $total_possible) * 100, 2) : 0;
    $horas_por_usuario = ($selected_tribunal == 29) ? 44 : 45;
    $total_horas_tribunal = $total_usuarios * $horas_por_usuario;
    $inasistencias_segundos = $semanas_duracion[$semana_num] ?? 0;
    $inasistencias_horas = $inasistencias_segundos / 3600;
    $porcentaje_inasistencia = $total_horas_tribunal > 0 ? round(($inasistencias_horas / $total_horas_tribunal) * 100, 2) : 0;
    $reporte[] = [
      'rango' => $inicio_semana->format('d/m/Y') . ' - ' . $fin_semana->format('d/m/Y'),
      'inicio' => $inicio_semana->format('Y-m-d'),
      'porcentaje_cumplimiento' => $porcentaje_cumplimiento,
      'porcentaje_inasistencia' => $porcentaje_inasistencia,
      'total_horas_tribunal' => $total_horas_tribunal,
      'inasistencias_horas' => round($inasistencias_horas, 2)
    ];
  }
?>