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

    </style>
</head>

<body>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xxl-9">
                        <div class="card" id="demo">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-header border-bottom-dashed p-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <img src="assests/imagenes/pcgerente_logo.jpg" class="card-logo card-logo-dark" alt="logo dark" height="50">

                                                <div class="mt-sm-5 mt-4">
                                                    <h6 class="text-muted text-uppercase fw-semibold">Direccion</h6>
                                                    <p class="text-muted mb-1" id="txtdirecc">Avenida 9 de Octubre</p>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 mt-sm-0 mt-3">
                                                <h6><span class="text-muted fw-normal">RUC: </span><span id="txtruc">0604537183</span></h6>
                                                <h6><span class="text-muted fw-normal">Email: </span><span id="txtemail">pcgerente@pc_generente.com</span></h6>
                                                <h6><span class="text-muted fw-normal">Website: </span> <a href="https://themesbrand.com/" class="link-primary" target="_blank" id="txtweb">www.pc_generente.com</a></h6>
                                                <h6 class="mb-0"><span class="text-muted fw-normal">Telefono: </span><span id="txttelf">0993096567</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end card-header-->
                                </div><!--end col-->
                                <div class="col-lg-12">
                                    <div class="card-body p-4">
                                        <div class="row g-3">
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Nro de Venta</p>
                                                <h5 class="fs-14 mb-0">V-<span id="txtid"></span></h5>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Fecha</p>
                                                <h5 class="fs-14 mb-0"><span id="txtfecha"></span></h5>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Tipo de Pago</p>
                                                <span class="badge badge-soft-success fs-11" id="pag_nom">Efectivo</span>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Total</p>
                                                <h5 class="fs-14 mb-0">$<span id="txtTotal"></span></h5>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!--end col-->


                                <div class="col-lg-12">
                                    <div class="card-body p-4 border-top border-top-dashed">
                                        <div class="row g-3">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Datos del Cliente</h6>
                                                </div>
                                            </div>
                                            <!--end row-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Nombre:</p>
                                                <h5 class="fs-14 mb-0"><span id="txtcli_nom"></span></h5>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Telefono</p>
                                                <h5 class="fs-14 mb-0"><span id="txtcli_telefono"></span></h5>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Direccion</p>
                                                <span id="txtcli_direccion"></span>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-3 col-6">
                                                <p class="text-muted mb-2 text-uppercase fw-semibold">Correo</p>
                                                <h5 class="fs-14 mb-0"><span id="txtcli_correo"></span></h5>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!--end col-->
                                <div class="col-lg-12">
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr class="table-active">
                                                        <th>Producto</th>
                                                        <th>Cliente</th>
                                                        <th>Precio</th>
                                                        <th>Cantidad</th>
                                                        <th>Iva</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-list">

                                                </tbody>
                                            </table><!--end table-->
                                        </div>
                                        <div class="border-top border-top-dashed mt-2">
                                            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                                <tbody>
                                                    <tr>
                                                        <td>Sub Total</td>
                                                        <td class="text-end" id="txtdtsubtotal"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>IVA</td>
                                                        <td class="text-end" id="txtdtmonto_iva"></td>
                                                    </tr>
                                                    <tr class="border-top border-top-dashed fs-15">
                                                        <th scope="row">Precio Total</th>
                                                        <th class="text-end" id="txtdttotal"></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--end table-->
                                        </div>
                                        <div class="mt-4">
                                            <div class="alert alert-info">
                                                <p class="mb-0"><span class="fw-semibold">COMENTARIO:</span>
                                                    <span id="vent_coment">
                                                        Gracias por su compra. Asegúrese de revisar todos los detalles de la factura. Si tiene alguna pregunta o inquietud, no dude en ponerse en contacto con nosotros.
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                            <a href="javascript:window.print()" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a>

                                        </div>
                                    </div>
                                    <!--end card-body-->
                                </div><!--end col-->
                            </div><!--end row-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

            </div><!-- container-fluid -->
        </div><!-- End Page-content -->


    </div><!-- end main content-->
    <!-- Bootstrap JS -->
    <script src="assests/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var id = getUrlParameter('v')
            $.post("controllers/factura.php?op=mostrar", {
                id: id
            }, function(data) {
                data = JSON.parse(data);
                $('#txtdttotal').html(data.Total);
                $('#txtdtsubtotal').html(data.subtotal);
                $('#txtdtmonto_iva').html(data.monto_iva);
                $('#txtid').html(data.id);
                $('#txtfecha').html(data.Fecha);
                $('#txtTotal').html(data.Total);
                $('#txtcli_nom').html(data.CLI_NOM);
                $('#txtcli_direccion').html(data.CLI_DIRECCION);
                $('#txtcli_correo').html(data.CLI_CORREO);
                $('#txtcli_telefono').html(data.CLI_TELEFONO);
            });

            $.post("controllers/factura.php?op=listartopproducto", {
                id: id
            }, function(data) {
                $('#products-list').html(data);
            });
        });















        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
    </script>
</body>

</html>