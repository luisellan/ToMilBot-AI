<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once("../../config/conexion.php");
require_once("../../models/Vulnerabilidad.php");
require_once("../../vendor/autoload.php");

include 'callGemini.php';
include 'generarGraficoIndicador.php';
include 'crearReportePDF.php';
include 'mailer.php';
if (isset($_FILES['file']) && pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) === 'zip') {
    $zipName   = $_FILES['file']['name'];
    $zipTmp    = $_FILES['file']['tmp_name'];
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $destPath = $uploadDir . $zipName;
    if (!move_uploaded_file($zipTmp, $destPath)) die("Error al cargar el ZIP.");
    echo "Archivo ZIP cargado correctamente.<br>";

    $extractPath = $uploadDir . pathinfo($zipName, PATHINFO_FILENAME) . '/';
    $zip = new ZipArchive;
    if ($zip->open($destPath) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        echo "Archivo ZIP extraído correctamente.<br>";
    } else {
        die("No se pudo abrir el ZIP.");
    }

    $vuln = new Vulnerabilidad();
    $usu_id = isset($_POST["user_idx"]) ? $_POST["user_idx"] : null;
    $id_zip = $vuln->resertar($zipName, $usu_id);
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($extractPath));
    $tabs = '';
    $panes = '';
    $vCount = $totalCount = 0;
    $archivosCorregidos = [];

    $porcentajeCodigoEscaneadoSum = 0;
    $porcentajeIncidenciasErroresSum = 0;
    $tiempoPromedioRemediacionSum = 0;
    $nivelMadurezDesarrolloSeguroSum = 0;
    $porcentajeCumplimientoPruebasSeguridadSum = 0;
    $falsosPositivosAnalisisSum = 0;
    $riesgosPorCadaMilLineasSum = 0;

    $archivosEscaneados = 0;

    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $ruta = $file->getPathname();
        $nombreArchivo = $file->getFilename();
        if (!is_readable($ruta)) {
            echo "No legible: $nombreArchivo<br>";
            continue;
        }

        $contenido = file_get_contents($ruta);
        if ($contenido === false) {
            echo "Error lectura: $nombreArchivo<br>";
            continue;
        }

        $totalCount++;
        $respuesta = callGemini($contenido, 'analyze');

        if ($respuesta && str_contains(strtolower($respuesta), 'vulnerabilidad')) {
            $porcentajeCodigoEscaneado = intval(callGemini($contenido, 'porcentajeCodigoEscaneado'));
            $porcentajeIncidenciasErrores = intval(callGemini($contenido, 'porcentajeIncidenciasErrores'));
            $tiempoPromedioRemediacion = intval(callGemini($contenido, 'tiempoPromedioRemediacion'));
            $nivelMadurezDesarrolloSeguro = intval(callGemini($contenido, 'nivelMadurezDesarrolloSeguro'));
            $porcentajeCumplimientoPruebasSeguridad = intval(callGemini($contenido, 'porcentajeCumplimientoPruebasSeguridad'));
            $falsosPositivosAnalisis = intval(callGemini($contenido, 'falsosPositivosAnalisis'));
            $riesgosPorCadaMilLineas = intval(callGemini($contenido, 'riesgosPorCadaMilLineas'));

            $porcentajeCodigoEscaneadoSum += $porcentajeCodigoEscaneado;
            $porcentajeIncidenciasErroresSum += $porcentajeIncidenciasErrores;
            $tiempoPromedioRemediacionSum += $tiempoPromedioRemediacion;
            $nivelMadurezDesarrolloSeguroSum += $nivelMadurezDesarrolloSeguro;
            $porcentajeCumplimientoPruebasSeguridadSum += $porcentajeCumplimientoPruebasSeguridad;
            $falsosPositivosAnalisisSum += $falsosPositivosAnalisis;
            $riesgosPorCadaMilLineasSum += $riesgosPorCadaMilLineas;

            $archivosEscaneados++;
            $vCount++;
            $vuln->guardar($nombreArchivo, "Detectado por GPT", $contenido, $respuesta, $id_zip);

            crearReportePDF($nombreArchivo, $contenido, $respuesta, 'es');
            $respEn = traducirAlIngles($contenido);
            crearReportePDF($nombreArchivo, $contenido, $respEn, 'en');

            $respCorr = callGemini($contenido, 'fix');
            if ($respCorr) $archivosCorregidos[$nombreArchivo] = $respCorr;

            $fileId = 'file' . uniqid();
            $tabs .= "<li class='nav-item'><a class='nav-link' data-bs-toggle='pill' href='#{$fileId}'>{$nombreArchivo}</a></li>";

            $vulnCode = htmlspecialchars($contenido);
            $solCode = str_contains($respuesta, '<div')
                ? $respuesta
                : '<pre class="bg-light p-3">' . htmlspecialchars($respuesta) . '</pre>';

            $base = pathinfo($nombreArchivo, PATHINFO_FILENAME);
            $btnEs = "<a href='reportes/{$base}_es.pdf' class='btn btn-primary me-2' target='_blank'>PDF ES</a>";
            $btnEn = "<a href='reportes/{$base}_en.pdf' class='btn btn-secondary' target='_blank'>PDF EN</a>";
            $downloadButtons = "<div class='mt-2'>{$btnEs}{$btnEn}</div>";

            $panes .= "
            <div class='tab-pane fade' id='{$fileId}'>
                <div class='row'>
                    <div class='col-md-6'>
                        <h6 class='text-uppercase'>Código Original</h6>
                        <pre class='bg-dark text-white p-3 rounded'>{$vulnCode}</pre>
                    </div>
                    <div class='col-md-6'>
                        <h6 class='text-uppercase'>Análisis & Solución</h6>
                        {$solCode}
                        {$downloadButtons}
                    </div>
                </div>
            </div>";
        }
    }

    // Cálculo de promedios
    if ($archivosEscaneados > 0) {
        $porcentajeCodigoEscaneadoPromediado = $porcentajeCodigoEscaneadoSum / $archivosEscaneados;
        $porcentajeIncidenciasErroresPromediado = $porcentajeIncidenciasErroresSum / $archivosEscaneados;
        $tiempoPromedioRemediacionPromediado = $tiempoPromedioRemediacionSum / $archivosEscaneados;
        $nivelMadurezDesarrolloSeguroPromediado = $nivelMadurezDesarrolloSeguroSum / $archivosEscaneados;
        $porcentajeCumplimientoPruebasSeguridadPromediado = $porcentajeCumplimientoPruebasSeguridadSum / $archivosEscaneados;
        $falsosPositivosAnalisisPromediado = $falsosPositivosAnalisisSum / $archivosEscaneados;
        $riesgosPorCadaMilLineasPromediado = $riesgosPorCadaMilLineasSum / $archivosEscaneados;
    } else {
        $porcentajeCodigoEscaneadoPromediado = 0;
        $porcentajeIncidenciasErroresPromediado = 0;
        $tiempoPromedioRemediacionPromediado = 0;
        $nivelMadurezDesarrolloSeguroPromediado = 0;
        $porcentajeCumplimientoPruebasSeguridadPromediado = 0;
        $falsosPositivosAnalisisPromediado = 0;
        $riesgosPorCadaMilLineasPromediado = 0;
    }

if ($vCount > 0) {
    $indicadores = [];
    $indicadores[] = [
        'nombre' => 'Gráfico General',
        'descripcion' => 'Este gráfico muestra el porcentaje general de archivos del sistema que contienen vulnerabilidades detectadas. Sirve como un resumen rápido para saber qué tan expuesto está el proyecto en términos de seguridad.',
        'valor'  => ($vCount / $totalCount) * 100,
        'color'  => [255, 102, 0]
    ];
    $indicadores = array_merge($indicadores, [
        [
            'nombre' => '% de código escaneado con herramientas automáticas',
            'descripcion' => 'Representa el porcentaje de todo el código fuente del proyecto que ha sido revisado usando herramientas automáticas de escaneo de seguridad. Un valor alto indica una mayor cobertura automatizada en la búsqueda de vulnerabilidades.',
            'valor' => $porcentajeCodigoEscaneadoPromediado,
            'color' => [0, 153, 0]
        ],
        [
            'nombre' => '% de incidencias por errores de desarrollo',
            'descripcion' => 'Indica qué parte de las vulnerabilidades detectadas están relacionadas directamente con malas prácticas o errores cometidos durante el desarrollo del software. Un valor elevado puede reflejar la necesidad de mejorar la capacitación del equipo o los procesos de revisión de código.',
            'valor' => $porcentajeIncidenciasErroresPromediado,
            'color' => [255, 102, 0]
        ],
        [
            'nombre' => 'Tiempo promedio de remediación de vulnerabilidades',
            'descripcion' => 'Este valor indica el tiempo promedio que el equipo tarda en corregir una vulnerabilidad desde que se detecta hasta que se soluciona. Cuanto menor sea este tiempo, más eficiente es la respuesta ante riesgos de seguridad.',
            'valor' => $tiempoPromedioRemediacionPromediado,
            'color' => [0, 102, 204]
        ],
        [
            'nombre' => 'Nivel de madurez del desarrollo seguro',
            'descripcion' => 'Refleja qué tan avanzado está el proyecto en la implementación de buenas prácticas de desarrollo seguro. Un nivel alto indica que se siguen procesos formales para prevenir, detectar y corregir problemas de seguridad durante el ciclo de vida del software.',
            'valor' => $nivelMadurezDesarrolloSeguroPromediado,
            'color' => [204, 0, 204]
        ],
        [
            'nombre' => '% de cumplimiento en pruebas de seguridad',
            'descripcion' => 'Indica qué porcentaje de las pruebas de seguridad que deberían realizarse efectivamente se han ejecutado. Un cumplimiento alto es señal de un proceso de calidad y verificación sólido.',
            'valor' => $porcentajeCumplimientoPruebasSeguridadPromediado,
            'color' => [102, 0, 204]
        ],
        [
            'nombre' => 'Falsos positivos en análisis automático',
            'descripcion' => 'Se refiere al número de alertas generadas por las herramientas automáticas que realmente no representan una vulnerabilidad. Un número alto de falsos positivos puede generar pérdida de tiempo y recursos para el equipo de desarrollo.',
            'valor' => $falsosPositivosAnalisisPromediado,
            'color' => [255, 0, 0]
        ],
        [
            'nombre' => 'Riesgos por cada 1000 líneas de código',
            'descripcion' => 'Este indicador estima cuántas vulnerabilidades existen por cada 1000 líneas de código escritas. Ayuda a dimensionar el nivel de exposición del proyecto en relación al tamaño del código fuente.',
            'valor' => $riesgosPorCadaMilLineasPromediado,
            'color' => [0, 204, 204]
        ],
    ]);

    echo "<div class='mt-5'><div class='row'>";

    // Menú vertical
    echo "<div class='col-md-3'>";
    echo "<ul class='nav nav-pills flex-column' id='metricas-tabs' role='tablist'>";
    foreach ($indicadores as $i => $ind) {
        $active  = $i === 0 ? 'active' : '';
        $ariaSel = $i === 0 ? 'true'   : 'false';
        $idTab   = "tab-ind-{$i}";
        echo "
        <li class='nav-item mb-2' role='presentation'>
            <button class='nav-link text-start {$active}' id='{$idTab}-tab'
                data-bs-toggle='pill' data-bs-target='#{$idTab}'
                type='button' role='tab' aria-controls='{$idTab}'
                aria-selected='{$ariaSel}'>
                " . htmlspecialchars($ind['nombre']) . "
            </button>
        </li>";
    }
    echo "</ul></div>";

    // Contenido de las pestañas
    echo "<div class='col-md-9'>";
    echo "<div class='tab-content' id='metricas-tabs-content'>";
    foreach ($indicadores as $i => $ind) {
        $showActive = $i === 0 ? 'show active' : '';
        $idTab      = "tab-ind-{$i}";
        $imgUrl     = generarGraficoIndicador($ind['nombre'], $ind['valor'], $ind['color']);
        echo "
        <div class='tab-pane fade {$showActive}' id='{$idTab}' role='tabpanel' aria-labelledby='{$idTab}-tab'>
            <div class='grafico-card mb-4'>
                <img src='" . htmlspecialchars($imgUrl) . "' alt='" . htmlspecialchars($ind['nombre']) . "'>
                <div class='grafico-overlay'>" . htmlspecialchars($ind['descripcion']) . "</div>
            </div>
        </div>";
    }
    echo "</div></div>"; // cierre col-md-9 y tab-content

    echo "</div>"; // cierre row

    // Archivos vulnerables
    echo "
    <h5 class='mt-4'>Archivos con Vulnerabilidades ({$vCount}/{$totalCount})</h5>
    <ul class='nav nav-pills mb-3'>{$tabs}</ul>
    <div class='tab-content'>{$panes}</div>
    </div>";
} else {
    echo "No se encontraron vulnerabilidades.<br>";
}
    // ZIP corregidos
    if (!empty($archivosCorregidos)) {
        $zipCor = new ZipArchive;
        $zipCorName = 'corregido_' . $zipName;
        $zipCorPath = $uploadDir . $zipCorName;
        if ($zipCor->open($zipCorPath, ZipArchive::CREATE) === TRUE) {
            foreach ($archivosCorregidos as $n => $cont) {
                $zipCor->addFromString($n, $cont);
            }
            $zipCor->close();
            echo "<a href='{$zipCorPath}' class='btn btn-success mt-3'>Descargar ZIP Corregido</a>";
        }
    }

    echo "<br>✅ Procesamiento completo.";


    // Envío de correo al usuario
     if (!empty($_POST['correo_usuario'])) {
        sendEmail($_POST['correo_usuario'],$zipName,$vCount,$totalCount);
    }

} else {
    echo "⚠️ Envía un ZIP válido.<br>";
    if (isset($_FILES['file'])) {
        echo "Tipo: {$_FILES['file']['type']}<br>";
        echo "Nombre: {$_FILES['file']['name']}<br>";
    }
}