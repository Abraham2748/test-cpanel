<?php

require_once('clases/conexion/auth.class.php');
require_once('clases/conexion/respuestas.class.php');

$_respuestas = new respuestas;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $postBody = file_get_contents("php://input");
    print_r($postBody);
} else {
    echo "Method not allowed";
}

?>