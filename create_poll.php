<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Encuesta</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
        <script src="Utilidades/scripts.js"></script>
</head>
<?php require('Utilidades/scripts2.php')?>
<body class="create_poll">
<?php include("Utilidades/conexion.php") ?>
<?php include("Utilidades/header.php") ?>

<div id="notification-container"></div>
<?php
if (isset($_SESSION['usuario'])) {
}else {
        header("Location: ../errores/error403.php");
            http_response(403);
            exit;
}
?>
<?php include("Utilidades/footer.php") ?>
</body>
</html>
