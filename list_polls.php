<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Sitio Web</title>
    <link rel="stylesheet" href="Utilidades/styles.css">
    <script src="Utilidades/scripts.js"></script>
</head>
<body class="index">
    <?php include("header.php") ?>
    <div id="notification-container"></div>
    <?php
   
    
    try {
	$hostname = "localhost";
        $dbname = "votaciones";
        $username = "userVotaciones";
        $pw = "P@ssw0rd";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $user = $_SESSION["usuario"];
    $listar = 'SELECT titulo_encuesta, fech_inicio, fecha_fin FROM encuestas WHERE creador = (SELECT id_user FROM users WHERE nombre=:usuario);';

    $stmt = $pdo->prepare($listar);
    $stmt->bindParam(':usuario', $user, PDO::PARAM_STR);
    $stmt->execute();

    $encuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($encuestas) {
        echo "<h2>Encuestas creadas</h2>";
        echo "<div class='center'>";
        echo "<table border='1'>";
        echo "<tr><th>Título de la Encuesta</th><th>Fecha Inicio</th><th>Fecha Fin</th>";
        foreach ($encuestas as $encuesta) {
            echo "<tr>";
            echo "<td>{$encuesta['titulo_encuesta']}</td>";
            echo "<td>{$encuesta['fech_inicio']}</td>";
	    echo "<td>{$encuesta['fecha_fin']}</td>";
        //$fechaActual = strtotime(date("Y-m-d"));
        //$inicioEncuesta = strtotime($encuesta['fech_inicio']);
        //$finEncuesta = strtotime($encuesta['fecha_fin']);

	    //if ($fechaActual >= $inicioEncuesta && $fechaActual <= $finEncuesta) {
	    //echo "<td class='publica'>Publicada</td>"; 
 	    //} if ($fechaActual < $inicioEncuesta) {
	    //echo "<td class='oculta'>Oculta</td>";
	    //} if ($fechaActual > $finEncuesta){
	    //echo "<td class='finalizada'>Finalizada</td>";
	    //}
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<script>showNotification('No hay encuestas creadas','red')</script>";
    }

    unset($pdo);
    unset($stmt);
    ?>

    <?php include("footer.php") ?>
</body>
</html>
