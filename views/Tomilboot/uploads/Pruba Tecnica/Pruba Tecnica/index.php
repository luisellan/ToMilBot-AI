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
    <script>
        function eliminar(){
            var respuesta  = confirm("Estas seguro que deseas eliminar");
            return respuesta;
        }
    </script>
    <div class="container-fluid-row ">

        <div class="row px-4">
            <h1>Prueba Tecnica</h1>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <!-- Mostrar Error     -->
                            <?php
                            include "models/conexion.php";
                            include "controller/registro.php";

                            ?>
                            <h3>Registro de Persona</h3>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">

                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido">
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">Cedula</label>
                                <input type="text" class="form-control" id="dni" name="dni">
                            </div>
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha Nacimiento</label>
                                <input type="date" class="form-control" id="fecha" name="fecha">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <button type="submit" name="btnregistrar" class="btn btn-success" value="ok">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <?php include "controller/eliminar.php"; ?>
                <table class="table table-success table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Cedula</th>
                            <th scope="col">Fecha Nacimiento</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("models/conexion.php");
                        $registrosPorPagina = 5;
                        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                        if ($pagina < 1) $pagina = 1;

                        $offset = ($pagina - 1) * $registrosPorPagina;

                        // Total de registros y páginas
                        $resultadoTotal = $conexion->query("SELECT COUNT(*) AS total FROM personas");
                        $totalRegistros = $resultadoTotal->fetch_object()->total;
                        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

                        // Consulta paginada
                        $sql = $conexion->query("SELECT * FROM personas LIMIT $registrosPorPagina OFFSET $offset");
                        while ($a = $sql->fetch_object()) { ?>
                            <tr>
                                <td><?= $a->id ?></td>
                                <td><?= htmlspecialchars($a->nombre) ?></td>
                                <td><?= htmlspecialchars($a->apellido) ?></td>
                                <td><?= htmlspecialchars($a->cedula) ?></td>
                                <td><?= htmlspecialchars($a->fecha_nacimiento) ?></td>
                                <td><?= htmlspecialchars($a->correo) ?></td>
                                <td>
                                    <a class="btn btn-warning" href="edit.php?id=<?= $a->id ?>"><i class="fa-solid fa-pencil"></i></a>

                                    <a onclick="return eliminar()" class="btn btn-danger" href="index.php?id=<?= $a->id ?>"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <!-- Paginación -->
                <nav>
                    <ul class="pagination">
                        <?php if ($pagina > 1): ?>
                            <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($pagina < $totalPaginas): ?>
                            <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
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