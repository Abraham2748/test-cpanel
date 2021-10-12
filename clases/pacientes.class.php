<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion
{

    private $table = "pacientes";
    public function listaPacientes($pagina)
    {
        $inicio = 0;
        $cantidad = 100;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT * FROM " . $this->table . " LIMIT $inicio, $cantidad";
        print_r($query);
    }
}
