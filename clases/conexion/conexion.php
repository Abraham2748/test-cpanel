<?php

class conexion {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;

    function __construct() {
        $listadatos = $this->datos_conexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if($this->conexion->connect_errno) {
            echo "Error connecting";
            die();
        }
    }

    private function datos_conexion() {
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config");
        return json_decode($jsondata, true);
    }

    private function convertirUTF8($array) {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    public function obtenerDatos($sql_query) {
        $results = $this->conexion->query($sql_query);
        $resultArray = array();
        foreach ($results as $key => $value) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }
}

?>