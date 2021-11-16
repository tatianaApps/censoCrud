<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class PersonasController extends Controller
{
    //
    public function crear(Request $req){
    	
    	$respuesta = ["status" => 1, "msg" => ""];
    	$datos = $req->getContent();

    	//VALIDAR EL JSON

    	$datos = json_decode($datos); //Se interpreta como objeto. Se puede pasar un parámetro para que en su lugar lo devuelva como array.

    	//VALIDAR LOS DATOS

    	$persona = new Persona(); //$persona, es el objeto de la clase Persona

    	$persona->nombre = $datos->nombre;
    	$persona->primerApellido = $datos->primerApellido;
    	$persona->segundoApellido = $datos->segundoApellido;
    	$persona->fechaNacimiento = $datos->fechaNacimiento;

    	//Si son opcionales
    	if(isset($datos->primerApellido))
    		$persona->primerApellido = $datos->primerApellido;

    	//Escribir en la base de datos
    	try{
    		$persona->save();
    		$respuesta['msg'] = "Persona guardada con id ".$persona->id;
    	}catch(\Exception $e){
    		$respuesta['status'] = 0;
    		$respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
    	}

    	return response()->json($respuesta);
    }

    public function borrar($id){

    	$respuesta = ["status" => 1, "msg" => ""];

    	//Buscar a la persona
        try{
    	   $persona = Persona::find($id);

    	   if($persona){
    			$persona->delete();
    			$respuesta['msg'] = "Persona borrada";
            }else{
                $respuesta["msg"] = "Persona no encontrada";
                $respuesta["status"] = 0;
            }
    	}catch(\Exception $e){
    		$respuesta['status'] = 0;
    		$respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
    	}
    }

    public function editar(Request $req,$id){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req->getContent();

        //VALIDAR EL JSON

        $datos = json_decode($datos); //Se interpreta como objeto. Se puede pasar un parámetro para que en su lugar lo devuelva como array.


        //Buscar a la persona
        try{
            $persona = Persona::find($id);

            if($persona){

                //VALIDAR LOS DATOS

                if(isset($datos->nombre))
                    $persona->nombre = $datos->nombre;
                if(isset($datos->telefono))
                    $persona->telefono = $datos->telefono;
                if(isset($datos->direccion))
                    $persona->direccion = $datos->direccion;
                if(isset($datos->email))
                    $persona->email = $datos->email;

                //Escribir en la base de datos
                    $persona->save();
                    $respuesta['msg'] = "Persona actualizada.";
            }else{
                $respuesta["msg"] = "Persona no encontrada";
                $respuesta["status"] = 0;
            }
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }

        return response()->json($respuesta);
    }
    
    public function listar(){
        $respuesta = ["status" => 1, "msg" => ""];
        try{
            $personas = Persona::all();
            $respuesta['datos'] = $personas;
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }
        return response()->json($respuesta);
    }

    public function ver($id){
        $respuesta = ['status' => 1, "msg" => ""];

        //Buscar a la persona
        try{
            $persona = Persona::find($id);
            $persona->makeVisible(['direccion','updated_at']);
            $respuesta['datos'] = $persona;
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }

        return response()->json($respuesta);
    }
}