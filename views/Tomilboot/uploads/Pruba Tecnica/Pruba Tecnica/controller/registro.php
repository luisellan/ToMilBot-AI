<?php
if (!empty($_POST["btnregistrar"])) {
    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["apellido"]) &&
        !empty($_POST["dni"]) &&
        !empty($_POST["fecha"]) &&
        !empty($_POST["email"])
    ) {
        // Captura de los valores
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $dni = $_POST["dni"];
        $fecha = $_POST["fecha"];
        $email = $_POST["email"];

        // Utilizamos consultas preparadas para evitar inyecciones SQL
        $stmt = $conexion->prepare("INSERT INTO PERSONAS (nombre, apellido, cedula, fecha_nacimiento, correo) 
                                    VALUES (?, ?, ?, ?, ?)");
        
        // Vinculamos los parámetros
        $stmt->bind_param("sssss", $nombre, $apellido, $dni, $fecha, $email);
        
        // Ejecutamos la consulta
        if ($stmt->execute()) {
            echo '<div class="alert alert-success" role="alert">
                    Persona registrada correctamente.
                  </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                    Persona no registrada correctamente.
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
?>
