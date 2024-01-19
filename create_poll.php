<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
<head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Document</title>
          <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
          <script src="Utilidades/scripts.js"></script>
</head>
<body class="create_poll">
<?php include("header.php") ?>
<?php include("Utilidades/conexion.php")?>
<div class="content-paragraph">
        <p>
            Errores o Informacíon
        </p>
    </div>
<div class="login-container">
<form action="" method="post" id="create_poll">
          <label for="titulo_encuesta" >Titulo Encuesta</label>
          <input type="text" name="titulo_encuesta" id="titulo_encuesta" required>
          <label for="opciones_encuesta">Opciones Encuesta:</label>
          <input type="text" name="opciones_encuesta" id="opciones_encuesta">
          <button type="button" id="add-option" class="button button-login">Añadir Opcion</button>

</form>
</div>

<?php include("footer.php") ?>
</body>
</html>