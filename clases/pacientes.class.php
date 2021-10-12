<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion
{

    private $table = "pacientes";
    public function listaPacientes($pagina, $cantidad)
    {
        $inicio = 0;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1));
        }

        $query = "SELECT * FROM " . $this->table . " LIMIT $inicio, $cantidad";
        print_r($query);
    }
}
