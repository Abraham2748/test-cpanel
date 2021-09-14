<?php

require_once('clases/auth.class.php');
require_once('clases/respuestas.class.php');

$_respuestas = new respuestas;

if(_SERVER['REQUEST_METHOD'] == 'POST'){
    
} else {
    echo "Method not allowed";
}

?>