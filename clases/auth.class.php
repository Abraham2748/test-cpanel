<?php

require_once "connection/connection.php";
require_once "respuestas.class.php";

class auth extends Connection
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
        $query = "SELECT * FROM UserToken WHERE Token = '" . $token . "' AND Estado = 'Activo' AND Fecha > DATE_SUB(UTC_TIMESTAMP, INTERVAL 1 HOUR)";
        $result = parent::getData($query);
        if (sizeof($result) == 1) {
            $query = "UPDATE UserToken SET Fecha = UTC_TIMESTAMP WHERE Token = '" . $token . "'";
            parent::nonQuery($query);
            return true;
        } else {
            return false;
        }
    }

    private function obtenerDatosUsuario($correo)
    {
        $query = "SELECT * FROM User WHERE Usuario = '$correo'";
        $datos = parent::getData($query);
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
        $query = "INSERT INTO UserToken (UsuarioId, Token, Estado, Fecha) VALUES ('$usuarioId','$token','$estado', UTC_TIMESTAMP);";
        $existe = parent::nonQuery($query);
        if ($existe) {
            return $token;
        } else {
            return false;
        }
    }
}
