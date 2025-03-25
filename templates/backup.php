<?php
$apiKey = 'TU_API_KEY'; 
$city = 'Santiago';
$country = 'CL';
$url = "https://api.ipgeolocation.io/timezone?apiKey=$apiKey&tz=America/Santiago";
$response = file_get_contents($url);
if ($response === FALSE) {
    die('Error al intentar conectar con la API.');
}

$data = json_decode($response, true);

if (isset($data['date_time_ymd'])) {
    echo "La hora actual en $city, $country es: " . $data['date_time_ymd'] . ' ' . $data['time_24'];
} else {
    echo "Error al obtener la hora: " . $data['message'];
}
?>