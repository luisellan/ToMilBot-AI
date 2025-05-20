<?php
include "models/conexion.php";
$id = $_GET["id"];
echo $id;

$sql = $conexion->query("SELECT * FROM PERSONAS WHERE id = $id");
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <title>Hello, world!</title>
</head>

<body>

    <div class="container-fluid-row ">

        <div class="row px-4">
            <h1>Prueba Tecnica</h1>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <!-- Mostrar Error     -->
                            <?php
                            include "controller/modificar.php";
                            // Suponiendo que la consulta ya fue ejecutada y $sql contiene un solo registro
                            if ($a = $sql->fetch_object()) { ?>
                                <h3>Actudalizar Registro de Persona</h3>
                                <input type="hidden" name="id" value="<?= htmlspecialchars($a->id) ?>">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($a->nombre) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($a->apellido) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="dni" class="form-label">Cedula</label>
                                    <input type="text" class="form-control" id="dni" name="dni" value="<?= htmlspecialchars($a->cedula) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($a->correo) ?>" required>
                                </div>
                                <button type="submit" name="btnactualizar" class="btn btn-warning" value="ok">Actualizar</button>
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <?php } else { ?>
                                <p>No se encontró el registro</p>
                            <?php } ?>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>