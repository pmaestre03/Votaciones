<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Encuestas</title>
    <link rel="stylesheet" href="Utilidades/styles.css?no-cache=<?php echo time(); ?>">
    <script src="Utilidades/scripts.js"></script>
</head>
<body class="index">
    <?php include("Utilidades/header.php") ?>
    <div id="notification-container"></div>
    <?php
   
    
    try {
        $hostname = "localhost";
        $dbname = "votaciones";
        $username = "userProyecto";
        $pw = "votacionesAXP24";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    if (isset($_SESSION['usuario'])) {
        $user = $_SESSION["email"];
        $listar = 'SELECT id_encuesta, titulo_encuesta, fech_inicio, fecha_fin FROM encuestas WHERE creador = (SELECT id_user FROM users WHERE email=:email);';

        $stmt = $pdo->prepare($listar);
        $stmt->bindParam(':email', $user, PDO::PARAM_STR);
        $stmt->execute();

        $encuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($encuestas) {
            echo "<h2>Encuestas creadas</h2>";
            echo "<div class='center'>";
            echo "<table border='1'>";
            echo "<tr><th>Título de la Encuesta</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Estado</th><th></th><th></th>";
            foreach ($encuestas as $encuesta) {
                echo "<tr>";
                echo "<td>{$encuesta['titulo_encuesta']}</td>";
                echo "<td>{$encuesta['fech_inicio']}</td>";
                echo "<td>{$encuesta['fecha_fin']}</td>";
            $fechaActual = strtotime(date("Y-m-d"));
            $inicioEncuesta = strtotime($encuesta['fech_inicio']);
            $finEncuesta = strtotime($encuesta['fecha_fin']);

                if ($fechaActual >= $inicioEncuesta && $fechaActual <= $finEncuesta) {
                echo "<td class='publica'>Activa</td>"; 
                } if ($fechaActual < $inicioEncuesta) {
                echo "<td class='oculta'>No Activa</td>";
                } if ($fechaActual > $finEncuesta){
                echo "<td class='finalizada'>Finalizada</td>";
                }

                $id_encuesta = $encuesta['id_encuesta'];
                // echo "<td>{$encuesta['id_encuesta']}</td>";

                echo "<td><button onclick=\"window.location.href='graphics.php?id=$id_encuesta'\">Detalles Encuesta</button></td>";
                echo "<td><button onclick=\"window.location.href='invite.php?id=$id_encuesta'\">Enviar Invitaciones</button></td>";

                echo "</tr>";
                

            }

            echo "</table>";
            echo "</div>";
        } else {
            echo "<script>showNotification('No hay encuestas creadas','red')</script>";
        }

        unset($pdo);
        unset($stmt);
        
    }else {
            //header("HTTP/1.1 403 Forbidden");
            header("Location: ../errores/error403.php");
            http_response(403);
            exit;
}

?>

    <?php include("Utilidades/footer.php") ?>
</body>
</html>
