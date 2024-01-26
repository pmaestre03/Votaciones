<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphics</title>
    <link rel="stylesheet" href="Utilidades/styles.css?no-cache=<?php echo time(); ?>">
    <script src="Utilidades/scripts.js"></script>
</head>
<body class="graphics">
    <?php include("Utilidades/header.php") ?>
    <?php
    // Obtén el ID del usuario actual desde la sesión
    $id_usuario_actual = $_SESSION['id_user'];

    // Obtén el id_encuesta desde la URL
    if (isset($_GET['id'])) {
        $id_encuesta = intval($_GET['id']);  // Asegúrate de que sea un entero

        // Realiza la conexión a la base de datos
        try {
            $hostname = "localhost";
            $dbname = "Votaciones";
            $username = "xavi";
            $pw = "Superlocal123@";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }

        // Consulta para obtener información específica de la encuesta
        $query = 'SELECT * FROM encuestas WHERE id_encuesta = :id_encuesta';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
        $stmt->execute();

        // Obtén los resultados
        $encuesta = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica si el usuario actual es el creador de la encuesta
        if ($encuesta && $encuesta['creador'] == $id_usuario_actual) {
            // Ahora puedes utilizar $encuesta para mostrar la información de la encuesta
            echo "<h1>Detalles de la Encuesta {$encuesta['titulo_encuesta']}</h1>";
            echo "<p>Fecha de inicio: {$encuesta['fech_inicio']}</p>";
            echo "<p>Fecha de fin: {$encuesta['fecha_fin']}</p>";

            // Agrega un contenedor para el gráfico
            echo '<div style="width: 80%; margin: auto;"><canvas id="graficoBarras"></canvas></div>';

            echo "
            <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Configuración para el gráfico de barras
                    var ctxBar = document.getElementById('graficoBarras').getContext('2d');
                    var chartBar = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: ['Opción 1', 'Opción 2', 'Opción 3'],  // Etiquetas para el eje X
                            datasets: [{
                                label: 'Cantidad de Votos',
                                data: [10, 20, 15],  // Datos para las barras
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>";
            // Resto de la lógica para mostrar gráficos o información adicional
        } else {
            // Manejo de error si el usuario actual no es el creador de la encuesta
            //ESTARIA BIEN ERROR APACHE
            echo "<p>Error: No tienes permisos para acceder a esta encuesta.</p>";
            echo "<script>showNotification('No tienes permisos para acceder a esta encuesta','red')</script>";
        }

        unset($pdo);
    } else {
        // Manejo de error si no se proporcionó el parámetro 'id'

        //ESTARIA BIEN ERROR APACHE
        echo "Error: No se proporcionó el parámetro 'id'.";
    }
    ?>

    <?php include("Utilidades/footer.php") ?>
</body>
</html>
