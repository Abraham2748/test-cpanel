<?php

require_once('classes/auth.class.php');
require_once('classes/responses.class.php');

$_auth = new auth;
$_responses = new responses;

header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = file_get_contents("php://input");
    $data = $_auth->login($postBody);
    if (isset($data["result"]["error_id"])) {
        $responseCode = $data["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
} else {
    $data = $_responses->error_405();
}

echo json_encode($data);
