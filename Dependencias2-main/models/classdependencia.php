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

        $sql = "INSERT INTO dependencia (nombre, responsable) 
                VALUES ('{$nombre}', '{$responsable}')";
        return $this->conexion->ejecutar($sql);
    }

    function listar() {
        $sql = "SELECT * FROM dependencia ORDER BY id_dependencia";
        return $this->conexion->ejecutar($sql);
    }

    function modificar($id, $nombre, $responsable) {
        $id = (int)$id;
        $nombre = addslashes($nombre);
        $responsable = addslashes($responsable);

        $sql = "UPDATE dependencia 
                SET nombre='{$nombre}', responsable='{$responsable}'
                WHERE id_dependencia={$id}";
        return $this->conexion->ejecutar($sql);
    }

    function eliminar($id) {
        $id = (int)$id;

        $sql = "DELETE FROM dependencia WHERE id_dependencia = {$id}";
        $res = @pg_query($this->conexion->getConexion(), $sql);

        if (!$res) {
            $error = pg_last_error($this->conexion->getConexion());

            // Error de llave foránea — no se puede eliminar
            if (str_contains($error, "23503")) {
                return "FK_ERROR";
            }

            return false;
        }

        return true;
    }

    function buscar($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM dependencia WHERE id_dependencia = {$id}";
        return $this->conexion->ejecutar($sql);
    }
}
?>
