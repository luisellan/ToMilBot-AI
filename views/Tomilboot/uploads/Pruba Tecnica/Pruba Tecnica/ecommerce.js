// Esta es la función que se encarga de cargar los productos en la tabla
function cargarProductos() {
    // Se hace una petición (fetch) a un archivo PHP que devuelve los productos en formato JSON
    fetch('controller/Ecommerce/productos.php')
        // Cuando la respuesta se recibe, se convierte en formato JSON para poder trabajar con ella
        .then(response => response.json())
        .then(data => {
            // Buscamos el cuerpo de la tabla (donde se mostrarán los productos) por su id 'productosTable'
            let tableBody = document.querySelector('#productosTable tbody');

            // Limpiamos el contenido actual de la tabla para que no se repitan los productos
            tableBody.innerHTML = '';

            // Recorreremos cada producto que recibimos del servidor
            data.forEach(producto => {
                // Creamos una nueva fila en la tabla para agregar un producto
                let row = document.createElement('tr');

                // Establecemos el contenido de la fila (el nombre, precio, cantidad y botones)
                row.innerHTML = `
                    <td>${producto.id}</td> <!-- Muestra el ID del producto -->
                    <td>${producto.nombre}</td> <!-- Muestra el nombre del producto -->
                    <td>${producto.precio}</td> <!-- Muestra el precio del producto -->
                    <td>${producto.cantidad}</td> <!-- Muestra la cantidad disponible -->
                    <td>
                        <!-- Botón para editar el producto -->
                        <button onclick="editarProducto(${producto.id})">Editar</button>
                        <!-- Botón para eliminar el producto -->
                        <button onclick="eliminarProducto(${producto.id})">Eliminar</button>
                    </td>
                `;

                // Agregamos la fila creada al cuerpo de la tabla
                tableBody.appendChild(row);
            });
        });
}

// Escucha el evento de envío (submit) del formulario para guardar o actualizar el producto
document.getElementById('formProducto').addEventListener('submit', function (e) {
    // Evita que el formulario se envíe de forma tradicional (recarga la página)
    e.preventDefault();

    // Captura los valores de los campos del formulario
    let id = document.getElementById('id').value;           // ID del producto (si existe, para actualizar)
    let nombre = document.getElementById('nombre').value;   // Nombre del producto
    let cantidad = document.getElementById('cantidad').value; // Cantidad disponible del producto
    let precio = document.getElementById('precio').value;   // Precio del producto

    // Si el ID está presente, se enviará una solicitud para actualizar, de lo contrario se guardará como nuevo
    let url = id ? 'controller/Ecommerce/actualizar.php' : 'controller/Ecommerce/guardar.php';        // Si 'id' tiene valor, usa 'actualizar.php', de lo contrario usa 'guardar.php'

    // Crea un objeto con los datos del producto
    let data = {
        id: id,            // El ID del producto (si es una actualización)
        nombre: nombre,    // El nombre del producto
        cantidad: cantidad, // La cantidad del producto
        precio: precio     // El precio del producto
    };

    // Realiza la solicitud POST al servidor usando Fetch API
    fetch(url, {
        method: 'POST',                            // Establece el método HTTP como POST
        headers: {
            'Content-Type': 'application/json'    // Define el tipo de contenido como JSON
        },
        body: JSON.stringify(data)                  // Convierte el objeto 'data' en una cadena JSON
    })
        .then(response => response.json())           // Procesa la respuesta como JSON
        .then(result => {
            // Muestra un mensaje de éxito o error dependiendo de la respuesta
            alert(result.message);

            // Llama a la función 'cargarProductos' para actualizar la lista de productos en la interfaz
            cargarProductos();

            // Resetea los campos del formulario (limpia los valores)
            document.getElementById('formProducto').reset();

            // Resetea el valor del campo 'id' para evitar que persista entre registros
            document.getElementById('id').value = '';
        })
});


function editarProducto(id) {
    fetch('controller/Ecommerce/producto.php?id=' + id)
        .then(response => response.json())
        .then(producto => {
            document.getElementById('id').value = producto.id;           // ID del producto (si existe, para actualizar)
            document.getElementById('nombre').value = producto.nombre;   // Nombre del producto
            document.getElementById('cantidad').value = producto.cantidad; // Cantidad disponible del producto
            document.getElementById('precio').value = producto.precio;
        });
}

function eliminarProducto(id) {
    if (confirm('Estas seguro de eliminar este producto')) {
        fetch('controller/Ecommerce/eliminar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                cargarProductos();
            })
    }
}


cargarProductos();