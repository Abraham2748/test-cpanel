<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion
{

    private $table = "pacientes";

    private $pacienteId = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "000-00-00";


    public function listaPacientes($page = 1, $rowsPerPage = 10)
    {
        $initialRow = 0;
        if ($page > 1) {
            $initialRow = ($rowsPerPage * ($page - 1));
        }

        $query = "SELECT * FROM " . $this->table . " LIMIT $initialRow, $rowsPerPage";

        return parent::obtenerDatos($query);
    }

    public function obtenerPaciente($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
        return parent::obtenerDatos($query);
    }

    public function agregarPaciente($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (isset($datos["nombre"]) && isset($datos["dni"]) && isset($datos["correo"])) {
            $this->nombre = $datos["nombre"];
            $this->dni = $datos["dni"];
            $this->correo = $datos["correo"];

            if (isset($datos["direccion"])) $this->direccion = $datos["direccion"];
            if (isset($datos["codigoPostal"])) $this->codigoPostal = $datos["codigoPostal"];
            if (isset($datos["genero"])) $this->genero = $datos["genero"];
            if (isset($datos["telefono"])) $this->telefono = $datos["telefono"];
            if (isset($datos["fechaNacimiento"])) $this->fechaNacimiento = $datos["fechaNacimiento"];

            $query = "INSERT INTO " . $this->table . " (DNI, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo) VALUES ('"
                . $this->dni . "', '" . $this->nombre . "', '" . $this->direccion . "', '" . $this->codigoPostal . "', '"
                . $this->telefono . "', '" . $this->genero . "', '" . $this->fechaNacimiento . "', '" . $this->correo . "');";

            $id = parent::nonQueryId($query);
            if ($id) {
                $res = $_respuestas->response;
                $res["result"] = array("pacienteId" => $id);
            } else {
                $res = $_respuestas->error_500();
            }
            return $res;
        } else {
            return $_respuestas->error_400();
        }
    }
}
