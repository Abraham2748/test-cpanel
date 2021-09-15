<?php

class respuestas {

    private $response = [
        "status" => "ok",
        "result" => array()
    ];

    public function error_405() {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "405",
            "error_msg" => "Method Not Allowed",
        );
        return $this->response;
    }
    
    public function error_200($message = "Incorrect data") {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "200",
            "error_msg" => $message,
        );
        return $this->response;
    }

    public function error_400() {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "400",
            "error_msg" => "Incomplete data",
        );
        return $this->response;
    }
}

?>