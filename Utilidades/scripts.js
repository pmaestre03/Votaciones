$(document).ready(function () {
          var array_opciones_encuesta = [];
          var indice_opciones = 0;

          $("#add-option").click(function () {
                    var option_to_add = $("#opciones_encuesta").val();
                    if (option_to_add !== "") {
                              array_opciones_encuesta.push(option_to_add);
                              sessionStorage.setItem("opciones", JSON.stringify(array_opciones_encuesta));
                              $("form").append('<input style="display: none;" type="text" name="opcion_encuesta' + array_opciones_encuesta.length + '" id="opcion_encuesta' + array_opciones_encuesta.length + '">');
                              $('#opcion_encuesta' + array_opciones_encuesta.length).val(array_opciones_encuesta[indice_opciones]);
                              indice_opciones++;
                              showNotification('Opción añadida correctamente.')
                    } else {
                              showNotification('No lo puedes dejar en blanco')
                    }
                    $("#opciones_encuesta").val("");
                    if (array_opciones_encuesta.length >= 2 && $("#send-poll").length === 0) {
                              $("form").append('<button type="submit" id="send-poll" class="button button-login">Enviar encuesta</button>');
                    }
          });
});

$("#send-poll").click(function () {
          array_opciones_encuesta = [];
          sessionStorage.removeItem("opciones");
});

function showNotification(message) {
          var notificationContainer = $("#notification-container");

          var notificationDiv = $("<div>").addClass("notification");
          notificationDiv.text(message);

          var closeButton = $("<button>").addClass("close-button");
          closeButton.html("&times;");
          closeButton.click(function () {
          notificationDiv.remove();
          });

          notificationDiv.append(closeButton);

          notificationContainer.append(notificationDiv);
          notificationContainer.animate({ scrollTop: notificationContainer.prop("scrollHeight") }, 500);
};