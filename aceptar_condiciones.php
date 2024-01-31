<?php
if ($_SESSION['condiciones_aceptadas'] = 1) {
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
</head>
<body>
<?php include("Utilidades/header.php") ?>
<div class="login-container">
    <h1>Parece que no has aceptado las condiciones de uso. Aceptalas para poder continuar.</h1>
        <form method="post">
        <button type="submit" class="button button-login" name="accept-conditions">Aceptar</button>
    </form>
        <form method="post">
        <button type="submit" class="button button-login" name="reject-conditions">Rechazar</button>
    </form>
</div>

<?php
session_start(); // Asegúrate de iniciar la sesión
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accept-conditions'])) {
        $correo = $_SESSION['user_form'] ?? "";
        $contrasenya = $_SESSION['password_form'] ?? "";
        echo $_SESSION['email2'];
        echo $_SESSION['usuario2'];
        echo $_SESSION['id_user2'];
        if (isset($_SESSION['email2'], $_SESSION['usuario2'])) {
            $_SESSION['email'] = $_SESSION['email2'];
            $_SESSION['usuario'] = $_SESSION['usuario2'];
            $_SESSION['id_user'] = $_SESSION['id_user2'];
            $_SESSION['condiciones_aceptadas'] = 1;
            $updateQuery = "UPDATE users SET condiciones_aceptadas = 1 WHERE email=:usuario";
            $updateStatement = $pdo->prepare($updateQuery);
            $updateStatement->bindParam(':usuario', $correo, PDO::PARAM_STR);
            $updateStatement->execute();
            registrarEvento("Condiciones aceptadas por el usuario: $usuario");
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Session variables not set properly.";
            exit();
        }
    } elseif (isset($_POST['reject-conditions'])) {
        registrarEvento("Condiciones rechazadas por el usuario: $usuario");
                        session_unset();
                        session_destroy();
                        header("Location: index.php");
                        exit();
    }
}
?>

<?php include("Utilidades/footer.php") ?>
</body>
</html>
