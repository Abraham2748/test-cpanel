<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class auth extends conexion {
    public function login($json) {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if(isset($datos['usuario']) && isset($datos["password"])) {
            $usuario = $datos["usuario"];
            $password = md5($datos["password"]);
            $datosUsuario = $this->obtenerDatosUsuario($usuario);
            return array("local" => $password, "remote" => $datosUsuario[0]["Password"]);
            if($datosUsuario) {
                if($password == $datosUsuario[0]["Password"]) {
                    return $datosUsuario;
                } else {
                    return $_respuestas->error_200("Incorrect password");
                }
            } else {
                return $_respuestas->error_200("User $usuario does not exist");
            }
        } else {
            return $_respuestas->error_400();
        }
    }

    private function obtenerDatosUsuario($correo) {
        $query = "SELECT * FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]['UsuarioId'])) {
            return $datos;
        } else {
            return null;
        }
    }
}

?>