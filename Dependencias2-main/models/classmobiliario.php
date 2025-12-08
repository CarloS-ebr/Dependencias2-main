<?php

require_once(__DIR__ . "/../database/conexion.php");
class Mobiliario {
    private $conexion;

    function __construct($conex) {
        $this->conexion = $conex;
    }

    function insertar($nombre, $inventario, $id_dependencia) {
        $nombre = addslashes($nombre);
        $inventario = addslashes($inventario);
        $id = (int)$id_dependencia;
        $sql = "INSERT INTO mobiliario (nombre, numero_inventario, id_dependencia) VALUES ('{$nombre}', '{$inventario}', {$id})";
        return $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT m.id_mobiliario, m.nombre, m.numero_inventario, d.nombre as dependencia FROM mobiliario m LEFT JOIN dependencia d ON m.id_dependencia = d.id_dependencia ORDER BY m.id_mobiliario";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $nombre, $inventario, $id_dependencia) {
        $nombre = addslashes($nombre);
        $inventario = addslashes($inventario);
        $id = (int)$id;
        $id_dep = (int)$id_dependencia;
        $sql = "UPDATE mobiliario SET nombre='{$nombre}', numero_inventario='{$inventario}', id_dependencia={$id_dep} WHERE id_mobiliario={$id}";
        return $this->conexion->ejecutar($sql);
    }

    function eliminar($id) {
        $id = (int)$id;
        $sql = "DELETE FROM mobiliario WHERE id_mobiliario={$id}";
        return $this->conexion->ejecutar($sql);
    }

    function buscar($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM mobiliario WHERE id_mobiliario={$id}";
        return $this->conexion->ejecutar($sql);
    }
}
?>