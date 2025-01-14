<?php

class Conexion {
    static public function conectar(){
        $servidor = "localhost";
        $baseDatos = "api-rest";
        $usuario = "root";
        $password = "";
        $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos", 
        $usuario, $password);
        $conexion-> exec("set names utf8");
        return $conexion;
    }
}

?>