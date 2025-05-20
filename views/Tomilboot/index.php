<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

    <head>
        <meta charset="utf-8" />
        <title>Dashboard | Plataforma Tomilboot</title>
        <?php require_once("../html/head.php") ?>
    </head>

    <body>

        <div id="layout-wrapper">

            <?php require_once("../html/header.php") ?>
            <div class="vertical-overlay"></div>
            <?php require_once("../html/menu.php") ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row align-items-center gy-4">
                            <div class="col-lg-12 order-2 order-lg-1">
                                <div class="text-muted">
                                    <h5 class="fs-12 text-uppercase text-success" data-key="t-generador">Generador de Soluciones</h5>
                                    <h4 class="mb-3" data-key="t-titulo">Tomilboot - Plataforma de Gestión</h4>
                                    <p class="mb-4 ff-secondary" data-key="t-descripcion">
                                        Tomilboot es una plataforma web para la carga, gestión y seguimiento de documentos, donde los usuarios pueden subir archivos, revisarlos y obtener informes específicos relacionados con su actividad.
                                    </p>

                                    <div id="resultadoDesglose"></div>

                                    <!-- Formulario para cargar ZIP -->
                                    <div class="mt-4">
                                        <h5 class="fs-12 text-uppercase text-success" data-key="t-cargarzip">Cargar ZIP</h5>
                                        <form id="uploadForm" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" id="user_idx" name="user_idx" value="<?php echo $_SESSION["usu_id"]; ?>">
                                            <div class="mb-3">
                                                <label for="file" class="form-label" data-key="t-label-zip">Seleccionar archivo ZIP</label>
                                                <input type="file" class="form-control" id="file" name="file" accept=".zip" required>
                                            </div>
                                            <button type="submit" class="btn btn-success">
                                                <span data-key="t-btn-zip">Cargar ZIP</span>
                                            </button>
                                        </form>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal para mostrar carga -->
                <!-- Modal de Carga -->
                <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="loadingModalLabel">Cargando archivo...</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <p class="mt-2" id="statusText">Esperando...</p>
                            </div>
                        </div>
                    </div>
                </div>



                <?php require_once("../html/footer.php") ?>
                <script>
                    document.getElementById('uploadForm').addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Mostrar el modal de carga
                        $('#loadingModal').modal('show');

                        const formData = new FormData(this);

                        fetch('upload.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(res => res.text())
                            .then(html => {
                                // Procesar la respuesta después de la carga
                                document.getElementById('resultadoDesglose').innerHTML = html;

                                // Ocultar el modal cuando termine el proceso
                                $('#loadingModal').modal('hide');
                            })
                            .catch(err => {
                                console.error("Error en la carga:", err);
                                document.getElementById('statusText').innerText = "Error al cargar el archivo";
                            });

                        // Simulación de progreso (esto es solo para fines visuales)
                        let progress = 0;
                        let progressBar = document.getElementById('progressBar');
                        let statusText = document.getElementById('statusText');

                        let interval = setInterval(function() {
                            if (progress < 100) {
                                progress += 10;
                                progressBar.style.width = progress + '%';
                                statusText.innerText = 'Cargando... ' + progress + '%';
                            } else {
                                clearInterval(interval);
                            }
                        }, 500); // Simula progreso en intervalos de medio segundo
                    });
                </script>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>