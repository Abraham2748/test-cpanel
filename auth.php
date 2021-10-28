<?php

require_once('classes/auth.class.php');
require_once('classes/responses.class.php');

$_auth = new auth;
$_responses = new responses;

header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = file_get_contents("php://input");
    $data = $_auth->login($postBody);
} else {
    $data = $_responses->error_405();
}

echo json_encode($data);
