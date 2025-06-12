<?php
header('Content-Type: application/json');

$logs = [];

require_once("../../config/conexion.php");
require_once("../../models/Vulnerabilidad.php");
require_once("../../vendor/autoload.php");

// Supongamos que ya tienes las variables para la ruta, zipName, id_zip y un objeto $vuln
// $extractPath = ...; $zipName = ...; $id_zip = ...; $vuln = new Vulnerabilidad();

list($vCount, $totalCount, $archivosCorregidos, $tabs, $panes) = analizarArchivosExtraidos($extractPath, $zipName, $id_zip, $vuln, $logs);

echo json_encode([
    'logs' => $logs,
    'resultado' => [
        'vCount' => $vCount,
        'totalCount' => $totalCount,
        'tabs' => $tabs,
        'panes' => $panes,
    ]
]);
exit;