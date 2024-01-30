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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['accept-conditions'])) {
                                        $correo = $_POST['username'];
                                        $contrasenya = $_POST['password'];
                                        $querystr = "SELECT nombre,email,token_validado,condiciones_aceptadas FROM users WHERE email=:usuario AND contrasea_cifrada=:contrasenya";
                                        $query = $pdo->prepare($querystr);
                                        $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                                        $query->bindParam(':contrasenya', $contrasenya, PDO::PARAM_STR);
                                        $query->execute();
                                        $filas = $query->rowCount();
                                        $usuario = htmlspecialchars($_POST["username"]);
                                        $row = $query->fetch(PDO::FETCH_ASSOC);
                                        $condiciones_aceptadas = $row['condiciones_aceptadas'];
                                        $nombre_usuario = $row['nombre'];
                                        $_SESSION['email'] = $row["email"];
                                        $_SESSION['usuario'] = $nombre_usuario;
                                        $_SESSION['id_user'] = $row["id_user"];
                                        $updateQuery = "UPDATE users SET condiciones_aceptadas = 1 WHERE email=:usuario";
                                        $updateStatement = $pdo->prepare($updateQuery);
                                        $updateStatement->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                                        $updateStatement->execute();
                                        registrarEvento("Inicio de sesión por el usuario: $usuario");
                                        header("Location: dashboard.php");
                                        exit();
                    } elseif (isset($_POST['reject-conditions'])) {
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
