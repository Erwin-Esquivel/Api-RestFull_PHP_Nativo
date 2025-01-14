<?php
class ControladorClientes{
public function create($datos){

    /* validar nombres 
    */ 

    if(isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["nombre"])){
        $json=array(
        "status"=> 404,
        "detalle" => "error en el campo nombre solo se permiten letras"
        );
        echo json_encode($json, true);
        return;

    }
    /*VALIDAR APELLIDOS*/
    if(isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["apellido"])){
        $json=array(
        "status"=> 404,
        "detalle" => "error en el campo apellido solo se permiten letras"
        );
        echo json_encode($json, true);


    }

    /*VALIDAR EMAIL*/
    if(isset($datos["email"]) &&
     !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', 
     $datos["email"])){

        $json=array(
        "status"=> 404,
        "detalle" => "error en el campo email"
        );
        echo json_encode($json, true);


    }
  
    $clientes = Clientes_modelo::index("clientes");
        foreach ($clientes  as $key => $value) {
            if($value["email"] == $datos["email"]){
                 $json=array(
                    "status"=>404,
                    "detalle"=> "el email esta repetido"
            ); 
            echo json_encode($json,true);
            return;
            }
         
        }

        /* GENERAR ID DE CLIENTE */
        $id_cliente = str_replace("$","c",crypt($datos["nombre"].$datos["apellido"].$datos["email"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$'));
        $llave_clave = str_replace("$","c",crypt($datos["email"].$datos["apellido"].$datos["nombre"],'$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$'));
        $datos=array("nombre"=>$datos["nombre"],
                "apellido"=>$datos["apellido"],
                "email"=>$datos["email"],
                "id_cliente"=>$id_cliente,
                "llave_secreta"=>$llave_clave,
                "create_at"=>date("Y-m-d h:i:s"),
                "update_at"=>date("Y-m-d h:i:s")
                    );
        $create=Clientes_modelo::create("clientes",$datos);
        if($create=="ok"){
            $json=array(
                "status"=>200,
                "detalle"=>"registro exitoso"
            );
            echo json_encode($json,true);

}   

}   
}   
?>