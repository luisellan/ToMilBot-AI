
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
                <h4>Agregar Producto</h4>
            </div>
            <div class="card-body">
                <form id="formFactura">
                    <!-- Datos del Cliente -->
                    <h5 class="mb-3">Datos del Cliente</h5>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="cli_id" class="form-label">Cliente</label>
                            <select class="form-select" id="cli_id" name="cli_id">
                                <option value="0" selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="cli_nom" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="cli_nom" name="cli_nom" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label for="cli_direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="cli_direccion" name="cli_direccion" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label for="cli_telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="cli_telefono" name="cli_telefono" readonly>
                        </div>
                    </div>

                    <!-- Datos del Producto -->
                    <h5 class="mb-3">Datos del Producto</h5>
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="prod_id" class="form-label">Producto</label>
                            <select class="form-select" id="prod_id" name="prod_id">
                                <option value="0" selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="prod_precio" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="prod_precio" name="prod_precio" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label for="prod_stock" class="form-label">Stock</label>
                            <input type="text" class="form-control" id="prod_stock" name="prod_stock" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label for="prod_stock_resta" class="form-label">Cantidad</label>
                            <input type="text" class="form-control" id="prod_stock_resta" name="prod_stock_resta">
                        </div>
                        <div class="col-lg-3">
                            <label for="iva" class="form-label">Iva</label>
                            <input type="text" class="form-control" id="iva" name="iva">
                        </div>
                        <div class="col-lg-3">
                            <label for="iva" class="form-label">Color</label>
                            <input type="text" class="form-control" id="prod_color" name="prod_color">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" id="btnagregar" class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Nueva Compra
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4>Detalle de Factura</h4>
            </div>
            <div class="card-body">
                <table id="tablaFactura" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>SubTotal</th>
                            <th>% Iva</th>
                            <th>Iva</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="view_factura.php" class="btn btn-info text-white">Lista de Facturas</a>
            <button type="button" id="btnGuardar" class="btn btn-success">Guardar Factura</button>
            <button type="button" id="btnLimpiar" class="btn btn-secondary">Limpiar Factura</button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assests/js/bootstrap.bundle.min.js"></script>

    <!-- Aquí iría tu script para manejo de la factura, validaciones, etc. -->
    <script>
        $(document).ready(function() {
            let tabla = $('#tablaFactura').DataTable();

            // Cargar clientes
            $.post("controllers/cliente.php?op=combo", function(data) {
                $("#cli_id").html(data);
            });
            $("#cli_id").change(function() {
                let cli_id = $(this).val();
                if (cli_id != "0") {
                    $.post("controllers/cliente.php?op=mostrar", {
                        cli_id: cli_id
                    }, function(data) {
                        let cliente = JSON.parse(data);
                        $("#cli_nom").val(cliente.CLI_NOM);
                        $("#cli_direccion").val(cliente.CLI_DIRECCION);
                        $("#cli_telefono").val(cliente.CLI_TELEFONO);
                    });
                } else {
                    $("#cli_nom, #cli_direccion, #cli_telefono").val('');
                }
            });

            // Cargar productos
            $.post("controllers/producto.php?op=combo", function(data) {
                $("#prod_id").html(data);
            });
            $("#prod_id").change(function() {
                let prod_id = $(this).val();
                if (prod_id != "0") {
                    $.post("controllers/producto.php?op=mostrar", {
                        prod_id: prod_id
                    }, function(data) {
                        let producto = JSON.parse(data);
                        $("#prod_precio").val(producto.PROD_PRECIO);
                        $("#prod_stock").val(producto.PROD_STOCK);
                    });
                } else {
                    $("#prod_precio, #prod_stock").val('');
                }
            });


            $("#btnagregar").click(function() {
                let productoTexto = $("#prod_id option:selected").text();
                let prod_id = $("#prod_id").val();
                let precio = parseFloat($("#prod_precio").val());
                let stock = parseInt($("#prod_stock").val());
                let cantidad = parseInt($("#prod_stock_resta").val());
                let iva = parseFloat($("#iva").val()) / 100;
                


                if (prod_id === "0") {
                    Swal.fire("Error", "Seleccione un producto válido", "error");
                    return;
                }
                if (isNaN(precio) || precio <= 0) {
                    Swal.fire("Error", "El precio del producto no es válido", "error");
                    return;
                }
                if (isNaN(cantidad) || cantidad <= 0) {
                    Swal.fire("Error", "Ingrese una cantidad válida", "error");
                    return;
                }
                if (cantidad > stock) {
                    Swal.fire("Error", "La cantidad supera el stock disponible", "error");
                    return;
                }
                if (isNaN(iva) || iva < 0) {
                    Swal.fire("Error", "Ingrese un IVA válido", "error");
                    return;
                }

                let subtotal = precio * cantidad;
                let monto_iva = subtotal * iva;
                let total = subtotal + monto_iva;


                tabla.row.add([
                    productoTexto,
                    `$${precio.toFixed(2)}`,
                    cantidad,
                    `$${subtotal.toFixed(2)}`,
                    `${iva.toFixed(2)} %`,
                    `$${monto_iva.toFixed(2)}`,
                    `$${total.toFixed(2)}`,
                    `<button class="btn btn-danger btn-sm eliminar">Eliminar</button>`
                ]).draw();


                let nuevoStock = stock - cantidad;
                $("#prod_stock").val(nuevoStock);
                $("#prod_stock_resta").val("");

                Swal.fire("Producto Agregado", "Se ha agregado el producto a la factura", "success");
            });


            $("#tablaFactura tbody").on("click", ".eliminar", function() {
                let fila = $(this).closest("tr");
                let cantidad = parseInt(fila.find("td:eq(2)").text());

                tabla.row(fila).remove().draw();
                Swal.fire("Producto Eliminado", "El producto fue eliminado de la factura", "warning");
            });


            $("#btnGuardar").click(function() {
                let factura = {
                    cliente: $("#cli_id").val(),
                    productos: []
                };


                tabla.rows().every(function(rowIdx, tableLoop, rowLoop) {
                    let data = this.data();

                    let producto = data[0];
                    let precio = parseFloat(data[1].replace("$", ""));
                    let cantidad = parseInt(data[2]);
                    let subtotal = parseFloat(data[3].replace("$", ""));
                    let ivaPorcentaje = parseFloat(data[4].replace("%", ""));

                    
                    let monto_iva = subtotal * ivaPorcentaje;


                    let total = subtotal + monto_iva;


                    factura.productos.push({
                        producto: producto,
                        precio: precio.toFixed(2),
                        cantidad: cantidad,
                        subtotal: subtotal.toFixed(2),
                        iva: ivaPorcentaje.toFixed(2),
                        monto_iva: monto_iva.toFixed(2),
                        total: total.toFixed(2),
                        color: prod_color
                    });
                });


                if (factura.productos.length === 0) {
                    Swal.fire("Error", "No hay productos en la factura", "error");
                    return;
                }

                console.log("Datos de factura a enviar:", JSON.stringify(factura, null, 2));


                $.ajax({
                    url: "controllers/factura.php?op=guardar",
                    type: "POST",
                    data: {
                        factura: JSON.stringify(factura)
                    },
                    success: function(response) {

                        Swal.fire("Factura Guardada", "La factura se ha guardado correctamente", "success");


                        tabla.clear().draw();
                        $("#formFactura")[0].reset();
                    },
                    error: function(xhr, status, error) {

                        Swal.fire("Error", "No se pudo guardar la factura", "error");
                    }
                });
            });




            $("#btnLimpiar").click(function() {
                Swal.fire({
                    title: "Confirmar",
                    text: "¿Está seguro de limpiar la factura?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, limpiar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        tabla.clear().draw();
                        $("#formFactura")[0].reset();
                        Swal.fire("Factura Limpiada", "La factura se ha limpiado correctamente", "success");
                    }
                });
            });
        });
    </script>
</body>

</html>