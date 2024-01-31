<!-- invite.php -->
<?php
session_start();

// Verificar la sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../errores/error403.php");
    exit;
}

// Obtener el ID de la encuesta desde la URL
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

    // Consulta SQL para obtener la información de la encuesta
    $consulta_encuesta = 'SELECT id_encuesta, titulo_encuesta FROM encuestas WHERE id_encuesta = :id_encuesta';
    $stmt_encuesta = $pdo->prepare($consulta_encuesta);
    $stmt_encuesta->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
    $stmt_encuesta->execute();

    $datos_encuesta = $stmt_encuesta->fetch(PDO::FETCH_ASSOC);

    if ($datos_encuesta) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Invitaciones - <?php echo $datos_encuesta['titulo_encuesta']; ?></title>
    <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
    <script src="./Utilidades/scripts.js"></script>
</head>
<body class="index">
    <?php include("Utilidades/header.php") ?>
    <div id="notification-container"></div>

    <!-- Formulario para ingresar correos electrónicos -->
    <form method="post" action="process_invitations.php">
        <input type="hidden" name="id_encuesta" value="<?php echo $id_encuesta; ?>">
        <label for="emails">Direcciones de correo electrónico (separadas por coma):</label>
        <input type="text" id="emails" name="emails" required>
        <button type="submit">Enviar Invitaciones</button>
    </form>

    <?php include("Utilidades/footer.php") ?>
</body>
</html>
<?php
    } else {
        echo "Error: No se encontró la encuesta con el ID proporcionado.";
    }

    unset($pdo);
    unset($stmt_encuesta);

} else {
    echo "Error: No se proporcionó el parámetro 'id' para la encuesta.";
}
?>