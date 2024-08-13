<?php
// Ruta al archivo XML
$xmlFilePath = __DIR__ . '/reports/cucumber_report.xml/default.xml';
$jsonFilePath = __DIR__ . '/reports/cucumber_report.json';

// Cargar el archivo XML
$xml = simplexml_load_file($xmlFilePath);
if ($xml === false) {
    die("Error al cargar el archivo XML.");
}

// Función para convertir XML a JSON en el formato de Cucumber
function convertXmlToCucumberJson($xml)
{
    $jsonArray = [];

    foreach ($xml->testsuite as $testsuite) {
        $feature = [
            "uri" => (string)$testsuite['file'],
            "id" => str_replace(' ', '-', strtolower((string)$testsuite['name'])),
            "keyword" => "Feature",
            "name" => (string)$testsuite['name'],
            "description" => "",
            "line" => 1, // Línea arbitraria, puede ajustarse según tu contexto
            "elements" => []
        ];

        foreach ($testsuite->testcase as $testcase) {
            $scenario = [
                "id" => str_replace(' ', '-', strtolower((string)$testcase['name'])),
                "keyword" => "Scenario",
                "name" => (string)$testcase['name'],
                "description" => "",
                "line" => 1, // Línea arbitraria
                "type" => "scenario",
                "steps" => []
            ];

            $step = [
                "keyword" => "Given ",
                "name" => (string)$testcase['name'],
                "line" => 1, // Línea arbitraria
                "match" => [
                    "location" => "unknown"
                ],
                "result" => [
                    "status" => (string)$testcase->failure ? "failed" : "passed",
                    "duration" => 0, // Duración arbitraria, puede ajustarse si tienes datos de tiempo
                    "error_message" => (string)$testcase->failure ?? ""
                ]
            ];

            $scenario['steps'][] = $step;
            $feature['elements'][] = $scenario;
        }

        $jsonArray[] = $feature;
    }

    return json_encode($jsonArray, JSON_PRETTY_PRINT);
}

// Convertir y guardar el archivo JSON
$jsonContent = convertXmlToCucumberJson($xml);
file_put_contents($jsonFilePath, $jsonContent);

echo "El archivo JSON ha sido generado y guardado en: $jsonFilePath\n";
