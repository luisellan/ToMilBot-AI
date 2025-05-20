<?php
require_once("../../config/conexion.php");
require_once("../../models/Historial.php");
if (isset($_SESSION["usu_id"])) {
    // Verificar si el usuario ha iniciado sesión
    $usu_id = intval($_SESSION['usu_id']);
?>
    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

    <head>
        <title>AnderCode | Historial</title>
        <?php require_once("../html/head.php"); ?>
    </head>

    <body>

        <div id="layout-wrapper">

            <?php require_once("../html/header.php"); ?>
            <?php require_once("../html/menu.php"); ?>

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <?php
                        if (!empty($_GET['id_zip'])) {
                            $zip_id = intval($_GET['id_zip']);
                            $model = new Historial();
                            $vulnerabilidades = $model->obtener($zip_id, $usu_id);

                            echo "<h2 class='text-center mb-4'>Vulnerabilidades para el ZIP ID: {$zip_id}</h2>";

                            if (!empty($vulnerabilidades)) {
                                // Generar tabs
                                echo '<ul class="nav nav-pills mb-3" id="vulnerabilityTabs" role="tablist">';
                                foreach ($vulnerabilidades as $i => $v) {
                                    $active = $i === 0 ? 'active' : '';
                                    echo "<li class='nav-item' role='presentation'>
                          <button class='nav-link {$active}' id='tab{$i}-tab' data-bs-toggle='pill' data-bs-target='#tab{$i}' type='button' role='tab' aria-controls='tab{$i}' aria-selected='" . ($i === 0 ? 'true' : 'false') . "'>" . htmlspecialchars($v['archivo']) . "</button>
                        </li>";
                                }
                                echo '</ul>';

                                // Generar contenido de cada tab
                                echo '<div class="tab-content" id="vulnerabilityTabsContent">';
                                foreach ($vulnerabilidades as $i => $v) {
                                    $show = $i === 0 ? 'show active' : '';
                                    echo "<div class='tab-pane fade {$show}' id='tab{$i}' role='tabpanel' aria-labelledby='tab{$i}-tab'>
                          <div class='card mb-3'>
                            <div class='card-header'><h5>" . htmlspecialchars($v['archivo']) . "</h5></div>
                            <div class='card-body'>
                              <p><strong>Tipo:</strong> " . htmlspecialchars($v['tipo_vulnerabilidad']) . "</p>
                              <h6>Código Vulnerable</h6>
                              <pre class='bg-dark text-white p-2'>" . htmlspecialchars($v['codigo_vulnerable']) . "</pre>
                              <h6>Solución Propuesta</h6>
                              <div class='bg-light p-3' style='white-space: pre-wrap;'>" . $v['solucion_propuesta'] . "</div>
                            </div>
                          </div>
                        </div>";
                                }
                                echo '</div>';
                            } else {
                                echo "<div class='alert alert-warning' role='alert'>No se encontraron vulnerabilidades para este ZIP.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>No se ha proporcionado un ID de ZIP válido.</div>";
                        }
                        ?>
                    </div>
                </div>

                <?php require_once("../html/footer.php"); ?>

            </div>

        </div>

        <!-- Scripts de Bootstrap (si es necesario) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>