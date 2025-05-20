<?php
require_once("config/conexion.php");
if (isset($_POST["enviar"]) and $_POST["enviar"] == "si") {
    # code...
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->register();
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>

    <title>Registro | Tomilboot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/icon/icono.png">

    <!--Swiper slider css-->
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- espacio del footer -->

    <link href="assets/css/css.css" rel="stylesheet" type="text/css" />


</head>

<body>


    <div class="auth-page-wrapper pt-5">
        <!-- Fondo de la página de autenticación -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- Contenido de la página de autenticación -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <h1 class="display-6 fw-bold mb-3 lh-base text-white">Hola, soy <span class="text-success">Tomilboot</span></h1>
                            <div class="icon-container mb-4">
                                <img src="assets/icon/icono.png" class="card-logo card-logo-dark animated-icon" alt="Logo Tomilboot" height="60">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">¡Registrarse!</h5>
                                    <p class="text-muted">Por favor, Ingresa tu datos para crear una cuenta en Tomilboot.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="sign-box" action="" method="post" id="register_form">
                                        <input type="hidden" name="rol_id" id="rol_id" value="1">


                                        <?php
                                        if (isset($_GET["m"])) {
                                            $mensaje = "";
                                            switch ($_GET["m"]) {
                                                case 1:
                                                    $mensaje = "El usuario o contraseña son incorrectos";
                                                    break;
                                                case 2:
                                                    $mensaje = "Los campos están vacíos";
                                                    break;
                                            }

                                            if ($mensaje !== "") {
                                        ?>
                                                <!-- Alerta con cierre manual -->
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="miAlerta">
                                                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i></strong> <?= $mensaje ?>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>

                                                <!-- Script nativo para ocultar después de 5 segundos -->
                                                <script>
                                                    setTimeout(() => {
                                                        const alerta = document.getElementById('miAlerta');
                                                        if (alerta) {
                                                            alerta.classList.remove('show');
                                                            alerta.classList.add('fade');
                                                            setTimeout(() => alerta.remove(), 500); // se elimina del DOM
                                                        }
                                                    }, 5000);
                                                </script>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <div class="mb-3">
                                            <label for="usu_nom" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="usu_nom" name="usu_nom" placeholder="Ingresa tu Nombre">
                                        </div>
                                        <div class="mb-3">
                                            <label for="usu_ape" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" id="usu_ape" name="usu_ape" placeholder="Ingresa tu Apellido">
                                        </div>
                                        <div class="mb-3">
                                            <label for="usu_correo" class="form-label">Correo</label>
                                            <input type="text" class="form-control" id="usu_correo"
                                                name="usu_correo" placeholder="Ingresa tu Correo">
                                        </div>
                                        <div class="mb-3">
                                            
                                            <label class="form-label" for="usu_pass">Contraseña</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" id="usu_pass"
                                                    name="usu_pass" class="form-control pe-5" placeholder="Ingresa tu contraseña" id="password-input">

                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <input type="hidden" name="enviar" value="si" class="form-control">
                                            <button class="btn btn-success w-100" type="submit">Registarse</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © Tomilboot - AI
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>



    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="../../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/libs/simplebar/simplebar.min.js"></script>
    <script src="../../assets/libs/node-waves/waves.min.js"></script>
    <script src="../../assets/libs/feather-icons/feather.min.js"></script>
    <script src="../../assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="../../assets/js/plugins.js"></script>

    <!-- particles js -->
    <script src="../../assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="../../assets/js/pages/particles.app.js"></script>
    <!-- password-addon init -->
    <script src="../../assets/js/pages/password-addon.init.js"></script>
</body>

</html>