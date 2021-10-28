<?php

require_once('classes/auth.class.php');
require_once 'classes/responses.class.php';
require_once 'classes/patient.class.php';

$_auth = new auth;
$_responses = new Responses;
$_patient = new Patient;


$headers = getallheaders();

if (!isset($headers["Authorization"])) {
    echo json_encode($_responses->error_401());
    http_response_code(401);
    return;
} else {
    $authorized = $_auth->validateToken($headers["Authorization"]);
    echo json_encode($authorized);
    return;
    if (!$authorized) {
        echo json_encode($_responses->error_401());
        http_response_code(401);
        return;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
        if (isset($_GET["rowsPerPage"])) $rowsPerPage = $_GET["rowsPerPage"];
        $listOfPatients = $_patient->getListOfPatients($page, $rowsPerPage);
        echo json_encode($listOfPatients);
        http_response_code(200);
    } else if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $patientData = $_patient->getPatient($id);
        echo json_encode($patientData);
        http_response_code(200);
    } else {
        echo json_encode($_responses->error_200());
        http_response_code(200);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = file_get_contents("php://input");
    $response = $_patient->addPatient($postBody);
    echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $postBody = file_get_contents("php://input");
    $response = $_patient->updatePatient($postBody);
    echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['id'])) {
        $id = $_GET["id"];
        $response = $_patient->deletePatient($id);
        echo json_encode($response);
        http_response_code(200);
    } else {
        echo json_encode($_responses->error_200());
    }
} else {
    echo json_encode($_responses->error_405());
}
