<?php
require_once("../database/conexion.php");

class Empleados {
    private $conexion;

    function __construct($conex) {
        $this->conexion = $conex;
    }

    function insertar($nombre, $puesto, $id_dependencia) {
        $sql = "INSERT INTO empleado (nombre, puesto, id_dependencia)
                VALUES ('$nombre', '$puesto', $id_dependencia)";
        return $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT * FROM empleado ORDER BY id_empleado";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $nombre, $puesto, $id_dependencia) {
        $sql = "UPDATE empleado 
                SET nombre='$nombre', puesto='$puesto', id_dependencia=$id_dependencia
                WHERE id_empleado=$id";
        return $this->conexion->ejecutar($sql);
    }

    
    function eliminar($id) {
        $id = (int)$id;
        $sql = "DELETE FROM empleado WHERE id_empleado = $id";

        $resultado = @pg_query($this->conexion->getConexion(), $sql);

        if ($resultado === false) {

            $error = pg_last_error($this->conexion->getConexion());

            
            if (strpos($error, "foreign key") !== false ||
                strpos($error, "violates") !== false) {

                return ["error" => true, "detalle" => "LLAVE_FORANEA"];
            }

            return ["error" => true, "detalle" => $error];
        }

        return ["error" => false];
    }

    function buscar($id) {
        $sql = "SELECT * FROM empleado WHERE id_empleado = $id";
        return $this->conexion->ejecutar($sql);
    }
}
?>
