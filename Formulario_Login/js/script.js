$("#formulario").validate({
    rules: {
        nombreUsuario: {
            required: true,
        },
        contraseña: {
            required: true,
        }
    },
    submitHandler: function () {
        alert("formulario enviado");
    }
});



