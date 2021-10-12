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

        $datos = parent::obtenerDatos($query);
        return $datos;
    }
}
