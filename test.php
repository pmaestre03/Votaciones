<?php
    $mysqli_conexion = new mysqli("localhost", "root", "root", "votaciones");
    if($mysqli_conexion->connect_errno) {
        echo "Error de conexión con la base de datos: " . $mysqli_conexion->connect_errno;	
    } else {
        echo "Hemos podido conectarnos con MySQL";
    }
?>