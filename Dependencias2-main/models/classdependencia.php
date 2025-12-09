<?php

require_once(__DIR__ . "/../database/conexion.php");
class Dependencias {
    private $conexion;

    function __construct($conex) {
        $this->conexion = $conex;
    }

    function insertar($nombre, $responsable) {
        $nombre = addslashes($nombre);
        $responsable = addslashes($responsable);
        $sql = "INSERT INTO dependencia (nombre, responsable) VALUES ('{$nombre}', '{$responsable}')";
        return $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT * FROM dependencia ORDER BY id_dependencia";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $nombre, $responsable) {
        $nombre = addslashes($nombre);
        $responsable = addslashes($responsable);
        $id = (int)$id;
        $sql = "UPDATE dependencia SET nombre='{$nombre}', responsable='{$responsable}' WHERE id_dependencia={$id}";
        return $this->conexion->ejecutar($sql);
    }

   function eliminar($id) {
    $sql = "DELETE FROM dependencia WHERE id_dependencia = $id";
    $resultado = $this->conexion->ejecutar($sql);
    return $resultado;
}

    function buscar($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM dependencia WHERE id_dependencia={$id}";
        return $this->conexion->ejecutar($sql);
    }
}
?>