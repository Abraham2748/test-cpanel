<?php

require_once('clases/conexion/auth.class.php');
require_once('clases/conexion/respuestas.class.php');

$_respuestas = new respuestas;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo "POST Sucessfully"
} else {
    echo "Method not allowed";
}

?>