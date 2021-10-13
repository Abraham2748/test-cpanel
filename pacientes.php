<?php

require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
        if (isset($_GET["rowsPerPage"])) $rowsPerPage = $_GET["rowsPerPage"];
        $listaPacientes = $_pacientes->listaPacientes($page, $rowsPerPage);
        echo json_encode($listaPacientes);
        http_response_code(200);
    } else if (isset($_GET["id"])) {
        $pacienteId = $_GET["id"];
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteId);
        echo json_encode($datosPaciente);
        http_response_code(200);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = file_get_contents("php://input");
    $response = $_pacientes->agregarPaciente($postBody);
    echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    echo 'Hello PUT';
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo 'Hello DELETE';
} else {
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
