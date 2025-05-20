function init() {

}

$(document).ready(function () {


});


//Como llamar al btn
$(document).on("click", "#btnSoporte", function () {
    if ($('#rol_id').val() == 1) {
        $("#lbltitulo").html("Acceso Soporte");
        $("#btnSoporte").html("Acceso Usuario");
        $("#rol_id").val(2);
    } else {
        $("#lbltitulo").html("Acceso Usuario");
        $("#btnSoporte").html("Acceso Soporte");
        $("#rol_id").val(1);
    }

});

// Inicializar la función init cuando el DOM esté listo
init();