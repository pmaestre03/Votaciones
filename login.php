<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
    <title>Login</title>
    <script src="Utilidades/scripts.js"></script>
</head>
<body class="login">
    <?php include("Utilidades/header.php") ?>

    <!-- Formulario Login -->
    <div class="login-container">
        <form method="post">
            <label for="username">Correo:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contrase  a:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="button button-login">Iniciar Sesi  n</button>
        </form>
    </div>
    <div id="notification-container"></div>
    <!-- BBDD -->
    <?php

    try {
        $hostname = "localhost";
        $dbname = "votaciones";
        $username = "root";
        $pw = "PlataNoEs18";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }  

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["username"];
        $contrasenya = hash('sha512',$_POST["password"]);
        
        $querystr = "SELECT nombre,email FROM users WHERE email=:usuario AND contrasea_cifrada=:contrasenya";
        $query = $pdo->prepare($querystr);
        $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $query->bindParam(':contrasenya', $contrasenya, PDO::PARAM_STR);

        $query->execute();

        $filas = $query->rowCount();
        if ($filas > 0) {
            // Obt  n el nombre de usuario desde la base de datos
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $nombre_usuario = $row['nombre'];
            $_SESSION['email'] = $row["email"];
            $_SESSION['usuario'] = $nombre_usuario;
            echo "Usuario Correcto: Hola $nombre_usuario";
            header("Location: dashboard.php");

            exit();
        } else {
            echo "<script>showNotification('Usuario o contrase  a incorrecto','red')</script>";
        }

        unset($pdo);
        unset($query);
    }
    ?>

    <?php include("Utilidades/footer.php") ?>
</body>
</html>
