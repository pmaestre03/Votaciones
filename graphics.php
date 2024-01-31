<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>
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
            $dbname = "votaciones";
            $username = "userProyecto";
            $pw = "votacionesAXP24";
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
            echo "<h1 id='pollName'>Detalles de la Encuesta, {$encuesta['titulo_encuesta']}</h1>";
            // echo "<div id='dates'><p>Fecha de inicio: {$encuesta['fech_inicio']}</p>";
            // echo "<p>Fecha de fin: {$encuesta['fecha_fin']}</p></div>";

            echo '<div class="container">';

            // Gráfico de barras a la izquierda
            echo '<div class="chart-container"><p>Gráfico de Barras</p><canvas id="graficoBarras"></canvas></div>';

            echo "
            <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Configuración para el gráfico de barras
                    var ctxBar = document.getElementById('graficoBarras').getContext('2d');
                    var chartBar = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: ['Opción 1', 'Opción 2', 'Opción 3'],
                            datasets: [{
                                label: 'Cantidad de Votos',
                                data: [10, 50, 15],
                                backgroundColor: ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)'],
                                borderColor: ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)'],
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

            // Gráfico de quesos a la derecha
            echo '<div class="chart-container" id="graficoQueso-container"><p>Gráfico de Anillo</p><canvas id="graficoQueso"></canvas></div>';

            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Configuración para el gráfico de quesos
                    var ctxQueso = document.getElementById('graficoQueso').getContext('2d');
                    var chartQueso = new Chart(ctxQueso, {
                        type: 'doughnut',
                        data: {
                            labels: ['Opción 1', 'Opción 2', 'Opción 3'],
                            datasets: [{
                                label: 'Cantidad de Votos',
                                data: [10, 20, 15],
                                backgroundColor: ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)'],
                                borderColor: ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            cutout: '80%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });
                });
            </script>";

            echo '</div>';
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
