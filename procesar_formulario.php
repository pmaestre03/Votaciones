<?php
// procesar_formulario.php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Recuperar los datos del formulario
    $nombre = $_GET["nombre"];
    $email = $_GET["mail"];
    $password = password_hash($_GET["password"], PASSWORD_DEFAULT);
    $pais = $_GET["pais"];
    $telefono = $_GET["prefijo"] . $_GET["telefono"];
    $ciudad = $_GET["ciudad"];
    $codigoPostal = $_GET["codigoPostal"];
    $rol = "user";

    // Realizar la conexión a la base de datos (ajusta las credenciales según tu entorno)
    $conn = new mysqli('localhost', 'tu_usuario', 'tu_contraseña', 'tu_base_de_datos');

    // Verificar la conexión
    if ($conn->connect_error) {
        die("La conexión a la base de datos falló: " . $conn->connect_error);
    }

    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO users (nombre, email, contraseña_cifrada, nombre_pais, telefono, ciudad, codigoPostal, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Vincular los parámetros
    $stmt->bind_param("ssssssss", $nombre, $email, $password, $pais, $telefono, $ciudad, $codigoPostal, $rol);

    // Ejecutar la consulta
    $stmt->execute();

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    // Devolver una respuesta
    echo "¡Datos insertados correctamente!";
} else {
    // Método no permitido
    echo "Método no permitido";
}
?>
