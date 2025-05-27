<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../../config/conexion.php");
require_once("../../models/Vulnerabilidad.php");
require_once("../../vendor/autoload.php");
require_once("../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once("../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("../../vendor/phpmailer/phpmailer/src/SMTP.php");



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
        // 1) Arreglo de indicadores con Gráfico General al inicio
        $indicadores = [];
        $indicadores[] = [
            'nombre' => 'Gráfico General',
            'valor'  => ($vCount / $totalCount) * 100,
            'color'  => [255, 102, 0]
        ];
        $indicadores = array_merge($indicadores, [
            ['nombre' => '% de código escaneado con herramientas automáticas', 'valor' => (7500 / 10000) * 100, 'color' => [0, 153, 0]],
            ['nombre' => '% de incidencias por errores de desarrollo', 'valor' => (5 / 20) * 100, 'color' => [255, 102, 0]],
            ['nombre' => 'Tiempo promedio de remediación de vulnerabilidades', 'valor' => (5.2 / 10) * 100, 'color' => [0, 102, 204]],
            ['nombre' => 'Nivel de madurez del desarrollo seguro', 'valor' => (3 / 5) * 100, 'color' => [204, 0, 204]],
            ['nombre' => '% de cumplimiento en pruebas de seguridad', 'valor' => (18 / 20) * 100, 'color' => [102, 0, 204]],
            ['nombre' => 'Falsos positivos en análisis automático', 'valor' => (2 / 20) * 100, 'color' => [255, 0, 0]],
            ['nombre' => 'Riesgos por cada 1000 líneas de código', 'valor' => (5 / 1000) * 100, 'color' => [0, 204, 204]],
            ['nombre' => '% de accesos no autorizados a repositorios', 'valor' => (3 / 100) * 100, 'color' => [204, 0, 102]],
        ]);

        echo "<div class='mt-5'><div class='row'>";

        // Menú vertical (derecha)
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

        // Contenido de las pestañas (izquierda)
        echo "<div class='col-md-9'>";
        echo "<div class='tab-content' id='metricas-tabs-content'>";
        foreach ($indicadores as $i => $ind) {
            $showActive = $i === 0 ? 'show active' : '';
            $idTab      = "tab-ind-{$i}";
            $imgUrl     = generarGraficoIndicador($ind['nombre'], $ind['valor'], $ind['color']);
            echo "
        <div class='tab-pane fade {$showActive}' id='{$idTab}' role='tabpanel' aria-labelledby='{$idTab}-tab'>
          <div class='text-center'>
            <img src='" . htmlspecialchars($imgUrl) . "' class='img-fluid' alt='" . htmlspecialchars($ind['nombre']) . "'>
          </div>
        </div>";
        }
        echo "</div></div>"; // cierre col-md-9 y tab-content

        echo "</div>"; // cierre row
        // finalmente tus tabs de archivos vulnerables
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
    $correoUsuario = $_POST['correo_usuario'] ?? null;

    if ($correoUsuario) {
        $mail = new PHPMailer(true);
        try {
            // Configuración SMTP correcta para Mailtrap
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = '7631591b79bef9'; // Usuario Mailtrap
            $mail->Password   = 'f3831481a54de1'; // Contraseña Mailtrap
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;

            $mail->setFrom('hello@demomailtrap.co', 'Analizador de Vulnerabilidades');
            $mail->addAddress($correoUsuario);

            $mail->isHTML(true);
            $mail->Subject = 'Reporte de Análisis de Vulnerabilidades';
            $mail->Body    = "
            <h4>¡Hola!</h4>
            <p>Tu archivo <strong>{$zipName}</strong> ha sido procesado correctamente.</p>
            <p>Se detectaron <strong>{$vCount}</strong> vulnerabilidades de un total de <strong>{$totalCount}</strong> archivos.</p>
            <p>Puedes descargar el ZIP corregido o los reportes PDF desde la plataforma.</p>
            <br><p>Gracias por usar nuestro sistema.</p>
        ";
            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();
            echo "<p>📧 Correo enviado a <strong>{$correoUsuario}</strong>.</p>";
        } catch (Exception $e) {
            echo "<p>❌ Error al enviar el correo: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>⚠️ No se recibió un correo para notificar.</p>";
    }
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



function generarGraficoIndicador($nombreIndicador, $valorPorcentaje, $colorBarra = [0, 102, 204])
{
    $ancho = 600;
    $alto  = 350;
    $image = imagecreatetruecolor($ancho, $alto);

    // Colores
    $bgColor             = imagecolorallocate($image, 255, 255, 255);
    $colorTexto          = imagecolorallocate($image,   0,   0,   0);
    $colorBarraPrincipal = imagecolorallocate($image, $colorBarra[0], $colorBarra[1], $colorBarra[2]);
    $colorBarraFondo     = imagecolorallocate($image, 230, 230, 230);
    $colorLinea          = imagecolorallocate($image, 180, 180, 180);

    imagefill($image, 0, 0, $bgColor);

    // Título centrado
    $xTitulo = (int)(($ancho - imagefontwidth(5) * strlen($nombreIndicador)) / 2);
    imagestring($image, 5, $xTitulo, 20, $nombreIndicador, $colorTexto);

    // Parámetros de la barra
    $x0        = 80;
    $y0        = 150;
    $wTotal    = 440;
    $h         = 50;
    $wBarra    = (int)(($valorPorcentaje / 100) * $wTotal);  // cast aquí

    // Fondo de barra (todo casteado)
    imagefilledrectangle(
        $image,
        (int)$x0,
        (int)$y0,
        (int)($x0 + $wTotal),
        (int)($y0 + $h),
        $colorBarraFondo
    );

    // Barra principal
    imagefilledrectangle(
        $image,
        (int)$x0,
        (int)$y0,
        (int)($x0 + $wBarra),
        (int)($y0 + $h),
        $colorBarraPrincipal
    );

    // Borde de la barra
    imagerectangle(
        $image,
        (int)$x0,
        (int)$y0,
        (int)($x0 + $wTotal),
        (int)($y0 + $h),
        $colorLinea
    );

    // Valor porcentual
    $val     = round($valorPorcentaje, 2) . '%';
    $xVal    = (int)($x0 + ($wBarra / 2) - (imagefontwidth(5) * strlen($val) / 2));
    $yVal    = (int)($y0 + ($h / 2) - (imagefontheight(5) / 2));

    if ($wBarra > imagefontwidth(5) * strlen($val) + 10) {
        imagestring(
            $image,
            5,
            $xVal,
            $yVal,
            $val,
            imagecolorallocate($image, 255, 255, 255)
        );
    } else {
        imagestring(
            $image,
            5,
            (int)($x0 + $wBarra + 10),
            $yVal,
            $val,
            $colorTexto
        );
    }

    // Leyenda inferior
    $ley       = "Valor exacto: " . $val;
    $xLeyenda  = (int)(($ancho - imagefontwidth(3) * strlen($ley)) / 2);
    $yLeyenda  = $alto - 30;
    imagestring($image, 3, $xLeyenda, $yLeyenda, $ley, $colorTexto);

    // Guardar imagen
    $dir = 'graphics/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $file = $dir
        . 'indicador_'
        . preg_replace('/[^a-zA-Z0-9]/', '_', $nombreIndicador)
        . '_'
        . uniqid()
        . '.png';

    imagepng($image, $file);
    imagedestroy($image);

    return $file;
}
