<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class auth extends conexion
{
    public function login($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (isset($datos['usuario']) && isset($datos["password"])) {
            $usuario = $datos["usuario"];
            $password = md5($datos["password"]);
            $datosUsuario = $this->obtenerDatosUsuario($usuario);
            if ($datosUsuario) {
                if ($password == $datosUsuario[0]["Password"]) {
                    if ($datosUsuario[0]["Estado"] == "Activo") {
                        $token = $this->insertarToken($datosUsuario[0]['UsuarioId']);
                        if ($token) {
                            $result = $_respuestas->response;
                            $result['result'] = array(
                                "token" => $token
                            );
                            return $result;
                        } else {
                            return $_respuestas->error_500();
                        }
                    } else {
                        return $_respuestas->error_200("User $usuario is not active.");
                    }
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

    public function validateToken($token)
    {
        $token = substr($token, 7);
        $query = "SELECT * FROM usuarios_token WHERE Token = '" . $token . "' AND Estado = 'Activo'";
        $result = parent::obtenerDatos($query);
        return sizeof($result) == 1;
    }

    private function obtenerDatosUsuario($correo)
    {
        $query = "SELECT * FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if (isset($datos[0]['UsuarioId'])) {
            return $datos;
        } else {
            return null;
        }
    }

    private function insertarToken($usuarioId)
    {
        $cstrong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $cstrong));
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES ('$usuarioId','$token','$estado', UTC_TIMESTAMP);";
        $existe = parent::nonQuery($query);
        if ($existe) {
            return $token;
        } else {
            return false;
        }
    }
}
