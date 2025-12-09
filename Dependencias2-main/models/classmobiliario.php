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
    $res = @pg_query($this->conexion->getConexion(), $sql);

    if (!$res) {
        $error = pg_last_error($this->conexion->getConexion());

        if (str_contains($error, "23503")) {
            return "FK_ERROR";  // No se puede eliminar por clave foránea
        }
        return false;
    }

    return true;
}

    function buscar($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM mobiliario WHERE id_mobiliario={$id}";
        return $this->conexion->ejecutar($sql);
    }
  function eliminarPorEmpleado($id_empleado) {

    // 1. Obtener todos los mobiliarios asignados a ese empleado
    $sql = "SELECT id_mobiliario FROM asignacion WHERE id_empleado = $id_empleado";
    $res = $this->conexion->ejecutar($sql);

    if ($res && pg_num_rows($res) > 0) {

        while ($row = pg_fetch_assoc($res)) {
            $id_mob = (int)$row['id_mobiliario'];
            
            // 2. Eliminar el mobiliario
            $sqlDel = "DELETE FROM mobiliario WHERE id_mobiliario = $id_mob";
            $this->conexion->ejecutar($sqlDel);
        }
    }
  }
}
?>