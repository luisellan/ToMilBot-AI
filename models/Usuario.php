<?php
class Usuario extends Conectar
{
    public function login()
    {
        $conectar = parent::Conexion();
        parent::set_names();

        if (isset($_POST["enviar"])) {
            echo "Formulario enviado<br>";

            $usu_correo = trim($_POST["usu_correo"]);
            $usu_pass = trim($_POST["usu_pass"]);
            $rol_id = trim($_POST["rol_id"]);

            echo "Correo: " . $usu_correo . "<br>";
            echo "Password: " . $usu_pass . "<br>";
            echo "Rol: " . $rol_id . "<br>";

            if (empty($usu_correo) || empty($usu_pass)) {
                echo "Correo o contraseña vacíos<br>";
                header("Location: " . conectar::ruta() . "login.php?m=2");
                exit();
            } else {
                echo "Intentando login...<br>";

                // Buscar solo por correo y rol
                $sql = "SELECT * FROM TM_USUARIO WHERE USU_CORREO = ? AND ROL_ID = ? AND EST = 1";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $usu_correo);
                $stmt->bindValue(2, $rol_id);
                $stmt->execute();

                $resultado = $stmt->fetch();

                echo "<pre>";
                var_dump($resultado);
                echo "</pre>";

                if (is_array($resultado) && count($resultado) > 0) {
                    // Validar contraseña usando password_verify
                    if (password_verify($usu_pass, $resultado["USU_PASS"])) {
                        echo "Login exitoso<br>";
                        $_SESSION["usu_id"] = $resultado["USU_ID"];
                        $_SESSION["usu_nom"] = $resultado["USU_NOM"];
                        $_SESSION["usu_ape"] = $resultado["USU_APE"];
                        $_SESSION["usu_correo"] = $resultado["USU_CORREO"];
                        $_SESSION["usu_pass"] = $resultado["USU_PASS"];
                        $_SESSION["rol_id"] = $resultado["ROL_ID"];
                        $_SESSION["usu_img"] = $resultado["USU_IMG"];
                        header("Location: " . conectar::ruta() . "views/home");
                        exit();
                    } else {
                        echo "Contraseña incorrecta<br>";
                        header("Location: " . conectar::ruta() . "login.php?m=1");
                        exit();
                    }
                } else {
                    echo "Correo o rol incorrecto, o usuario no activo<br>";
                    header("Location: " . conectar::ruta() . "login.php?m=1");
                    exit();
                }
            }
        } else {
            echo "Formulario no enviado<br>";
        }
    }



    public function listar()
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM TM_USUARIO WHERE EST = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtener($usu_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM TM_USUARIO WHERE EST = 1 and USU_ID = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($usu_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE TM_USUARIO SET EST = 0 WHERE USU_ID id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();
    }

    public function insertar($usu_nom, $usu_ape, $usu_correo, $usu_pass, $rol_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "INSERT INTO TM_USUARIO
    (USU_NOM, USU_APE, USU_CORREO, USU_PASS, ROL_ID)
    VALUES (?, ?, ?, ?, ?)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_nom);
        $stmt->bindValue(2, $usu_ape);
        $stmt->bindValue(3, $usu_correo);
        $stmt->bindValue(4, $usu_pass);
        $stmt->bindValue(5, $rol_id);
        $stmt->execute();
    }


    public function modificar($usu_id, $usu_nom, $usu_ape, $usu_correo, $usu_img)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        require_once("Usuario.php");
        $usu = new Usuario();

        if ($_FILES["usu_img"]["name"] != '') {
            $usu_img = $usu->upload_image();
        } else {
            $usu_img = $_POST["hidden_usuario_imagen"];
        }

        $sql = "UPDATE TM_USUARIO SET 
                USU_NOM = ?, 
                USU_APE = ?, 
                USU_CORREO = ?, 
                USU_IMG = ?
            WHERE USU_ID = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_nom);
        $stmt->bindValue(2, $usu_ape);
        $stmt->bindValue(3, $usu_correo);
        $stmt->bindValue(4, $usu_img);
        $stmt->bindValue(5, $usu_id); // el ID es el último en el WHERE

        $stmt->execute();
    }



    public function restaurar_password($usu_id, $usu_pass)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE TM_USUARIO SET 
                USU_PASS = ?
            WHERE USU_ID = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_pass);
        $stmt->bindValue(2, $usu_id);
        $stmt->execute();
    }


    public function VERIFICAR($usu_correo)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM TM_USUARIO WHERE EST = 1 AND USU_CORREO = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_correo, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado : false;
    }


    public function register()
    {
        $conectar = parent::Conexion();
        parent::set_names();

        if (isset($_POST["enviar"])) {
            $usu_nom = trim($_POST["usu_nom"]);
            $usu_ape = trim($_POST["usu_ape"]);
            $usu_correo = trim($_POST["usu_correo"]);
            $usu_pass = trim($_POST["usu_pass"]);
            $rol_id = intval($_POST["rol_id"]);

            if (empty($usu_correo) || empty($usu_pass) || empty($usu_nom) || empty($usu_ape)) {
                header("Location: " . conectar::ruta() . "register.php?m=2"); // Redirecciona si falta info
                exit();
            }

            // 1. Validar si ya existe el usuario
            $sql = "SELECT * FROM TM_USUARIO WHERE USU_CORREO = ? AND EST = 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_correo);
            $stmt->execute();
            $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioExistente) {
                // Usuario ya registrado
                header("Location: " . conectar::ruta() . "register.php?m=1"); // Mensaje de "usuario ya existe"
                exit();
            } else {
                // 2. Registrar el usuario
                $hashPassword = password_hash($usu_pass, PASSWORD_DEFAULT);

                $insertSql = "INSERT INTO TM_USUARIO (USU_NOM, USU_APE,USU_CORREO, USU_PASS, ROL_ID,FECH_CREA, EST, USU_IMG) VALUES (?, ?, ?, ?, ?, NOW(), 1, 'user-dummy-img.jpg')";
                $insertStmt = $conectar->prepare($insertSql);
                $insertStmt->bindValue(1, $usu_nom);
                $insertStmt->bindValue(2, $usu_ape);
                $insertStmt->bindValue(3, $usu_correo);
                $insertStmt->bindValue(4, $hashPassword);
                $insertStmt->bindValue(5, $rol_id);
                $insertStmt->execute();

                // 3. Iniciar sesión automática después de registrarlo
                $usu_id = $conectar->lastInsertId();

                $_SESSION["usu_id"] = $usu_id;
                $_SESSION["usu_nom"] = $usu_nom; // Puedes agregar campos después
                $_SESSION["usu_ape"] = $usu_ape;
                $_SESSION["rol_id"] = $rol_id;
                $_SESSION["usu_img"] = 'user-dummy-img.jpg';
                header("Location: " . conectar::ruta() . "views/home"); // Redirige al home
                exit();
            }
        }
    }

    public function upload_image()
    {
        if (isset($_FILES["usu_img"])) {
            $extension = explode('.', $_FILES['usu_img']['name']);
            $new_name = rand() . '.' . $extension[1];
            $destination = '../assets/images/users/' . $new_name;
            move_uploaded_file($_FILES['usu_img']['tmp_name'], $destination);
            return $new_name;
        }
    }
}
