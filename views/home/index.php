<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
    //Verificar si el usuario ha iniciado session

?>
    <!doctype html>
    <html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

    <head>

        <meta charset="utf-8" />
        <title>Dashboard | Velzon - Admin & Dashboard Template</title>
        <?php require_once("../html/head.php") ?>
        <style>
            .icon-container {
                display: flex;
                justify-content: center;
                /* Centra horizontalmente */
                align-items: center;
            }

            .animated-icon {
                animation: flicker 1.5s infinite alternate, float 3s infinite ease-in-out;
            }

            /* Efecto de parpadeo */
            @keyframes flicker {
                0% {
                    opacity: 0.6;
                }

                100% {
                    opacity: 1;
                }
            }

            /* Efecto de movimiento */
            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }

                100% {
                    transform: translateY(0px);
                }
            }
        </style>
    </head>

    <body>

        <!-- Begin page -->
        <div id="layout-wrapper">

            <?php require_once("../html/header.php") ?>

            <div class="vertical-overlay"></div>
            <?php require_once("../html/menu.php") ?>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <!-- start hero section with login -->
                        <section class="section pb-0 hero-section" id="hero" style="height: 100vh;">
                            <div class="bg-overlay bg-overlay-pattern"></div>
                            <div class="container h-100">
                                <div class="row justify-content-center align-items-center h-100">
                                    <div class="col-lg-8 col-sm-10">
                                        <div class="text-center">
                                            <h1 class="display-6 fw-bold mb-3 lh-base">
                                                <span data-key="t-hola">Hola,</span>
                                                <span class="text-success">
                                                    <?php echo $_SESSION["usu_nom"] . ' ' . $_SESSION["usu_ape"]; ?>
                                                </span>
                                            </h1>
                                            <div class="icon-container mb-4">
                                                <img src="../../assets/icon/icono.png" class="card-logo card-logo-dark animated-icon" alt="logo dark" height="60">
                                            </div>
                                            <p class="lead text-muted lh-base" data-key="t-welcome">Bienvenido de nuevo, tu aliado inteligente para detectar y corregir vulnerabilidades en tu proyecto de desarrollo.</p>
                                            <div class="d-flex gap-3 justify-content-center mt-4">
                                                <a href="../Tomilboot/" class="btn btn-success">
                                                    <span data-key="t-comencemos">Comencemos</span> <i class="ri-arrow-right-line align-middle ms-1"></i>
                                                </a>
                                                <a href="../Perfil/" class="btn btn-warning">
                                                    <span data-key="t-modificar">Modificar Perfil</span> <i class="ri-arrow-right-line align-middle ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-absolute start-0 end-0 bottom-0 hero-shape-svg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
                                    <path d="M 0,118 C 288,98.6 1152,40.4 1440,21L1440 140L0 140z"></path>
                                </svg>
                            </div>
                        </section>



                        <!-- end hero section with login -->


                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


                <?php require_once("../html/footer.php") ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->








    </body>

    </html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>