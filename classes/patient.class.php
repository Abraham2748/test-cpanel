<?php

require_once "connection/connection.php";
require_once "responses.class.php";

class Patient extends Connection
{

    private $table = "Patient";
    private $responses;

    public function __construct()
    {
        $this->responses = new responses;
        parent::__construct();
    }


    public function getPatientList($page = 1, $rowsPerPage = 10)
    {
        $result = parent::callProcedure('SP_GET_PATIENT_LIST', array(
            '_page' => $page,
            '_rowsPerPage' => $rowsPerPage
        ));

        return $this->responses->ok($result);
    }

    public function getPatient($id)
    {
        $result = parent::callProcedure('SP_GET_PATIENT', array(
            '_id' => $id
        ));
        if (count($result) == 1) {
            return $this->responses->ok($result[0]);
        } else {
            return $this->responses->error_200("id not found");
        }
    }

    private function validatePatient($patient)
    {
        return isset($patient["fullName"]) && isset($patient["documentNumber"])
            && isset($patient["email"]) && isset($patient["address"])
            && isset($patient["postalCode"]) && isset($patient["gender"])
            && isset($patient["phoneNumber"]) && isset($patient["birthday"]);
    }

    public function addPatient($json)
    {
        $patient = json_decode($json, true);

        if ($this->validatePatient($patient)) {
            $result = parent::callProcedure('SP_ADD_PATIENT', array(
                '_fullName' => $patient["fullName"],
                '_documentNumber' => $patient["documentNumber"],
                '_email' => $patient["email"],
                '_address' => $patient["address"],
                '_postalCode' => $patient["postalCode"],
                '_gender' => $patient["gender"],
                '_phoneNumber' => $patient["phoneNumber"],
                '_birthday' => $patient["birthday"]
            ));
            if (isset($result["error_message"])) {
                return $this->responses->error_200($result["error_message"]);
            } else {
                return $this->responses->ok("Patient added successfully");
            }
        } else {
            return $this->responses->error_400();
        }
    }

    public function updatePatient($json)
    {
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
                $res = $this->responses->response;
            } else {
                $res = $this->responses->error_500();
            }
            return $res;
        } else {
            return $this->responses->error_400();
        }
    }


    public function deletePatient($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE PacienteId = '$id'";
        $affected_rows = parent::nonQuery($query);

        if ($affected_rows == 1) {
            $res = $this->responses->response;
        } else if ($affected_rows == 0) {
            $res = $this->responses->error_200("id not found");
        } else {
            $res = $this->responses->error_500();
        }
        return $res;
    }
}
