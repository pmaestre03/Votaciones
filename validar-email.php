<?php include("Utilidades/conexion.php") ?>
<?php

$token = $_GET['token'];

// Consulta para obtener el ID de usuario asociado al token
$userIdQuery = "SELECT user_id FROM tokens_emails WHERE token = '$token'";
$result = mysqli_query($conn, $userIdQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $userId = $row['user_id'];

    // Marcar el correo como validado en la tabla de usuarios
    $updateUserQuery = "UPDATE users SET token_validado = 1 WHERE id_user = $userId";
    mysqli_query($conn, $updateUserQuery);

    // Eliminar el token utilizado
    $deleteTokenQuery = "DELETE FROM tokens_emails WHERE token = '$token'";
    mysqli_query($conn, $deleteTokenQuery);

    echo "Correo electrónico validado correctamente. Puedes iniciar sesión ahora.";
} else {
    echo "Token no válido o expirado.";
}

?>