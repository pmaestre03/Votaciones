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
<?php include("Utilidades/conexion.php") ?>
<?php include("header.php") ?>

<div class="content-paragraph">
        <p>
            Errores o Informacíon
        </p>
    </div>
<div class="poll-container">
<form action="" method="post" id="create_poll">
<label for="fecha_inicio">Fecha Inicio</label>
          <input type="date" name="fecha_inicio" id="fecha_inicio" required>
          <label for="fecha_fin">Fecha Fin</label>
          <input type="date" name="fecha_fin" id="fecha_fin" required>
          <label for="titulo_encuesta" >Titulo Encuesta</label>
          <input type="text" name="titulo_encuesta" id="titulo_encuesta" required>
          <label for="opciones_encuesta">Opciones Encuesta:</label>
          <input type="text" name="opciones_encuesta" id="opciones_encuesta">
          <button type="button" id="add-option" class="button button-login">Añadir Opcion</button>

</form>
</div>
<?php include("footer.php") ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $fecha_inicio = date("Y-m-d", strtotime($_POST["fecha_inicio"]));
          $fecha_fin = date("Y-m-d", strtotime($_POST["fecha_fin"]));
          $encuesta = "insert into encuestas(titulo_encuesta,creador,fech_inicio,fecha_fin) values('" . $_POST["titulo_encuesta"] . "',1,'" . $fecha_inicio . "','" . $fecha_fin . "')";
          $resultat_enquesta = mysqli_query($conn, $encuesta);
          foreach ($_POST as $key => $value) {
                    if (preg_match('/^opcion_encuesta\d+$/', $key)) {
                              $opciones = "insert into opciones_encuestas(nombre_opciones,id_encuesta) values('" . $value . "',(SELECT max(id_encuesta) from encuestas))";
                              $resultat_opciones = mysqli_query($conn, $opciones);
                    }
          }

}
?>

</body>
</html>