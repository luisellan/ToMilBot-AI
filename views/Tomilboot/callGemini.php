<?php
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
        'porcentajeCodigoEscaneado' => "Analiza el siguiente código y devuélveme únicamente un número entero entre 0 y 100 que represente el porcentaje de código que ha sido correctamente escaneado para posibles vulnerabilidades. Solo responde con el número, sin texto adicional.\n\nCódigo:\n\n",

    'porcentajeIncidenciasErrores' => "Analiza el siguiente código y devuelve únicamente un número entero entre 0 y 100 que represente el porcentaje de incidencias o errores detectados en comparación con el tamaño total del código. Solo responde con el número, sin texto adicional.\n\nCódigo:\n\n",

    'tiempoPromedioRemediacion' => "Analiza el siguiente código y devuelve solo un número entero entre 0 y 100, donde 0 es remediación muy lenta y 100 es remediación inmediata. Representa el tiempo promedio estimado para solucionar las vulnerabilidades encontradas. Solo responde con el número, sin texto adicional.\n\nCódigo:\n\n",

    'nivelMadurezDesarrolloSeguro' => "Analiza el siguiente código y devuelve únicamente un número entero entre 0 y 100 que represente el nivel de madurez en prácticas de desarrollo seguro. Solo responde con el número, sin explicaciones ni texto adicional.\n\nCódigo:\n\n",

    'porcentajeCumplimientoPruebasSeguridad' => "Analiza el siguiente código y devuelve solo un número entero entre 0 y 100 que represente el porcentaje estimado de cumplimiento con pruebas de seguridad aplicadas. Solo responde con el número, sin explicaciones.\n\nCódigo:\n\n",

    'falsosPositivosAnalisis' => "Analiza el siguiente código y devuelve un número entero entre 0 y 100 que represente el porcentaje estimado de falsos positivos en el análisis de seguridad. Solo responde con el número, sin texto adicional.\n\nCódigo:\n\n",

    'riesgosPorCadaMilLineas' => "Analiza el siguiente código y devuelve un número entero entre 0 y 100, donde 0 representa bajo riesgo y 100 representa un alto riesgo, como índice de riesgos por cada mil líneas de código. Solo responde con el número, sin comentarios ni explicaciones.\n\nCódigo:\n\n",
    
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



// --- función de traducción mock ---
function traducirAlIngles($texto)
{
    // reemplaza con tu API real si quieres
    return callGemini($texto, 'translate');
}


?>