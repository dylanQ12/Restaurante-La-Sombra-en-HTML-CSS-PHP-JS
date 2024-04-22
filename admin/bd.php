<?php
$server = "localhost";
$bd_name = "restaurante";
$user = "root";
$password = "";

try {
    $conexion = new PDO("mysql:host=$server;dbname=$bd_name", $user, $password);
} catch (Exception $error) {
    echo $error->getMessage();
}
