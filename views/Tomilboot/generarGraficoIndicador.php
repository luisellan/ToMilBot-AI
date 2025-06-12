<?php

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

?>