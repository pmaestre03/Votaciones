$(document).ready(function () {
          var array_opciones_encuesta =  []
          $("#add-option").click(function () {
                    var option_to_add = $("#opciones_encuesta").val()
                    if ($("#opciones_encuesta").val() != "") {
                              array_opciones_encuesta.push(option_to_add)
                              sessionStorage.setItem("opciones",array_opciones_encuesta)
                    }
                    $("#opciones_encuesta").val("")
                    if (array_opciones_encuesta.length >= 2 && $("#send-poll").length === 0) {
                              $("form").append('<button type="submit" id="send-poll" class="button button-login">Enviar encuesta</button>')

                    }
          });
});

$("send-poll").click(function() {
          array_opciones_encuesta =  []
})