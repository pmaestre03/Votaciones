<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Encuesta</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
        <script src="Utilidades/scripts.js"></script>
</head>
<?php require('Utilidades/scripts2.php')?>
<body class="create_poll">
<?php include("Utilidades/conexion.php") ?>
<?php include("Utilidades/header.php") ?>

<div id="notification-container"></div>
<?php
if (isset($_SESSION['usuario'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar si se recibieron opciones de encuesta
        if (isset($_POST['titulo']) && isset($_POST['inicio']) && isset($_POST['final']) && isset($_POST['option'])) {

            // Recibir y limpiar los datos del formulario
            $creador = $_SESSION['id_user'];
            $titulo_encuesta = $_POST["titulo"];
            $fecha_inicio = date("Y-m-d H:i:s", strtotime($_POST["inicio"]));
            $fecha_fin = date("Y-m-d H:i:s", strtotime($_POST["final"]));

            try {
                $dsn = "mysql:host=localhost;dbname=votaciones";
                $pdo = new PDO($dsn, 'userProyecto', 'votacionesAXP24');
                $query = $pdo->prepare("INSERT INTO encuestas (fech_inicio, fecha_fin, titulo_encuesta, creador, imagen_titulo) VALUES (:fech_inicio, :fecha_fin, :titulo_encuesta, :creador, :imagen_titulo)");
                $query->bindParam(':fech_inicio', $fecha_inicio, PDO::PARAM_STR);
                $query->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $query->bindParam(':titulo_encuesta', $titulo_encuesta, PDO::PARAM_STR);
                $query->bindParam(':creador', $creador, PDO::PARAM_INT);

                // Mover el archivo de imagen del título a la carpeta 'uploads'
                if(isset($_FILES["imgTitulo"]) && $_FILES["imgTitulo"]["error"] == 0){
                    $target_dir = "uploads/";
                    $target_file = $target_dir . time() . "_" . basename($_FILES["imgTitulo"]["name"]);

                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    if ($_FILES["imgTitulo"]["size"] < 500000) {
                        if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
                            if (move_uploaded_file($_FILES["imgTitulo"]["tmp_name"], $target_file)) {
                                $query->bindParam(':imagen_titulo', $target_file, PDO::PARAM_STR);
                            } else {
                                echo "Hubo un error al subir el archivo.";
                                $query->bindValue(':imagen_titulo', NULL, PDO::PARAM_NULL);
                            }
                        } else {
                            echo "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
                            $query->bindValue(':imagen_titulo', NULL, PDO::PARAM_NULL);
                        }
                    } else {
                        echo "El archivo es demasiado grande.";
                        $query->bindValue(':imagen_titulo', NULL, PDO::PARAM_NULL);
                    }
                } else {
                    $query->bindValue(':imagen_titulo', NULL, PDO::PARAM_NULL);
                }
                $query->execute();

                $id_encuesta = $pdo->lastInsertId();
                $options = isset($_POST["option"]) ? $_POST["option"] : [];

                if (!empty($options)) {
                    foreach ($options as $key => $option) {
                        $option_query = $pdo->prepare("INSERT INTO opciones_encuestas (id_encuesta, nombre_opciones, imagen_opciones) VALUES (:id_encuesta, :nombre_opciones, :imagen_opciones)");
                        $option_query->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
                        $option_query->bindParam(':nombre_opciones', $option, PDO::PARAM_STR);

                        if (!empty($_FILES["imgOpcion" . ($key + 1)]["name"])) {
                            $target_dir = "uploads/";
                            $target_file = $target_dir . time() . "_" . basename($_FILES["imgOpcion" . ($key + 1)]["name"]);

                            if (move_uploaded_file($_FILES["imgOpcion" . ($key + 1)]["tmp_name"], $target_file)) {
                                $option_query->bindParam(':imagen_opciones', $target_file, PDO::PARAM_STR);
                            } else {
                                $option_query->bindValue(':imagen_opciones', NULL, PDO::PARAM_NULL);
                            }
                        } else {
                            $option_query->bindValue(':imagen_opciones', NULL, PDO::PARAM_NULL);
                        }

                        $option_query->execute();

                        if ($option_query->rowCount() > 0) {
                            // La opción se insertó correctamente
                        } else {
                            echo "Error al insertar la opción '$option'<br>";
                        }
                    }
                }

            } catch (PDOException $e) {
                echo "Error en la base de datos: " . $e->getMessage();
            }
        }
    }
} else {
    header("Location: ../errores/error403.php");
    http_response(403);
    exit;
}

?>
<?php include("Utilidades/footer.php") ?>
</body>
</html>
