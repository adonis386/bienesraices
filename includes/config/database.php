<?php

function conectarDB() {
    $host = 'localhost'; // Cambia esto si tu base de datos está en otro servidor
    $usuario = 'root'; // Cambia esto por tu usuario de la base de datos
    $password = 'admin'; // Cambia esto por tu contraseña de la base de datos
    $db = 'bienesraices'; // Cambia esto por el nombre de tu base de datos

    $conexion = new mysqli($host, $usuario, $password, $db);

    if ($conexion->connect_error) {
        die('Error en la conexión: ' . $conexion->connect_error);
    }

    return $conexion;
}