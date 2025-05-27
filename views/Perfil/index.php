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
                                                    <img src="../../assets/images/users/<?php echo $_SESSION['usu_img']; ?>" alt="Usuario" class="rounded-circle avatar-lg">
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

                                            <form id="formPerfil" enctype="multipart/form-data">
                                                <div class="row g-3">
                                                    <!-- Campo oculto para ID de usuario -->
                                                    <input type="hidden" id="usu_id" name="usu_id" value="<?php echo $_SESSION['usu_id']; ?>">

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

                                                    <div class="col-md-12 d-none" id="contenedorImagen">
                                                        <label for="usu_img" class="form-label">Imagen</label>
                                                        <input type="file" class="form-control" id="usu_img" name="usu_img" />
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="text-center">
                                                            <a id="btnremovephoto" class="btn btn-danger btn-icon waves-effect waves-light btn-sm">
                                                                <i class="ri-delete-bin-5-line"></i>
                                                            </a>
                                                            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
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
                                            </form>

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
                        const contenedorImagen = document.getElementById('contenedorImagen');

                        btnEditarPerfil.addEventListener('click', function() {
                            inputs.forEach(input => input.disabled = false);
                            guardarPerfil.classList.remove('d-none');
                            contenedorImagen.classList.remove('d-none');
                        });

                        btnCancelar.addEventListener('click', function() {
                            inputs.forEach(input => input.disabled = true);
                            guardarPerfil.classList.add('d-none');
                            contenedorImagen.classList.add('d-none');
                        });

                        btnGuardarCambios.addEventListener('click', function() {
                            const formData = new FormData();
                            formData.append('usu_id', document.getElementById('usu_id').value);
                            formData.append('usu_nom', document.getElementById('input_nombre').value);
                            formData.append('usu_ape', document.getElementById('input_apellido').value);
                            formData.append('usu_correo', document.getElementById('input_correo').value);


                            const fileInput = document.getElementById('usu_img');
                            if (fileInput.files.length > 0) {
                                formData.append('usu_img', fileInput.files[0]);
                            }

                            fetch('../../controller/usuario.php?op=guardaryeditar', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.text())
                                .then(data => {
                                    inputs.forEach(input => input.disabled = true);
                                    guardarPerfil.classList.add('d-none');
                                    contenedorImagen.classList.add('d-none');

                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Datos actualizados!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error al guardar cambios',
                                        text: 'Intente nuevamente',
                                    });
                                });
                        });


                        function filePreview(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $("#pre_imagen").html(
                                        "<img src=" +
                                        e.target.result +
                                        ' class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img>'
                                    );
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $(document).on("change", "#usu_img", function() {
                            filePreview(this);
                        });

                        $(document).on("click", "#btnremovephoto", function() {
                            $("#usu_img").val("");
                            $("#pre_imagen").html(
                                '<img src="../../assets/images/users/user-dummy-img.jpg" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img><input type="hidden" name="hidden_usuario_imagen" value="" />'
                            );
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