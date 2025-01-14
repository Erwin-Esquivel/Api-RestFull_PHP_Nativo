<?php
require_once "controladores/rutas.controlador.php";
require_once "controladores/cursos.controlador.php";
require_once "controladores/clientes.controlador.php"; 
require_once "modelos/conexion.php";
require_once "modelos/cursos.modelo.php";
require_once "modelos/clientes.modelo.php";
$rutas = new controladorRutas();
$rutas -> inicio();
?>