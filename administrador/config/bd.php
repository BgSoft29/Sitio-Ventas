<?php
    $host = 'localhost';
    $bd = 'sitioventas';
    $usuario = 'root';
    $contrasenia = '';

    try {
        $conexion = new PDO("mysql:host=$host; dbname=$bd",$usuario, $contrasenia);
        // if ($conexion){ echo "conectado a sistema"; }
    } catch ( Exception $ex ) {//por si llega a fallar para moestrar mensaje del error
        echo $ex->getMessage();
        }

?>