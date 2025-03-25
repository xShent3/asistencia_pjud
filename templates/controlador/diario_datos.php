<?php
$fecha_actual = date('d-m-Y H:i:s');
$sql = "SELECT u.nombre_completo, d.hora_inicio 
        FROM dias_usuario d
        INNER JOIN usuario u ON d.RUT = u.RUT 
        WHERE DATE(d.dia) = CURDATE() 
        AND d.hora_inicio IS NOT NULL";

$resultado = $conn->query($sql);
$dias_semana = [
    'Monday' => 'Lunes',
    'Tuesday' => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday' => 'Jueves',
    'Friday' => 'Viernes',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
];
$fecha_servidor = new DateTime();
$nombre_dia = $dias_semana[$fecha_servidor->format('l')];


$sql = "SELECT u.nombre_completo, 
               d.hora_inicio, 
               d.hora_fin 
        FROM dias_usuario d
        INNER JOIN usuario u ON d.RUT = u.RUT 
        WHERE DATE(d.dia) = CURDATE() 
        AND d.hora_inicio IS NOT NULL";

$resultado = $conn->query($sql);
?>