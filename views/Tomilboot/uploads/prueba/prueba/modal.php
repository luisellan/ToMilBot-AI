<div id="modalmantenimiento" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="lbltitulo">Detalle de Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table id="detalleTabla" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID Producto</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Iva</th>
                                <th>Saldo Iva</th>
                                <th>SubTotal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se rellenará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                
               
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" name="action" value="add" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>