<?php

class ControladorCursos{
/*--------------------------------------------- METODO INDEX ------------------------------------------------------------- */
    public function index($pagina){
        $clientes = Clientes_modelo::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
            foreach($clientes as $key => $value){
                if(base64_encode($value["id_cliente"] == $_SERVER['PHP_AUTH_USER']) && 
                    base64_encode($value["llave_secreta"] == $_SERVER['PHP_AUTH_PW'])){

                    if($pagina != null){
                    $cantidad = 10;
                    $desde = ($pagina - 1) * $cantidad;
                        
                    $cursos = modeloCursos::index("cursos", "clientes", $cantidad, $desde);

                    }else{
                    $cursos = modeloCursos::index("cursos", "clientes", null, null);
                    }
                    
                    $json=array(
                        "status" => 200,
                        "total_registros"=>count($cursos),
                        "detalle" => $cursos,
                        
                        );
                        echo json_encode($json, true);
                }
            }
        }
       
    }
/*--------------------------------------------- METODO CREATE ------------------------------------------------------------- */
    public function create($datos){
        $clientes = Clientes_modelo::index("clientes");
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
            foreach($clientes as $key => $value){
                if(base64_encode($value["id_cliente"] == $_SERVER['PHP_AUTH_USER']) && 
                    base64_encode($value["llave_secreta"] == $_SERVER['PHP_AUTH_PW'])){

                        //VALIDAR DATOS 

                    foreach($datos as $key => $valuedatos){
                       if(isset($valuedatos) && 
                       !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/',
                       $valuedatos)){
                          $json=array(
                                "status" => 404,
                              "detalle" => "Error en el campo".$key
                              );
                              echo json_encode($json, true);
                              return;
                          

                       }
                    }

                    //VALIDAR EL TITULO Y LA DESCRIPCION QUE NO ESTEN REPETIDOS

                    $cursos = modeloCursos::index("cursos","clientes", null, null);
                    foreach($cursos as $key => $valuecurso){
                       if($valuecurso -> titulo == $datos["titulo"] ){
                            $json=array(
                                    "status" => 404,
                                "detalle" => "Error el titulo ya existe"
                                );
                                echo json_encode($json, true);
                                return;
                       }
                       if($valuecurso -> descripcion == $datos["descripcion"] ){
                            $json=array(
                                    "status" => 404,
                                "detalle" => "Error la descripcion ya existe"
                                );
                                echo json_encode($json, true);
                                return;

                       }

                    }
                    /*llevar a el modelo*/
                    $datos = array(
                        "titulo" => $datos["titulo"],
                        "descripcion" => $datos["descripcion"],
                        "instructor" => $datos["instructor"],
                        "imagen" => $datos["imagen"],
                        "precio" => $datos["precio"],
                        "id_creador" => $value["id"],
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')

                    );
                    $create = modeloCursos::create("cursos",$datos);

                    if($create == "ok"){
                        $json=array(
                            "status" => 200,
                            "detalle" => "Registro exitoso, curso creado"
                            );
                            echo json_encode($json, true);
                            return;

                       
            }
        }
    
}
}
}
/*--------------------------------------------- METODO SHOW ------------------------------------------------------------- */

    public function show2($id){
        /* VALIDAR LAS CREDENCIALES DEL CLIENTE */
        $clientes = Clientes_modelo::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){


            foreach($clientes as $key => $value){
                
                if(base64_encode($value["id_cliente"] == $_SERVER['PHP_AUTH_USER']) && 
                    base64_encode($value["llave_secreta"] == $_SERVER['PHP_AUTH_PW'])){
                        /* mostrar los cursos */
                        $curso = modelocursos::show("cursos","clientes", $id);
                        if(!empty($curso)){
                            $json = array(
                                "status" => 200,
                                "detalle" => $curso
                            );
                            echo json_encode($json, true);
                            return;
                            
                        }
                }
            }

        }
    }

/*--------------------------------------------- METODO UPDATE ------------------------------------------------------------- */
    public function update($id, $datos){

        //validamos las credenciales del cliente 
        $clientes = Clientes_modelo::index("clientes");
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

			foreach ($clientes as $key => $valueCliente) {
				
				if( "Basic ".base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
					"Basic ".base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"]) ){

            	/*=============================================
					Validar datos
					=============================================*/

					foreach ($datos as $key => $valueDatos) {

						if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){
							$json = array(
								"status"=>404,
								"detalle"=>"Error en el campo ".$key
							);
							echo json_encode($json, true);
							return;
						}
					}
          	/*=============================================
					Validar id creador
					=============================================*/

          $curso = modeloCursos::show("cursos","clientes", $id);

          foreach ($curso as $key => $valueCurso) {

            if($valueCurso->id_creador == $valueCliente["id"]){

              	/*=============================================
							Llevar datos al modelo
							=============================================*/

              	$datos = array( "id"=>$id,
											      "titulo"=>$datos["titulo"],
											      "descripcion"=>$datos["descripcion"],
											      "instructor"=>$datos["instructor"],
											      "imagen"=>$datos["imagen"],
											      "precio"=>$datos["precio"],
											      "updated_at"=>date('Y-m-d h:i:s'));

                            $update = ModeloCursos::update("cursos", $datos);
                            if($update == "ok"){
                              	$json = array(
                                    "status"=>200,
                                     "detalle"=>"Registro exitoso, su curso ha sido actualizado"
                                ); 
                                echo json_encode($json, true); 
						    	return;  

                            }else{

                              	$json = array(

                                  "status"=>404,
                                  "detalle"=>"No está autorizado para modificar este curso"
                                );
                              echo json_encode($json, true);

                              return;

                            }
                        }
                    }
                }

            }

        }
}

/*--------------------------------------------- METODO DELETE ------------------------------------------------------------- */
    public function delete($id){
        //validamos las credenciales del cliente 
        $clientes = Clientes_modelo::index("clientes");
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
            foreach($clientes as $key => $value){
                if(base64_encode($value["id_cliente"] == $_SERVER['PHP_AUTH_USER']) && 
                    base64_encode($value["llave_secreta"] == $_SERVER['PHP_AUTH_PW'])){
                        /*validar el id del creador */
                        $curso = modelocursos::show("cursos","clientes", $id);

                        foreach($curso as $key => $valuecurso){
                            if($valuecurso -> id_creador == $value["id"]){
                                /*llevar al modelo */
                                $delete = modelocursos::delete("cursos", $id);
                                //comprobacion
                                if($delete == "ok"){
                                    $json = array(
                                        "status" => 200,
                                        "detalle" => "Curso eliminado"
                                    );
                                    echo json_encode($json, true);
                                    return;
                                }else{
                                    $json = array(
                                        "status" => 404,
                                        "detalle" => "No está autorizado para eliminar este curso"
                                    );
                                    echo json_encode($json, true);
                                    return;
                                }
                            }


                        }
                }
            }
    }
}
}


?>