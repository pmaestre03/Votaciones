<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recuperar los valores del formulario
    $visibilidadEncuesta = $_POST["visibilidad_encuesta"];
    $visibilidadRespuestas = $_POST["visibilidad_respuestas"];

    // Validar y procesar la consulta SQL
    if ($visibilidadRespuestas === "oculta" || $visibilidadRespuestas === "privada" || $visibilidadRespuestas === "publica") {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'tu_usuario', 'tu_contraseña');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Realizar la consulta SQL aquí
            $query = $pdo->prepare("INSERT INTO tu_tabla (visibilidad_encuesta, visibilidad_respuestas) VALUES (:encuesta, :respuestas)");
            $query->bindParam(':encuesta', $visibilidadEncuesta, PDO::PARAM_STR);
            $query->bindParam(':respuestas', $visibilidadRespuestas, PDO::PARAM_STR);
            $query->execute();

            // Resto del código...

        } catch (PDOException $e) {
            echo "Error en la conexión a la base de datos: " . $e->getMessage();
        }
    } else {
        echo "Error: Opción no válida para la visibilidad de las respuestas.";
    }
}
?>
