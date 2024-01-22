<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Utilidades/styles.css">
</head>
<body class="login">
    <?php include("header.php") ?>

    <!-- Párrafo Feedback -->
    <div class="content-paragraph">
        <p>
            Errores o Información
        </p>
    </div>

    <!-- Formulario Login -->
    <div class="login-container">
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="button button-login">Login</button>
        </form>
    </div>

    <!-- BBDD -->
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["username"];
        $contrasenya = $_POST["password"];

        $querystr = "SELECT nombre FROM users WHERE email=:usuario AND contrasea_cifrada=md5(:contrasenya)";
        $query = $pdo->prepare($querystr);
        $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $query->bindParam(':contrasenya', $contrasenya, PDO::PARAM_STR);

        $query->execute();

        $filas = $query->rowCount();
        if ($filas > 0) {
            // Obtén el nombre de usuario desde la base de datos
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $nombre_usuario = $row['nombre'];

            $_SESSION['usuario'] = $nombre_usuario;
            echo "Usuario Correcto: Hola $nombre_usuario";
            header("Location: index.php");
            exit();
        } else {
            echo "Usuario o contraseña incorrectos";
        }

        unset($pdo);
        unset($query);
    }
    ?>

    <?php include("footer.php") ?>
</body>
</html>