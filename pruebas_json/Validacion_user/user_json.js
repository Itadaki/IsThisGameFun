function validar() {
    xhr = new XMLHttpRequest();
    if (xhr) {
        xhr.onreadystatechange = gestionarRespuesta;
        xhr.open('POST', 'user_json.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send('json=' + crea_json());
    }
}
function crea_json() {
    var user = document.getElementById("nombreUsuario").value;
    var JSONObject = new Object();
    JSONObject.user = user;
    var objeto_json = JSON.stringify(JSONObject);
    console.log('JSON ENVIADO: ' + objeto_json);
    return objeto_json;
}
function gestionarRespuesta() {
    if (xhr.readyState == 4 && xhr.status == 200) {
        var respuesta_JSON = xhr.responseText;
        var objeto_json = eval("(" + respuesta_JSON + ")");
        var existe = objeto_json[0].existe;
        if (existe) {
            alert("El nombre de usuario ya existe!");
        } else {
            alert("Nombre de usuario valido");
        }
    }
}
