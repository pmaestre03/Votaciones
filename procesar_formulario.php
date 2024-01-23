<?php
// Conectar a la base de datos
$conn = mysqli_connect('localhost', 'aleix', 'Caqjuueeemke64*', 'votaciones');

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Recuperar datos del formulario
$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
$mail = mysqli_real_escape_string($conn, $_POST['mail']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash de la contraseña (se recomienda)
$pais = mysqli_real_escape_string($conn, $_POST['pais']);
$telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
$ciudad = mysqli_real_escape_string($conn, $_POST['ciudad']);
$codigoPostal = mysqli_real_escape_string($conn, $_POST['codigoPostal']);

// Consulta SQL para insertar los datos
$query = "INSERT INTO tu_tabla (nombre, mail, password, pais, telefono, ciudad, codigo_postal) VALUES ('$nombre', '$mail', '$password', '$pais', '$telefono', '$ciudad', '$codigoPostal')";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Datos insertados correctamente.";
} else {
    echo "Error al insertar datos: " . mysqli_error($conn);
}

// Cerrar la conexión
mysqli_close($conn);
?>
