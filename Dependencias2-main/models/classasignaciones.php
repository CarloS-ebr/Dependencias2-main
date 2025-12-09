<?php
class Asignaciones {
    private $conexion;

    function __construct($conex) {
        $this->conexion = $conex;
    }

    function insertar($id_empleado, $id_mobiliario, $fecha) {
        $sql = "INSERT INTO asignacion (id_empleado, id_mobiliario, fecha)
                VALUES ($id_empleado, $id_mobiliario, '$fecha')";
        return $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT a.id_asignacion, a.fecha,
                       e.nombre AS empleado,
                       m.nombre AS mobiliario,
                       m.numero_inventario
                FROM asignacion a
                INNER JOIN empleado e ON a.id_empleado = e.id_empleado
                INNER JOIN mobiliario m ON a.id_mobiliario = m.id_mobiliario
                ORDER BY a.id_asignacion";
        return $this->conexion->ejecutar($sql);
    }

    function buscar($id) {
        $sql = "SELECT * FROM asignacion WHERE id_asignacion = $id";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $id_empleado, $id_mobiliario, $fecha) {
        $sql = "UPDATE asignacion 
                SET id_empleado=$id_empleado, id_mobiliario=$id_mobiliario, fecha='$fecha'
                WHERE id_asignacion=$id";
        return $this->conexion->ejecutar($sql);
    }

    /** 
     * BORRAR UNA ASIGNACIÓN
     */
    function eliminar($id) {
        $sql = "DELETE FROM asignacion WHERE id_asignacion = $id";
        return $this->conexion->ejecutar($sql);
    }

    /**
     * BORRAR TODAS LAS ASIGNACIONES DE UN EMPLEADO
     */
    function eliminarPorEmpleado($id_empleado) {
        $id_empleado = (int)$id_empleado;
        $sql = "DELETE FROM asignacion WHERE id_empleado = $id_empleado";
        return $this->conexion->ejecutar($sql);
    }
}
?>
