<?php

class Persona{
    private $nombre;
    private $apellido;
    private $documento;
	private $msjOperacion;

	public function __construct() {
		$this->nombre = '';
		$this->apellido = '';
		$this->documento = '';
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function setNombre($name) {
		$this->nombre = $name;
	}

	public function getApellido() {
		return $this->apellido;
	}

	public function setApellido($apell) {
		$this->apellido = $apell;
	}

	public function getDocumento() {
		return $this->documento;
	}

	public function setDocumento($dni) {
		$this->documento = $dni;
	}

	public function getMsjOperacion() {
		return $this->msjOperacion;
	}

	public function setMsjOperacion($mensajeOperacion) {
		$this->msjOperacion = $mensajeOperacion;
	}

    public function cargar($dni, $name, $apell){
        $this->setDocumento($dni);
        $this->setNombre($name);        
        $this->setApellido($apell);
    }

	//Funcion para realizar Consultas
	public function buscar($dni){
        $base = new BaseDatos();
        $consultaPersona = "SELECT * FROM persona WHERE documento =".$dni;
        $resp = false;
        if($base->Iniciar()){
            //Si se pudo conectar la BD, se realiza la consulta
            if($base->Ejecutar($consultaPersona)){
                if($row2 = $base->Registro()){
                    $this->setDocumento($dni);
                    $this->setNombre($row2['nombre']);//Con los [] accedemos a la columna
                    $this->setApellido($row2['apellido']);
                    $resp = true;
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $resp;
    }

    public  function listar($condicion=""){
        $arregloPersona = null;
        $base=new BaseDatos();
        $consultaPersonas="SELECT * FROM persona ";
        if ($condicion!=""){
            $consultaPersonas=$consultaPersonas.' WHERE '.$condicion;
        }
        $consultaPersonas.=" order by idpersonas ";
        
        if($base->Iniciar()){
            if($base->Ejecutar($consultaPersonas)){
                $arregloPersona= array();
                while($row2=$base->Registro()){

                    $NroDoc=$row2['documento'];
                    $Nombre=$row2['nombre'];
                    $Apellido=$row2['apellido'];
                    $perso = new Persona();
                    $perso->cargar($NroDoc,$Nombre,$Apellido);
                    array_push($arregloPersona,$perso);
                }
             }    else {
                     $this->setMsjOperacion($base->getERROR());
            }
         }    else {
                 $this->setMsjOperacion($base->getERROR());
         }
         return $arregloPersona;
    }

	//Funcion para añadir datos
    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsert = "INSERT INTO persona(nombre,apellido,documento) VALUES 
        ('" . $this->getNombre() . "', '" . $this->getApellido() . "', " . $this->getDocumento() . ")";
        
        if($base->Iniciar()){
            if($base->Ejecutar($consultaInsert)){
                $resp = true;
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $resp;
    }
    
	//Funcion para modificar la BD según el documento de la persona
    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
    
        $consultaUpdate = "UPDATE persona SET apellido='" . $this->getApellido() . "', nombre='" . $this->getNombre() .
                          "' WHERE documento=" . $this->getDocumento();
    
        //echo "Debug: Consulta UPDATE: " . $consultaUpdate . "\n";
        //echo "Debug: Apellido: " . $this->getApellido() . ", Nombre: " . $this->getNombre() . ", Documento: " . $this->getDocumento() . "\n";
    
        if ($base->Iniciar()) {
            //echo "Debug: Conexión con la base de datos iniciada.\n";
    
            if ($base->Ejecutar($consultaUpdate)) {
                $resp = true;
                $this->setMsjOperacion("¡Modificación exitosa en la tabla persona!");
            } else {
                $this->setMsjOperacion("Error al ejecutar la modificación en la tabla persona: " . $base->getERROR());
                //echo "Debug: Error en la ejecución: " . $base->getERROR() . "\n";
            }
        } else {
    
            $this->setMsjOperacion("Error al iniciar la conexión para modificar persona: " . $base->getERROR());
            //echo "Debug: Error al iniciar la conexión: " . $base->getERROR() . "\n";
        }
    
        if ($resp) {
            //echo "Debug: Persona modificada exitosamente.\n";
        } else {
            //echo "Debug: Fallo al modificar la persona.\n";
        }
    
        return $resp;
    }

	//Funcion para eliminar un viaje de la BD según el documento de la persona
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaDelete="DELETE FROM persona WHERE documento=".$this->getDocumento();
				if($base->Ejecutar($consultaDelete)){
				    $resp=  true;
				}else{
						$this->setMsjOperacion($base->getERROR());
					
				}
		}else{
				$this->setMsjOperacion($base->getERROR());
			
		}
		return $resp; 
	}

}