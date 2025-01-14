<?php
//documentacion y explicacion de el codigo 
//autor: Erwin Esquivel Vega
//iniciamos con la creacion de un array llamado $arrayRutas
$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);
//con la funcion explode separamos la url en partes y las guardamos en el array $arrayRutas
//con la funcion $_SERVER['REQUEST_URI'] obtenemos la url actual
if(isset($_GET["pagina"]) && is_numeric($_GET["pagina"])){
    $cursos = new ControladorCursos();
    $cursos -> index($_GET["pagina"]);

}else{
//con la funcion count(array_filter($arrayRutas)) contamos los elementos del array $arrayRutas
if(count(array_filter($arrayRutas)) == 1){
            
}else{

    //ESTO ES PARA EL REGISTRO DE CLIENTES
    //UNICAMENTE SE PUEDE REGISTRAR UN CLIENT
    if(count(array_filter($arrayRutas)) == 2){
        if(array_filter($arrayRutas)[2] == "registro"){
            
            if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
                
                $datos = array(
                    "nombre" => $_POST["nombre"],
                    "apellido" => $_POST["apellido"],
                    "email" => $_POST["email"]
            
                );
               // echo "<pre>"; print_r($datos); echo "</pre>";

                $cursos = new ControladorClientes();
                $cursos -> create($datos);

            }
        }
    }





    //ESTE ES EL INDEX DE LOS CURSOS
    //AQUI SE MUESTRAN TODOS LOS CURSOS
    if(count(array_filter($arrayRutas)) == 2){
        if(array_filter($arrayRutas)[2] == "cursos"){
            if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){
                $cursos = new ControladorCursos();
                $cursos -> index(null);
            }
/*--------------------------------------------- PETICIONES POST ------------------------------------------------------------- */
            if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
                  $datos = array(
                        "titulo" => $_POST["titulo"],
                        "descripcion" => $_POST["descripcion"],
                        "instructor" => $_POST["instructor"],
                        "imagen" => $_POST["imagen"],
                        "precio" => $_POST["precio"]
                    ); 
                $cursos = new ControladorCursos();
                $cursos -> create($datos);
            }
        }
        }else{
            if(array_filter($arrayRutas)[2] == "cursos" && 
            is_numeric(array_filter($arrayRutas)[3])){

/*--------------------------------------------- PETICIONES GET ------------------------------------------------------------- */

                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){
                $cursos = new ControladorCursos();
                $cursos -> show2(array_filter($arrayRutas)[3]);
                }
/*--------------------------------------------- PETICIONES PUT ------------------------------------------------------------- */
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT"){
                    /* Capturar los datos del formulario */
                    $datos = array();
                    parse_str(file_get_contents("php://input"), $datos);
                    //PARA VERIFICAR QUE LOS DATOS SE ESTAN CAPTURANDO CORRECTAMENTE
                    //echo "<pre>"; print_r($datos); echo "</pre>";

                    //MANDAMOS LOS DATOS AL METODO UPDATE
                    $editarcursos = new ControladorCursos();
                    $editarcursos -> update(array_filter($arrayRutas)[3], $datos);
                }
/*--------------------------------------------- PETICIONES DELETE ------------------------------------------------------------- */

                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE"){
                    $borrarcursos = new ControladorCursos();
                    $borrarcursos -> delete(array_filter($arrayRutas)[3]);
                }
                
        }
        }

}
}
?>