<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
    //Verificar si el usuario ha iniciado session

?>
    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

    <head>
        <title>Tolmiboot | Perfil</title>
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
                                    <h4 class="mb-sm-0" data-key="t-mant-perfil">Mantenimiento Perfil</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);" data-key="t-mantenimiento">Mantenimiento</a></li>
                                            <li class="breadcrumb-item active" data-key="t-perfil">Perfil</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="flex-shrink-0">
                                                    <img src="../../assets/images/users/user-dummy-img.jpg" alt="Usuario" class="rounded-circle avatar-lg">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="card-title mb-1" id="usuario_nombre" data-key="t-usuario-nombre">Nombre del Usuario</h5>
                                                    <p class="text-muted mb-0" id="usuario_correo">correo@ejemplo.com</p>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <button type="button" class="btn btn-primary btn-sm" id="btnEditarPerfil">
                                                        <i class="ri-edit-2-line"></i> <span data-key="t-editar-perfil">Editar Perfil</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" data-key="t-nombre">Nombre completo</label>
                                                    <input type="text" class="form-control" id="input_nombre" name="usu_nom" value="<?php echo $_SESSION['usu_nom']; ?>" disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" data-key="t-apellido">Apellido</label>
                                                    <input type="text" class="form-control" id="input_apellido" name="usu_ape" value="<?php echo $_SESSION['usu_ape']; ?>" disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" data-key="t-correo">Correo electrónico</label>
                                                    <input type="email" class="form-control" id="input_correo" name="usu_correo" value="<?php echo $_SESSION['usu_correo']; ?>" disabled>
                                                </div>



                                                <div class="col-md-6">
                                                    <label class="form-label" data-key="t-contrasena">Contraseña</label>
                                                    <input type="text" class="form-control" id="input_password" name="usu_pass" value="<?php echo $_SESSION['usu_pass']; ?>" disabled>
                                                </div>


                                                <div class="col-md-12">
                                                    <div>
                                                        <label for="valueInput" class="form-label">Imagen</label>
                                                        <input type="file" class="form-control" id="usu_img" name="usu_img" />
                                                    </div>
                                                </div>


                                                <br>


                                                <div class="col-md-12">
                                                    <div class="text-center">
                                                        <a id="btnremovephoto" class="btn btn-danger btn-icon waves-effect waves-light btn-sm"><i class="ri-delete-bin-5-line"></i></a>
                                                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                                            <span id="pre_imagen"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="mt-4 d-none" id="guardarPerfil">
                                                <button type="button" class="btn btn-success" id="btnGuardarCambios">
                                                    <i class="ri-save-3-line"></i> Guardar Cambios
                                                </button>
                                                <button type="button" class="btn btn-secondary" id="btnCancelar">
                                                    Cancelar
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <?php require_once("../html/footer.php"); ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const btnEditarPerfil = document.getElementById('btnEditarPerfil');
                        const btnGuardarCambios = document.getElementById('btnGuardarCambios');
                        const btnCancelar = document.getElementById('btnCancelar');
                        const inputs = document.querySelectorAll('input.form-control');
                        const guardarPerfil = document.getElementById('guardarPerfil');
                        const contenedorImagen = document.getElementById('contenedorImagen'); // <-- referencia al contenedor de imagen

                        // Función para habilitar los campos
                        btnEditarPerfil.addEventListener('click', function() {
                            inputs.forEach(input => input.disabled = false);
                            guardarPerfil.classList.remove('d-none');
                            contenedorImagen.classList.remove('d-none'); // <-- Mostrar el input de imagen
                        });

                        // Función para cancelar edición
                        btnCancelar.addEventListener('click', function() {
                            inputs.forEach(input => input.disabled = true);
                            guardarPerfil.classList.add('d-none');
                            contenedorImagen.classList.add('d-none'); // <-- Ocultar nuevamente
                        });

                        // Función para guardar cambios
                        btnGuardarCambios.addEventListener('click', function() {
                            inputs.forEach(input => input.disabled = true);
                            guardarPerfil.classList.add('d-none');
                            contenedorImagen.classList.add('d-none'); // <-- Ocultar luego de guardar

                            Swal.fire({
                                icon: 'success',
                                title: '¡Datos actualizados!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });
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