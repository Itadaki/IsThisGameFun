$("#formulario").validate({
    rules: {
        nombreUsuario: {
            required: true,
        },
        contraseña: {
            required: true,
        },
        contraseña2: {
            required: true,
            equalTo: contraseña
        },
        nombre: {
            required: true,
            lettersonly: true
        },
        apellidos: {
            required: true,
            lettersonly: true
        },
        telefono: {
            required: true,
            digits: true,
            minlength: 9,
            maxlength: 9
        },
        email: {
            required: true,
            email: true,
        },
        email2: {
            required: true,
            equalTo: email
        }
    },
    messages: {
        nombre: {
            lettersonly: "Escribe sólo letras"
        },
        contraseña2: {
            equalTo: "Las contraseñas no coinciden"
        },
        apellidos: {
            lettersonly: "Escribe sólo letras"
        },
        email2: {
            equalTo: "Escribe el mismo email"
        },
    },
    submitHandler: function () {
        alert("formulario enviado");
    }
});



