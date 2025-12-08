<?php

require_once(__DIR__ . "/../database/conexion.php");
class Asignaciones {
    private $conexion;

    function __construct($conex) {
        $this->conexion = $conex;
    }

    function insertar($id_empleado, $id_mobiliario, $fecha) {
        $idem = (int)$id_empleado;
        $idm = (int)$id_mobiliario;
        $fecha = addslashes($fecha);
        $sql = "INSERT INTO asignacion (id_empleado, id_mobiliario, fecha) VALUES ({$idem}, {$idm}, '{$fecha}')";
        return $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT a.id_asignacion, a.fecha, e.id_empleado, e.nombre AS empleado, m.id_mobiliario, m.nombre AS mobiliario, m.numero_inventario
                FROM asignacion a
                LEFT JOIN empleado e ON a.id_empleado = e.id_empleado
                LEFT JOIN mobiliario m ON a.id_mobiliario = m.id_mobiliario
                ORDER BY a.id_asignacion";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $id_empleado, $id_mobiliario, $fecha) {
        $id = (int)$id;
        $idem = (int)$id_empleado;
        $idm = (int)$id_mobiliario;
        $fecha = addslashes($fecha);
        $sql = "UPDATE asignacion SET id_empleado={$idem}, id_mobiliario={$idm}, fecha='{$fecha}' WHERE id_asignacion={$id}";
        return $this->conexion->ejecutar($sql);
    }

    function eliminar($id) {
        $id = (int)$id;
        $sql = "DELETE FROM asignacion WHERE id_asignacion={$id}";
        return $this->conexion->ejecutar($sql);
    }

    function buscar($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM asignacion WHERE id_asignacion={$id}";
        return $this->conexion->ejecutar($sql);
    }
}
?>