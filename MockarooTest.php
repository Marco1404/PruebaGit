<?php
// Tu clave API de Mockaroo
$apiKey = 'tu-api-key';  // Sustituye con tu clave API de Mockaroo

// Nombre del esquema que has creado en Mockaroo
$schema = 'mi_esquema_de_clientes';  // Sustituye con el nombre de tu esquema

// Número de registros que deseas generar
$count = 100;  // Puedes cambiarlo por la cantidad que necesites

// URL de la API de Mockaroo para generar datos
$url = "https://api.mockaroo.com/api/generate.json?key=$apiKey&schema=$schema&count=$count";

// Inicializar cURL
$ch = curl_init();

// Establecer opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);  // URL de la API de Mockaroo
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Para que cURL devuelva la respuesta

// Ejecutar la solicitud
$response = curl_exec($ch);

// Verificar si ocurrió algún error en cURL
if (curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
    exit;
}

// Cerrar la sesión de cURL
curl_close($ch);

// Convertir la respuesta JSON en un array de PHP
$data = json_decode($response, true);

// Ver los datos obtenidos
echo "<pre>";
print_r($data);  // Esto imprime los datos para ver cómo se ven
echo "</pre>";

// Aquí puedes guardar los datos en tu base de datos o hacer cualquier otra operación
?>
