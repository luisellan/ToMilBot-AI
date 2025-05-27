<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
    //Verificar si el usuario ha iniciado session

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
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0"  data-key="t-mant-history">Mantenimiento Historial</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);" data-key="t-mantenimiento">Mantenimiento</a></li>
                                            <li class="breadcrumb-item active" data-key="t-historial">Historial</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nombre de Archivo</th>
                                                    <th>Zip Cargado</th>
                                                    <th>Zip Corregido</th>
                                                    <th>Fecha de Subida</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <?php require_once("../html/footer.php"); ?>
                <script type="text/javascript" src="mnthistorial.js"></script>
            </div>

        </div>





    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>