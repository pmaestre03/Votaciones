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
                $query = $pdo->prepare("INSERT INTO encuestas (fech_inicio, fecha_fin, titulo_encuesta, creador) VALUES (:fech_inicio, :fecha_fin, :titulo_encuesta, :creador)");
                $query->bindParam(':fech_inicio', $fecha_inicio, PDO::PARAM_STR);
                $query->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $query->bindParam(':titulo_encuesta', $titulo_encuesta, PDO::PARAM_STR);
                $query->bindParam(':creador', $creador, PDO::PARAM_INT);
                $query->execute();
            
                $id_encuesta = $pdo->lastInsertId();
                $options = isset($_POST["option"]) ? $_POST["option"] : [];

                if (!empty($options)) {
                    foreach ($options as $option) {
                        $option_query = $pdo->prepare("INSERT INTO opciones_encuestas (id_encuesta, nombre_opciones) VALUES (:id_encuesta, :nombre_opciones)");
                        $option_query->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
                        $option_query->bindParam(':nombre_opciones', $option, PDO::PARAM_STR);
                        $option_query->execute();
            
                        if ($option_query->rowCount() > 0) {
                        } else {
                                echo "Error al insertar la opción '$option'<br>";
                            }
                    }
                } 
            
            } catch (PDOException $e) {
                echo "Error en la base de datos: " . $e->getMessage();
            }

            // Mover los archivos de imagen a uploads
            for ($i = 1; $i <= count($options); $i++) {
                $option = $_POST["option"][$i - 1];
                if (!empty($option)) {
                    $target_file = NULL;

                    if(isset($_FILES["imgOpcion$i"]) && $_FILES["imgOpcion$i"]["error"] == 0){
                        $target_dir = "uploads/";
                        $target_file = $target_dir . time() . "_" . basename($_FILES["imgOpcion$i"]["name"]);

                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                        if (!file_exists($target_file)) {
                            // Verificar el tamaño del archivo
                            if ($_FILES["imgOpcion$i"]["size"] < 500000) {
                                // Permitir formatos de archivo
                                if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
                                    // Intentar mover el archivo subido al directorio de destino
                                    if (!move_uploaded_file($_FILES["imgOpcion$i"]["tmp_name"], $target_file)) {
                                        echo "Hubo un error al subir el archivo.";
                                        $target_file = NULL;
                                    }
                                } else {
                                    $_SESSION['error'] = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
                                    custom_log('Error subida imagen', "El usuario $email ha intentado subir un fichero no valido");

                                    header('Location: create_poll.php');
                                    $target_file = NULL;
                                    exit();
                                }
                            } else {
                                echo "El archivo es demasiado grande.";
                                $target_file = NULL;
                            }
                        } else {
                            echo "El archivo ya existe.";
                            $target_file = NULL;
                        }
                    }
                    
                }
            }
        }
    }
}else {
    header("Location: ../errores/error403.php");
        http_response(403);
        exit;
}

?>
<?php include("Utilidades/footer.php") ?>
</body>
</html>
