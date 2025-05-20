<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../../config/conexion.php");
require_once("../../models/Vulnerabilidad.php");
require_once("../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

function crearReportePDF($nombreArchivo, $codigo, $respuesta, $idioma = 'es')
{
    $título = $idioma === 'es' ? 'Reporte de Vulnerabilidad' : 'Vulnerability Report';
    $suffix = $idioma === 'es' ? '_es' : '_en';
    $pdfPath = __DIR__ . "/reportes/" . pathinfo($nombreArchivo, PATHINFO_FILENAME) . $suffix . ".pdf";

    // HTML del PDF
    $html = '
    <html><head><meta charset="UTF-8">
    <style>
      body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
      h1 { font-size: 18px; text-align: center; }
      pre { background: #f4f4f4; padding: 10px; white-space: pre-wrap; word-wrap: break-word; }
      .section { margin-bottom: 20px; }
    </style>
    </head><body>
      <h1>' . $título . '</h1>
      <div class="section">
        <strong>Archivo:</strong> ' . htmlspecialchars($nombreArchivo) . '
      </div>
      <div class="section">
        <strong>' . ($idioma === 'es' ? 'Código Analizado' : 'Code Analyzed') . ':</strong>
        <pre>' . htmlspecialchars($codigo) . '</pre>
      </div>
      <div class="section">
        <strong>' . ($idioma === 'es' ? 'Análisis' : 'Analysis') . ':</strong>
        ' . $respuesta . '
      </div>
    </body></html>';

    // configurar Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // crear carpeta si no existe
    if (!is_dir(__DIR__ . '/reportes')) {
        mkdir(__DIR__ . '/reportes', 0777, true);
    }

    // guardar PDF
    file_put_contents($pdfPath, $dompdf->output());
}

// --- función de traducción mock ---
function traducirAlIngles($texto)
{
    // reemplaza con tu API real si quieres
    return callGemini($texto, 'translate');
}

if (isset($_FILES['file']) && pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) === 'zip') {
    // Subida y extracción
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

    // Inicializar DB y conteos
    $vuln = new Vulnerabilidad();
    $usu_id = isset($_POST["user_idx"]) ? $_POST["user_idx"] : null;
    $id_zip = $vuln->resertar($zipName, $usu_id);
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($extractPath));
    $tabs = '';
    $panes = '';
    $vCount = $totalCount = 0;
    $archivosCorregidos = [];

    // Procesar archivos
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
            $vCount++;
            $vuln->guardar($nombreArchivo, "Detectado por GPT", $contenido, $respuesta, $id_zip);

            // generar PDFs
            crearReportePDF($nombreArchivo, $contenido, $respuesta, 'es');
            $respEn = traducirAlIngles($contenido);
            crearReportePDF($nombreArchivo, $contenido, $respEn, 'en');

            // corregido
            $respCorr = callGemini($contenido, 'fix');
            if ($respCorr) $archivosCorregidos[$nombreArchivo] = $respCorr;

            // pestaña
            $fileId = 'file' . uniqid();
            $tabs .= "<li class='nav-item'><a class='nav-link' data-bs-toggle='pill' href='#{$fileId}'>{$nombreArchivo}</a></li>";

            // preparación de contenido
            $vulnCode = htmlspecialchars($contenido);
            $solCode = str_contains($respuesta, '<div')
                ? $respuesta
                : '<pre class="bg-light p-3">' . htmlspecialchars($respuesta) . '</pre>';

            // botones descarga
            $base = pathinfo($nombreArchivo, PATHINFO_FILENAME);
            $btnEs = "<a href='reportes/{$base}_es.pdf' class='btn btn-primary me-2' target='_blank'>PDF ES</a>";
            $btnEn = "<a href='reportes/{$base}_en.pdf' class='btn btn-secondary' target='_blank'>PDF EN</a>";
            $downloadButtons = "<div class='mt-2'>{$btnEs}{$btnEn}</div>";

            // comparación lado a lado
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

    if ($vCount > 0) {
        $barUrl = generarGraficoBarrasVulnerabilidad($vCount, $totalCount);
        echo "
    <div class='mt-5'>
      <h5>Gráfico General</h5>
  <div class='row'>
    <div class='col-md-12 text-center'>
      <img src='{$barUrl}' class='img-fluid' alt='Gráfico de barras'>
    </div>
  </div>

      <h5 class='mt-4'>Archivos con Vulnerabilidades ({$vCount}/{$totalCount})</h5>
      <ul class='nav nav-pills mb-3'>{$tabs}</ul>
      <div class='tab-content'>{$panes}</div>
    </div>
    ";
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
} else {
    echo "⚠️ Envía un ZIP válido.<br>";
    if (isset($_FILES['file'])) {
        echo "Tipo: {$_FILES['file']['type']}<br>";
        echo "Nombre: {$_FILES['file']['name']}<br>";
    }
}






/**
 * Llama a Gemini con diferentes modos: analizar, traducir o corregir código.
 *
 * @param string $codigo Código fuente a procesar
 * @param string $modo   'analyze' | 'translate' | 'fix'
 * @return string        Respuesta de Gemini
 */
function callGemini(string $codigo, string $modo): string
{
    $apikey = 'AIzaSyDcu_fXx87K1-1CEIvI7XJoSRIwkmvFj6U';
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apikey;

    $prompts = [
        'analyze' => "Por favor, analiza el siguiente código en busca de vulnerabilidades de seguridad e identifica también las métricas relevantes de calidad del código (como complejidad, duplicación, legibilidad o acoplamiento). Devuélveme la respuesta ya formateada en fragmento HTML usando tarjetas de Bootstrap. 

Usa <div class='card border-success'> para la sección 'Solución Propuesta'. No envuelvas en etiquetas <html> ni <body>, solo el fragmento interno. 

Indica tipo de vulnerabilidad, línea aproximada, cómo mitigarla y, si aplica, cómo mejorar las métricas señaladas. Sé claro, ordenado y profesional,por fafor resalta la linea que tiene el error del codigo etc.

Código:\n\n",

        'translate' => "Please analyze the following code for potential security vulnerabilities and also identify relevant code quality metrics (such as complexity, duplication, readability, or coupling). Return the response formatted as an HTML snippet using Bootstrap cards.

Use <div class='card border-success'> for the section titled 'Proposed Solution'. Do not wrap in <html> or <body> tags, just the inner fragment.

Indicate the type of vulnerability, approximate line, how to mitigate it, and if applicable, how to improve the mentioned metrics. Be clear, organized, and professional.

Code:\n\n",

        'fix' => "Analiza el siguiente código y devuélveme solo el código con las soluciones aplicadas. No añadas ningún texto explicativo ni comentarios, solo el código corregido: ",
    ];

    if (!isset($prompts[$modo])) {
        return "⚠️ Modo inválido. Usa: 'analyze', 'translate' o 'fix'.";
    }

    $data = [
        'contents' => [[
            'parts' => [[
                'text' => $prompts[$modo] . $codigo
            ]]
        ]]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($data),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['candidates'][0]['content']['parts'][0]['text']
        ?? "⚠️ No se pudo obtener respuesta de Gemini.\n" . json_encode($result);
}



function generarGraficoBarrasVulnerabilidad($vulnerabilidadesDetectadas, $totalArchivos)
{
    // Crear imagen
    $image = imagecreatetruecolor(400, 300);
    $bgColor = imagecolorallocate($image, 255, 255, 255); // Fondo blanco
    imagefill($image, 0, 0, $bgColor);

    // Colores
    $colorVulnerabilidades = imagecolorallocate($image, 255, 0, 0); // Rojo
    $colorNoVulnerabilidades = imagecolorallocate($image, 0, 255, 0); // Verde

    // Calcular las alturas de las barras
    $alturaVulnerabilidades = (int)(($vulnerabilidadesDetectadas / $totalArchivos) * 250); // 250 es la altura máxima de la barra
    $alturaNoVulnerabilidades = (int)(($totalArchivos - $vulnerabilidadesDetectadas) / $totalArchivos * 250);

    // Dibujar las barras
    imagefilledrectangle($image, 50, 250 - $alturaVulnerabilidades, 150, 250, $colorVulnerabilidades); // Barra de vulnerabilidades
    imagefilledrectangle($image, 200, 250 - $alturaNoVulnerabilidades, 300, 250, $colorNoVulnerabilidades); // Barra de no vulnerabilidades

    // Agregar texto
    imagestring($image, 5, 70, 260 - $alturaVulnerabilidades, "Vulnerabilidades", imagecolorallocate($image, 0, 0, 0));
    imagestring($image, 5, 220, 260 - $alturaNoVulnerabilidades, "No Vulnerabilidades", imagecolorallocate($image, 0, 0, 0));

    // Texto total
    $texto = "Vulnerabilidades: $vulnerabilidadesDetectadas/$totalArchivos";
    imagestring($image, 5, 130, 10, $texto, imagecolorallocate($image, 0, 0, 0));

    // Guardar gráfico
    $directorio = 'graphics/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $graficoUrl = $directorio . 'grafico_barras_vulnerabilidad_' . uniqid() . '.png';
    imagepng($image, $graficoUrl);
    imagedestroy($image);

    return $graficoUrl;
}
