<?php include("Utilidades/header.php") ?>
<?php require('Utilidades/scripts2.php')?>
<div id="notification-container"></div>
<div class="login-container">
                <form method="post">
<?php 

try {
                    $pdo = new PDO('mysql:host=localhost;dbname=votaciones', 'userProyecto', 'votacionesAXP24');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
                    die("Error en la conexión a la base de datos: " . $e->getMessage());
}
$id_encuesta = $_SESSION["id_encuesta"];
$find_poll = "SELECT titulo_encuesta,estado_publicacion,estado_visibilidad FROM encuestas where id_encuesta=:id_encuesta";
$query = $pdo->prepare($find_poll);
$query->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_STR);
$query->execute();
$filas = $query->rowCount();

if ($filas > 0) {
                    $row = $query->fetch(PDO::FETCH_ASSOC);
                    $titulo_encuesta = $row['titulo_encuesta'];
                    echo "<h1>".$titulo_encuesta."</h1>";
}
?>
<label for="visibilidad_encuesta">Visibilidad de la Encuesta:</label>
        <select name="visibilidad_encuesta" id="visibilidad_encuesta" onchange="actualizarOpcionesRespuestas()">
            <option value="oculta">Oculta</option>
            <option value="privada">Privada</option>
            <option value="publica">Pública</option>
        </select>

        <br>

        <label for="visibilidad_respuestas">Visibilidad de las Respuestas:</label>
        <select name="visibilidad_respuestas" id="visibilidad_respuestas">
            <!-- Las opciones se actualizarán dinámicamente mediante JavaScript -->
        </select>

        <br>

        <input type="submit" value="Enviar">
    </form>
</div>
<?php include("Utilidades/footer.php") ?>
<script src="Utilidades/scripts.js"></script>