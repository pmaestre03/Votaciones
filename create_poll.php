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
<?php
// Assuming the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Array to store filtered input values
    $filteredInputs = [];

    // Iterate through all POST variables
    foreach ($_POST as $key => $value) {
        // Check if the input name matches the pattern "opcion_encuesta" followed by a number at the end
        if (preg_match('/^opcion_encuesta\d+$/', $key)) {
            // Add the input to the filtered array
            $filteredInputs[$key] = $value;
            print_r($value." ");
        }
    }

    // Now $filteredInputs contains only the inputs with names starting with "opcion_encuesta" and ending with a number
    
    // Do something with the filtered inputs
    print_r($filteredInputs);
}
?>

<?php include("footer.php") ?>
</body>
</html>