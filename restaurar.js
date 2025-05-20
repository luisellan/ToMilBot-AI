document.getElementById('btnBuscarUsuario').addEventListener('click', function () {
    const usuario = document.getElementById('usu_correo').value;

    if (usuario.trim() === '') {
        Swal.fire('Advertencia', 'Por favor ingresa un usuario', 'warning');
        return;
    }

    const data = new URLSearchParams({ usu_correo: usuario });
    console.log('🔍 Enviando búsqueda:', Object.fromEntries(data));

    fetch('controller/usuario.php?op=obtener', {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
        .then(data => {
            console.log('📥 Respuesta de búsqueda:', data);
            if (data && Object.keys(data).length > 0) {
                document.getElementById('restaurarOpciones').classList.remove('d-none');
            } else {
                Swal.fire('Error', 'Usuario no encontrado', 'error');
            }
        });
});

document.getElementById('form-restaurar').addEventListener('submit', function (e) {
    e.preventDefault();

    const usuario = document.getElementById('usu_correo').value;
    const password = document.getElementById('usu_pass').value;

    if (password.trim() === '') {
        Swal.fire('Advertencia', 'Por favor ingresa la nueva contraseña', 'warning');
        return;
    }

    const data = new URLSearchParams({
        usu_correo: usuario,
        usu_pass: password
    });
    console.log('🔐 Enviando nueva contraseña:', Object.fromEntries(data));

    fetch('controller/usuario.php?op=restaurar_password', {
        method: 'POST',
        body: data
    })
        .then(res => res.json())
        .then(resp => {
            console.log('📥 Respuesta de restauración:', resp);
            if (resp.status === "success") {
                Swal.fire({
                    title: 'Éxito',
                    text: resp.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Después de hacer clic en OK, redirige al login
                    window.location.href = 'login.php';
                });
            } else {
                Swal.fire('Error', resp.message || 'Ocurrió un error', 'error');
            }
        });
});

