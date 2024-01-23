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
    session_start();  // Asegúrate de iniciar la sesión
    
    try {
        $hostname = "localhost";
        $dbname = "votaciones";
        $username = "super";
        $pw = "e1ce1uy7nc173?";
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
        echo "<tr><th>Título de la Encuesta</th><th>Fecha de Inicio</th><th>Fecha de Fin</th></tr>";
        
        foreach ($encuestas as $encuesta) {
            echo "<tr>";
            echo "<td>{$encuesta['titulo_encuesta']}</td>";
            echo "<td>{$encuesta['fech_inicio']}</td>";
            echo "<td>{$encuesta['fecha_fin']}</td>";
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
