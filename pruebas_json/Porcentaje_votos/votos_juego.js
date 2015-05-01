function validar() {
    xhr = new XMLHttpRequest();
    if (xhr) {
        xhr.onreadystatechange = gestionarRespuesta;
        xhr.open('POST', 'votos_juego.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send('json=' + crea_json());
    }
}
function crea_json() {
    var game = document.getElementById("juego").value;
    var JSONObject = new Object();
    JSONObject.game = game;
    var objeto_json = JSON.stringify(JSONObject);
    console.log('JSON ENVIADO: ' + objeto_json);
    return objeto_json;
}
function gestionarRespuesta() {
    if (xhr.readyState == 4 && xhr.status == 200) {
        var respuesta_JSON = xhr.responseText;
        var objeto_json = eval("(" + respuesta_JSON + ")");
        var votos_totales = objeto_json[0].juego.votos_totales;
        var votos_positivos = objeto_json[1].positivos.votos_positivos;
        var porcentaje_votos = (votos_positivos/votos_totales)*100;
        document.getElementById("positivos").style.width = porcentaje_votos+"%";
        document.getElementById("positivos").innerHTML = "<p>"+parseInt(porcentaje_votos)+"%</p>";
    }
}



