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
    session_start();

    try {
        $hostname = "localhost";
        $dbname = "Votaciones";
        $username = "xavi";
        $pw = "Superlocal123@";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

        // $conn = mysqli_connect('localhost','xavi','Superlocal123@');
        // mysqli_select_db($conn, 'Votaciones');

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
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $nombre_usuario = $row['nombre'];

            $_SESSION['usuario'] = $nombre_usuario;
            echo "Usuario Correcto: Hola $nombre_usuario";
            header("Location: dashboard.php");
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
