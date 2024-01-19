<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Utilidades/styles.css">
</head>
<body>
    <?php include("header.php") ?>

    <div class="content-paragraph">
        <p>
            Errores o Informacíon
        </p>
    </div>

    <div class="login-container">
        <form action="process_login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="button button-login">Login</button>
        </form>
    </div>

    <?php include("footer.php") ?>
</body>
</html>