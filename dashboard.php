<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Utilidades/styles.css?no-cache=<?php echo time(); ?>">
</head>
<body class="dashboard">
<?php include("Utilidades/header.php") ?>
    <?php
        if (isset($_SESSION['usuario'])) {
            
            echo "<div class='user-info'>";
            echo "Panel de Administraci  n";
            echo "</div>";

            echo "<div class='dashboard-container'>";
                echo "<div class='dashboard-box' id='createPolls'>";
                    echo "<h2>Crear Encuestas</h2>";
                echo "</div>";

                // redireccion del div crear encuestas
                echo '<script>';
                    echo '$(document).ready(function() {';
                    echo '    $("#createPolls").on("click", function() {';
                    echo '        window.location.href = "create_poll.php";';
                    echo '    });';
                    echo '});';
                echo '</script>';

                // echo "<div class='dashboard-box'>";
                //     echo "<h2>Invitaciones</h2>";
                // echo "</div>";

                // echo "<div class='dashboard-box'>";
                //     echo "<h2>Editar Encuestas</h2>";
                // echo "</div>";

                echo "<div class='dashboard-box' id='listPolls'>";
                    echo "<h2>Listar Encuestas</h2>";
                echo "</div>";

                // redireccion del div crear encuestas
                echo '<script>';
                    echo '$(document).ready(function() {';
                    echo '    $("#listPolls").on("click", function() {';
                    echo '        window.location.href = "list_polls.php";';
                    echo '    });';
                    echo '});';
                echo '</script>';

            echo "</div>";
        } else {
            //header("HTTP/1.1 403 Forbidden");
            header("Location: ../errores/error403.php");
            exit;
}
        ?>
<?php include("Utilidades/footer.php") ?>
</body>
</html>
