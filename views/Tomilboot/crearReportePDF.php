<?php
use Dompdf\Dompdf;
use Dompdf\Options;

require_once("../../config/conexion.php");
require_once("../../models/Vulnerabilidad.php");
require_once("../../vendor/autoload.php");
require_once("../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once("../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("../../vendor/phpmailer/phpmailer/src/SMTP.php");

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
?>