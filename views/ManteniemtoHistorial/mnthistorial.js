$(document).ready(function () {
      // Capturar el valor del hidden input
      var userIdx = $('#user_idx').val();
      console.log('Usuario ID:', userIdx);

      // Inicialización de DataTable
      $('#table_data').DataTable({
          processing: true,
          serverSide: true,
          dom: 'Bfrtip',
          buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5'
          ],
          ajax: {
              url: '../../controller/historial.php?op=listar',
              type: 'POST',
              data: function (d) {
                  // Agregar user_idx a los parámetros de la petición
                  d.user_idx = userIdx;
              }
          },
          destroy: true,
          responsive: true,
          info: true,
          pageLength: 10,
          order: [[0, 'desc']],
          language: {
              processing: "Procesando...",
              lengthMenu: "Mostrar _MENU_ registros",
              zeroRecords: "No se encontraron resultados",
              emptyTable: "Ningún dato disponible en esta tabla",
              info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
              infoFiltered: "(filtrado de un total de _MAX_ registros)",
              search: "Buscar:",
              loadingRecords: "Cargando...",
              paginate: {
                  first: "Primero",
                  last: "Último",
                  next: "Siguiente",
                  previous: "Anterior"
              },
              aria: {
                  sortAscending: ": Activar para ordenar la columna de manera ascendente",
                  sortDescending: ": Activar para ordenar la columna de manera descendente"
              }
          }
      });
  });