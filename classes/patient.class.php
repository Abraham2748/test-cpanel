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
            return $this->responses->error_200("Patient not found");
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
        $patient = json_decode($json, true);

        if (isset($patient["id"]) && $this->validatePatient($patient)) {
            $result = parent::callProcedure('SP_UPDATE_PATIENT', array(
                '_id' => $patient["id"],
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
                return $this->responses->ok("Patient updated successfully");
            }
        } else {
            return $this->responses->error_400();
        }
    }


    public function deletePatient($id)
    {
        $result = parent::callProcedure('SP_DELETE_PATIENT', array(
            '_id' => $id,
        ));
        if (isset($result["error_message"])) {
            return $this->responses->error_200($result["error_message"]);
        } else {
            return $this->responses->ok("Patient deleted successfully");
        }
    }
}
