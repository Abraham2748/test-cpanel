<?php

require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $_pacientes->listaPacientes(3, 3);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo 'Hello POST';
} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    echo 'Hello PUT';
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo 'Hello DELETE';
} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
