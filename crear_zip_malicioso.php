<?php
$zip = new ZipArchive();
$filename = "malicioso.zip";

if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("No se pudo crear el archivo ZIP\n");
}

// ⚠️ Archivos simulados peligrosos
$zip->addFromString("malicioso.php", "<?php echo 'ataque'; ?>");
$zip->addFromString("inocente.txt", "Esto es un archivo normal");
$zip->addFromString("script.js.php", "<?php echo 'doble extension'; ?>");
$zip->addFromString("../outside.php", "<?php echo 'traversal'; ?>");
$zip->addFromString("virus.exe", "simulación de ejecutable");

$zip->close();

echo "✅ ZIP malicioso generado correctamente: $filename\n";
