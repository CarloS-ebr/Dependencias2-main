<?php

class conexion {
    private $host;
    private $nombreBD;
    private $usuario;
    private $password;
    private $conexionDB;

    function __construct ($hts, $db, $usr, $pwd) {
        $this->host = $hts;
        $this->nombreBD = $db;
        $this->usuario = $usr;
        $this->password = $pwd;
        $this->conexionDB = null;
    }

    function conectar(){
        if ($this->conexionDB) return $this->conexionDB;
        $cadenaConexion = " host=".$this->host;
        $cadenaConexion .= " dbname=".$this->nombreBD;
        $cadenaConexion .= " user=".$this->usuario;
        $cadenaConexion .= " password=".$this->password;
        $this->conexionDB = @pg_connect($cadenaConexion);
    }

    function desconectar(){
        if ($this->conexionDB) {
            pg_close($this->conexionDB);
            $this->conexionDB = null;
        }
    }

    function ejecutar($sql){
    $this->conectar();
    $res = @pg_query($this->conexionDB, $sql);

    if (!$res) {
        // Guardamos el error de PostgreSQL
        return [
            "error" => true,
            "mensaje" => pg_last_error($this->conexionDB),
            "sql" => $sql
        ];
    }

    return $res;
}

    function getConexion(){
        $this->conectar();
        return $this->conexionDB;
    }
}
?>