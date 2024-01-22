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

var notificationIndex = 1;  // Variable para llevar el seguimiento del índice de z-index

function showNotification(message) {
          var notificationContainer = document.getElementById("notification-container");

          // Crear div de notificación
          var notificationDiv = document.createElement("div");
          notificationDiv.className = "notification";

          // Establecer el índice de z-index y actualizar la variable
          notificationDiv.style.zIndex = notificationIndex++;

          // Agregar mensaje
          notificationDiv.innerHTML = message;

          // Crear botón de cerrar
          var closeButton = document.createElement("button");
          closeButton.innerHTML = "Cerrar";
          closeButton.className = "close-button";

          // Agregar evento al botón de cerrar
          closeButton.addEventListener("click", function () {
                    notificationContainer.removeChild(notificationDiv);
          });

          // Agregar botón de cerrar a la notificación
          notificationDiv.appendChild(closeButton);

          // Agregar notificación en la parte superior del contenedor
          notificationContainer.insertBefore(notificationDiv, notificationContainer.firstChild);
}