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
        $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
        return parent::obtenerDatos($query);
    }

    public function agregarPaciente($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (isset($datos["nombre"]) && isset($datos["dni"]) && isset($datos["correo"])) {
            return $_respuestas->error_400();
        }
    }
}
