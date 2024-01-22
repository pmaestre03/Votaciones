<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #notification-container {
            position: fixed;
            bottom: 10px;
            right: 10px;
            max-width: 300px;
            z-index: 9999; /* Asegura que esté en la parte superior */
        }

        .notification {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .close-button {
            background-color: #555;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            float: right;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            var notificationIndex = 1;

            function showNotification(message) {
                var notificationContainer = $("#notification-container");

                var notificationDiv = $("<div>").addClass("notification");
                notificationDiv.text(message);

                var closeButton = $("<button>").addClass("close-button");
                closeButton.text("Cerrar");
                closeButton.click(function () {
                    notificationDiv.remove();
                });

                notificationDiv.append(closeButton);

                notificationContainer.append(notificationDiv);
                notificationContainer.animate({ scrollTop: notificationContainer.prop("scrollHeight") }, 500);
            }
            $("#add-notification").click(function () {
                showNotification('Nueva notificación añadida.');
            });
        });
    </script>
</head>
<body>

<div id="notification-container"></div>

<button id="add-notification">Añadir Notificación</button>

</body>

</html>
