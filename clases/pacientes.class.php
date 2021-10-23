<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion
{

    private $table = "pacientes";


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
        $_respuestas = new respuestas;
        $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
        $paciente = parent::obtenerDatos($query);
        if (sizeof($paciente) == 1) {
            $res = $_respuestas->response;
            $res["result"] = $paciente[0];
        } else if (sizeof($paciente) == 0) {
            $res = $_respuestas->error_200("id not found");
        } else {
            $res = $_respuestas->error_500();
        }
        return $res;
    }

    private function validarPaciente($datos)
    {
        return isset($datos["nombre"]) && isset($datos["dni"])
            && isset($datos["correo"]) && isset($datos["direccion"])
            && isset($datos["codigoPostal"]) && isset($datos["genero"])
            && isset($datos["telefono"]) && isset($datos["fechaNacimiento"]);
    }

    public function agregarPaciente($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if ($this->validarPaciente($datos)) {
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

    public function actualizarPaciente($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (isset($datos["pacienteId"]) && $this->validarPaciente($datos)) {
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
                $res = $_respuestas->response;
            } else {
                $res = $_respuestas->error_500();
            }
            return $res;
        } else {
            return $_respuestas->error_400();
        }
    }


    public function eliminarPaciente($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE PacienteId = '$id'";
        $affected_rows = parent::nonQuery($query);
        $_respuestas = new respuestas;
        if ($affected_rows == 1) {
            $res = $_respuestas->response;
        } else {
            $res = $_respuestas->error_500();
        }
        return $res;
    }
}
