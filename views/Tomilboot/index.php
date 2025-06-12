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

                    <!-- Título y descripción -->
                    <div class="row align-items-center gy-4">
                        <div class="col-lg-12">
                            <h5 class="fs-12 text-uppercase text-success" data-key="t-generador">Generador de Soluciones</h5>
                            <h4 class="mb-3" data-key="t-titulo">Tomilboot - Plataforma de Gestión</h4>
                            <p class="mb-4 ff-secondary" data-key="t-descripcion">
                                Tomilboot es una plataforma web para la carga, gestión y seguimiento de documentos...
                            </p>
                            <div id="resultadoDesglose"></div>
                        </div>
                    </div>

                    <!-- Formulario para cargar ZIP -->
                    <div class="mt-4">
                        <h5 class="fs-12 text-uppercase text-success" data-key="t-cargarzip">Cargar ZIP</h5>
                        <form id="uploadForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="user_idx"   name="user_idx"    value="<?php echo $_SESSION["usu_id"]; ?>">
                            <input type="hidden" id="user_nom"   name="user_nom"    value="<?php echo $_SESSION["usu_nom"]; ?>">
                            <input type="hidden" id="user_ape"   name="user_ape"    value="<?php echo $_SESSION["usu_ape"]; ?>">
                            <input type="hidden" id="user_email" name="correo_usuario" value="<?php echo $_SESSION["usu_correo"]; ?>">

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

        <!-- Modal de Carga Mejorado -->
        <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="loadingModalLabel">Cargando archivo...</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Barra de progreso -->
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 id="progressBar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <!-- Estado -->
                        <p class="text-muted mb-2" id="statusText">Esperando...</p>
                        <!-- Información dinámica -->
                        <div class="border p-3 rounded bg-light">
  <div id="idUsuario" class="mb-3 p-2 bg-primary text-white rounded">
    ID Usuario: 123
  </div>
  <p><strong>Archivo:</strong> <span id="archivoNombre">–</span></p>
  <p><strong>Nombre:</strong> <span id="nombreUsuario">Juan</span></p>
  <p><strong>Apellido:</strong> <span id="apellidoUsuario">–</span></p>
  <p><strong>Correo:</strong> <span id="idCorreo">–</span></p>
</div>
                        <!-- Proceso -->
                        <div class="mt-3">
                            <ul id="procesoList" class="list-group"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once("../html/footer.php") ?>

        <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Obtener datos
    const fileInput    = document.getElementById('file');
    const archivo      = fileInput.files[0];
    const archivoName  = archivo?.name || 'No seleccionado';
    const nombreUser   = document.getElementById('user_nom').value || 'Nombre no definido';
    const apellidoUser = document.getElementById('user_ape').value || 'Apellido no definido';
    const idUser       = document.getElementById('user_idx').value || 'Id Usuario no definido';
    const idCorreo     = document.getElementById('user_email').value || 'Correo no Identificado'
    // Mostrar modal y resetear UI
    document.getElementById('archivoNombre').innerText   = archivoName;
    document.getElementById('nombreUsuario').innerText   = nombreUser;
    document.getElementById('apellidoUsuario').innerText = apellidoUser;
    document.getElementById('idUsuario').innerText       = idUser;
    document.getElementById('idCorreo').innerText       = idCorreo;

    $('#loadingModal').modal('show');
    const procesoList = document.getElementById('procesoList');
    procesoList.innerHTML = '';  // Limpiar

    // Función para agregar un <li> con retraso
    async function addListItem(html, delay = 600) {
        return new Promise(resolve => {
            setTimeout(() => {
                procesoList.innerHTML += html;
                resolve();
            }, delay);
        });
    }

    await addListItem(`<li class="list-group-item">📁 Archivo <strong>${archivoName}</strong> – Inicio de proceso: Extraer ZIP</li>`, 800);

    const progressBar = document.getElementById('progressBar');
    const statusText  = document.getElementById('statusText');
    progressBar.style.width = '0%';
    statusText.innerText = 'Iniciando...';

    try {
        const zip = await JSZip.loadAsync(archivo);
        const files = Object.keys(zip.files);
        const totalFiles = files.length;
        let progress = 0;

        // Tiempo total deseado en ms, aquí 2 minutos = 120000 ms
        // Ajustar a 1:30 (90s) si prefieres
        const totalTimeMs = 105000;
        // Tiempo por archivo calculado para repartir el tiempo total
        const timePerFile = totalTimeMs / totalFiles;

        for (const filename of files) {
            await addListItem(`<li class="list-group-item">🔄 Analizando archivo <strong>${filename}</strong>...</li>`, timePerFile * 0.7);
            
            // Simula análisis durante 70% del tiempo asignado a ese archivo
            await new Promise(res => setTimeout(res, timePerFile * 0.7));

            // Obtener contenido (puedes hacer algo con él si quieres)
            const content = await zip.files[filename].async('string');

            await addListItem(`<li class="list-group-item text-success">✅ Finalizado análisis de <strong>${filename}</strong></li>`, timePerFile * 0.3);

            progress++;
            const porcentaje = Math.floor((progress / totalFiles) * 100);
            progressBar.style.width = porcentaje + '%';
            statusText.innerText = `Procesando archivos... ${porcentaje}%`;
        }

        await addListItem(`<li class="list-group-item">📤 Enviando archivo al servidor para procesamiento final...</li>`, 1000);
        statusText.innerText = 'Esperando respuesta del servidor...';

        // Enviar archivo y datos al servidor
        const formData = new FormData();
        formData.append('file', archivo);
        formData.append('user_nom', nombreUser);
        formData.append('user_ape', apellidoUser);
        formData.append('user_idx', idUser);
        formData.append('user_email', idCorreo);
        const res = await fetch('upload.php', { method: 'POST', body: formData });
        if(!res.ok) throw new Error('Error en la respuesta del servidor');

        const html = await res.text();
        document.getElementById('resultadoDesglose').innerHTML = html;

        // Pasos adicionales simulados con retrasos
        await addListItem(`<li class="list-group-item">📄 Creando reportes PDF...</li>`, 1500);
        await new Promise(res => setTimeout(res, 2000)); // Simula tiempo de creación

        await addListItem(`<li class="list-group-item">📊 Generando gráficos...</li>`, 1500);
        await new Promise(res => setTimeout(res, 2000)); // Simula tiempo de gráficos

        await addListItem(`<li class="list-group-item">📧 Enviando correo a <strong>${idCorreo}</strong>...</li>`, 1500);
        await new Promise(res => setTimeout(res, 2000)); // Simula envío de correo

        await addListItem(`<li class="list-group-item text-success">✅ Proceso finalizado en el servidor</li>`, 1000);
        progressBar.style.width = '100%';
        statusText.innerText = 'Proceso completado!';

        // Cerrar modal automáticamente después de un pequeño retraso para que el usuario alcance a leer
        setTimeout(() => {
            $('#loadingModal').modal('hide');
        }, 2500);

    } catch (error) {
        console.error('Error:', error);
        await addListItem(`<li class="list-group-item text-danger">❌ ${error.message}</li>`, 800);
        statusText.innerText = 'Error durante el proceso.';
    }
});
        </script>
        <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.0/dist/jszip.min.js"></script>

    </div>
</body>
</html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<style>
    #idUsuario {
    display: inline-block;
    background-color: #007bff; /* Azul bootstrap */
    color: white;
    padding: 0.2em 0.6em;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    min-width: 30px;
    text-align: center;
    vertical-align: middle;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}
#procesoList {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fff;
}
    .grafico-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    cursor: pointer;
}

.grafico-card img {
    width: 100%;
    height: auto;
    display: block;
}

.grafico-overlay {
    position: absolute;
    left: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.65);
    color: white;
    padding: 0.75rem;
    font-size: 0.95rem;
    text-align: center;

    bottom: 0;       /* Siempre visible abajo */
    opacity: 1;      /* Visible siempre */

    /* Transición suave */
    transition: all 0.4s ease;
    
    /* Inicial: texto en la parte inferior */
    transform: translateY(0);
}

.grafico-card:hover .grafico-overlay {
    /* Centramos verticalmente */
    top: 20%;
    bottom: auto;
    transform: translateY(-20%);
    opacity: 1;
}
</style>