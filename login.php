<?php
require_once("config/conexion.php");
if (isset($_POST["enviar"]) and $_POST["enviar"] == "si") {
    # code...
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->login();
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>


    <title>Login Ingreso | Tomilboot</title>
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
                                    <h5 class="text-primary">¡Bienvenido!</h5>
                                    <p class="text-muted" id="lbltitulo">Acceso Usuario</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="sign-box" action="" method="post" id="login_form">
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
                                            <label for="usu_correo" class="form-label">Correo</label>
                                            <input type="text" class="form-control" id="usu_correo"
                                                name="usu_correo" placeholder="Ingresa tu Correo">
                                        </div>
                                        <div class="mb-3">
                                            <div class="float-end">
                                                <a href="reset-password.php" class="text-muted">¿Olvidaste tu contraseña?</a>
                                            </div>
                                            <label class="form-label" for="usu_pass">Contraseña</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" id="usu_pass"
                                                    name="usu_pass" class="form-control pe-5" placeholder="Ingresa tu contraseña" id="password-input">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <a href="reset-password.php">Restaurar Contraseña</a>
                                                <a href="#" id="btnSoporte">Acceso Soporte</a>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <input type="hidden" name="enviar" value="si" class="form-control">
                                            <button class="btn btn-success w-100" type="submit">Iniciar Sesión</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="mb-0">¿No tienes una cuenta? <a href="register.php" class="fw-semibold text-primary text-decoration-underline"> Regístrate aquí </a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Footer -->
        <footer class="custom-footer bg-dark py-5 position-relative">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <!-- Logo -->
                    <div class="col-lg-4 col-md-6 text-center text-md-start mb-4 mb-md-0">
                        <img src="assets/icon/icono.png" alt="logo light" height="50">
                    </div>

                    <!-- Redes Sociales -->
                    <div class="col-lg-4 col-md-6 text-center">
                        <ul class="list-inline mb-0 footer-social-link">
                            <li class="list-inline-item">
                                <a href="#" class="avatar-xs d-block">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-facebook-fill"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="avatar-xs d-block">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-github-fill"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="avatar-xs d-block">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-linkedin-fill"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="avatar-xs d-block">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-google-fill"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="avatar-xs d-block">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-dribbble-line"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Copyright -->
                    <div class="col-lg-4 text-center text-lg-end">
                        <p class="copy-rights mb-0">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Tomilboot - AI
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->


    </div>
    <!-- end layout wrapper -->


    <!-- JAVASCRIPT: Primero se carga jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Luego se incluyen las demás librerías de Bootstrap y plugins -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- Swiper slider js -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Landing init -->
    <script src="assets/js/pages/landing.init.js"></script>

    <!-- Por último, tu script personalizado -->
    <script src="login.js"></script>

</body>

</html>