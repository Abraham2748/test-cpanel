<?php

require_once "connection/connection.php";
require_once "responses.class.php";

class Patient extends Connection
{

    private $table = "Patient";


    public function getListOfPatients($page = 1, $rowsPerPage = 10)
    {
        $initialRow = 0;
        if ($page > 1) {
            $initialRow = ($rowsPerPage * ($page - 1));
        }

        $query = "SELECT * FROM " . $this->table . " LIMIT $initialRow, $rowsPerPage";

        return parent::getData($query);
    }

    public function getPatient($id)
    {
        $_responses = new responses;
        $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
        $paciente = parent::getData($query);
        if (sizeof($paciente) == 1) {
            $res = $_responses->response;
            $res["result"] = $paciente[0];
        } else if (sizeof($paciente) == 0) {
            $res = $_responses->error_200("id not found");
        } else {
            $res = $_responses->error_500();
        }
        return $res;
    }

    private function validatePatient($datos)
    {
        return isset($datos["nombre"]) && isset($datos["dni"])
            && isset($datos["correo"]) && isset($datos["direccion"])
            && isset($datos["codigoPostal"]) && isset($datos["genero"])
            && isset($datos["telefono"]) && isset($datos["fechaNacimiento"]);
    }

    public function addPatient($json)
    {
        $_responses = new responses;
        $datos = json_decode($json, true);

        if ($this->validatePatient($datos)) {
            $nombre = $datos["nombre"];
            $dni = $datos["dni"];
            $correo = $datos["correo"];
            $direccion = $datos["direccion"];
            $codigoPostal = $datos["codigoPostal"];
            $genero = $datos["genero"];
            $telefono = $datos["telefono"];
            $fechaNacimiento = $datos["fechaNacimiento"];

            $query = "INSERT INTO " . $this->table . " (DNI, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo) VALUES ('"
                . $dni . "', '" . $nombre . "', '" . $direccion . "', '" . $codigoPostal . "', '"
                . $telefono . "', '" . $genero . "', '" . $fechaNacimiento . "', '" . $correo . "');";

            $id = parent::nonQueryId($query);
            if ($id) {
                $res = $_responses->response;
                $res["result"] = array("pacienteId" => $id);
            } else {
                $res = $_responses->error_500();
            }
            return $res;
        } else {
            return $_responses->error_400();
        }
    }

    public function updatePatient($json)
    {
        $_responses = new responses;
        $datos = json_decode($json, true);

        if (isset($datos["pacienteId"]) && $this->validatePatient($datos)) {
            $pacienteId = $datos["pacienteId"];
            $nombre = $datos["nombre"];
            $dni = $datos["dni"];
            $correo = $datos["correo"];
            $direccion = $datos["direccion"];
            $codigoPostal = $datos["codigoPostal"];
            $genero = $datos["genero"];
            $telefono = $datos["telefono"];
            $fechaNacimiento = $datos["fechaNacimiento"];

            $query = "UPDATE " . $this->table . " SET DNI = '" . $dni . "', Nombre = '" . $nombre
                . "', Direccion = '" . $direccion . "', CodigoPostal = '" . $codigoPostal . "', "
                . "Telefono = '" . $telefono . "', Genero = '" . $genero . "', FechaNacimiento = '" . $fechaNacimiento . "', "
                . "Correo = '" . $correo . "' WHERE PacienteId = " . $pacienteId;

            $affected_rows = parent::nonQuery($query);
            if ($affected_rows == 1) {
                $res = $_responses->response;
            } else {
                $res = $_responses->error_500();
            }
            return $res;
        } else {
            return $_responses->error_400();
        }
    }


    public function deletePatient($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE PacienteId = '$id'";
        $affected_rows = parent::nonQuery($query);
        $_responses = new responses;
        if ($affected_rows == 1) {
            $res = $_responses->response;
        } else if ($affected_rows == 0) {
            $res = $_responses->error_200("id not found");
        } else {
            $res = $_responses->error_500();
        }
        return $res;
    }
}