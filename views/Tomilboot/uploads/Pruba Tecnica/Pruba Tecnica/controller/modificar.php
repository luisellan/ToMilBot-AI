<?php
if (!empty($_POST["btnactualizar"])) {
    if (
        !empty($_POST["id"]) &&
        !empty($_POST["nombre"]) &&
        !empty($_POST["apellido"]) &&
        !empty($_POST["dni"]) &&

        !empty($_POST["email"])
    ) {
        // Captura de los valores
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $dni = $_POST["dni"];

        $email = $_POST["email"];

        // Utilizamos consultas preparadas para evitar inyecciones SQL
        $stmt = $conexion->prepare("UPDATE PERSONAS 
        SET 
        nombre = ?,
        apellido = ?,
        cedula = ?,
        fecha_nacimiento = NOW(),
        correo = ?
        WHERE id = ?");

        // Vinculamos los parámetros
        $stmt->bind_param("sssss", $nombre, $apellido, $dni, $email, $id);

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            // Almacenamos el mensaje en la sesión
            session_start();
            $_SESSION['mensaje'] = 'Persona Actualizada correctamente.';

            // Redirigimos al index.php
            header("Location: index.php");
            exit;  // Es importante llamar a exit después de la redirección
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Persona no Actualizada correctamente.
          </div>';
        }

        // Cerramos el statement
        $stmt->close();
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Alguno de los campos está vacío.
              </div>';
    }
}
