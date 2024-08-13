<?php
// Ruta al archivo XML
$xmlFilePath = __DIR__ . '/reports/cucumber_report.xml/default.xml';

// Verificar si el archivo XML existe
if (!file_exists($xmlFilePath)) {
    die("El archivo XML no existe en la ruta especificada.");
}

// Cargar el archivo XML
$xml = simplexml_load_file($xmlFilePath);

// Convertir XML a JSON
$json = json_encode($xml, JSON_PRETTY_PRINT);

// Ruta para guardar el archivo JSON
$jsonFilePath = __DIR__ . '/reports/cucumber_report.json';

// Guardar el JSON en un archivo
file_put_contents($jsonFilePath, $json);

echo "El archivo JSON ha sido generado y guardado en: $jsonFilePath\n";
