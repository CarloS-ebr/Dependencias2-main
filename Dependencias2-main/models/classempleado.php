<?php
require_once("../database/conexionpg.php");
class Empleados {
    private $conexion;

    function __construct($conex) {
        $this->conexion = $conex;
    }

    function insertar($nombre, $puesto, $id_dependencia) {
        $sql = "INSERT INTO empleado (nombre, puesto, id_dependencia) VALUES ('$nombre', '$puesto', $id_dependencia)";
        $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT * FROM empleado";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $nombre, $puesto, $id_dependencia) {
        $sql = "UPDATE empleado SET nombre='$nombre', puesto='$puesto', id_dependencia=$id_dependencia WHERE id_empleado=$id";
        $this->conexion->ejecutar($sql);
    }

    function eliminar($id) {
        $sql = "DELETE FROM empleado WHERE id_empleado=$id";
        $this->conexion->ejecutar($sql);
    }

    function buscar($id) {
        $sql = "SELECT * FROM empleado WHERE id_empleado=$id";
        return $this->conexion->ejecutar($sql);
    }
}
?>