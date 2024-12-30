<?php

//importar la conexión
require 'includes/config/database.php';

$db = conectarDB();

//crear email y password
$email = "correo@correo.com";
$password ='123456';
// Hashear el password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);


//query para crear el usuario
$query = "INSERT INTO usuarios (email, password) VALUES ('{$email}', '{$passwordHash}')";
echo $query;

//agregarlo a la base de datos
mysqli_query($db, $query);