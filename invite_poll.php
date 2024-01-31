<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invitar Encuesta</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
        <script src="Utilidades/scripts.js"></script>
</head>
<?php require('Utilidades/scripts2.php')?>
<?php include("Utilidades/header.php") ?>
<?php include("Utilidades/conexion.php") ?>
<body class="invite_poll">

<div id="notification-container"></div>
<?php
if (!isset($_SESSION['id_user'])) {
    header("Location: ../errores/error403.php");
        http_response(403);
        exit;
}
$id_usuario_actual = $_SESSION['id_user'];
if (isset($_GET['id'])) {
    $id_encuesta = intval($_GET['id']);

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
    $query = 'SELECT * FROM encuestas WHERE id_encuesta = :id_encuesta';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
        $stmt->execute();
        $encuesta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($encuesta && $encuesta['creador'] == $id_usuario_actual) {
            echo "<h1 id='pollName'>Invitaciones para: {$encuesta['titulo_encuesta']}</h1>";
        }
}
?>
<?php include("Utilidades/footer.php") ?>
</body>
</html>
