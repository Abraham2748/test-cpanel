<?php

require_once "clases/conexion/conexion.php";

$conexion = new conexion;

$query = "SELECT * FROM pacientes";

print_r($conexion->obtenerDatos($query));

?>