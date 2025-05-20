<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura con Estilo Bootstrap</title>

    <!-- Bootstrap CSS -->
    <link href="assests/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS (Integrado con Bootstrap) -->
    <link rel="stylesheet" href="assests/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <script src="assests/sweetalert2@11.js"></script>

    <!-- jQuery -->
    <script src="assests/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="assests/jquery.dataTables.min.js"></script>
    <script src="assests/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f1f1;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        h2 {
            margin: 2rem 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Sistema de Facturación</h2>

        <!-- Formulario para agregar productos -->
        <div class="card">
            <div class="card-header">
                <h4>LISTA DE FACTURAS</h4>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card mb-4">
            <div class="card-body">
                <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>SubTotal</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <?php require_once("modal.php"); ?>
    <!-- Bootstrap JS -->
    <script src="assests/js/bootstrap.bundle.min.js"></script>

    <!-- Aquí iría tu script para manejo de la factura, validaciones, etc. -->
    <script>
        $(document).ready(function() {
            $('#table_data').DataTable({
                "aProcessing": true,
                "aServerSide": true,
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                ],
                "ajax": {
                    url: "controllers/factura.php?op=listar",
                    type: "post",
                },
                "bDestroy": true,
                "responsive": true,
                "bInfo": true,
                "iDisplayLength": 20,
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
            });

        });

        function editar(id) {
    $.post("controllers/factura.php?op=listar_items", { id: id }, function(response) {
        let jsonResponse = JSON.parse(response);
        console.log("Detalle de productos:", jsonResponse);

       
        let items = jsonResponse.aaData; 
        let tableBody = "";

        

        items.forEach(function(item) {
            tableBody += `<tr>
                            <td>${item[0]}</td>
                            <td>${item[1]}</td>
                            <td>${item[2]}</td>
                            <td>${item[3]}</td>
                            <td>${item[4]}</td>
                            <td>${item[5]}</td>
                            <td>${item[6]}</td>
                            <td>${item[7]}</td>
                            <td>${item[8]}</td>
                          </tr>`;
        });

        $("#detalleTabla tbody").html(tableBody);
        $('#modalmantenimiento').modal('show'); // Mostrar el modal de detalle
    });
}
    </script>
</body>

</html>