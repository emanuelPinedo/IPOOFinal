<?php

class Empresa{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $msjOperacion;

    public function __construct(){
        $this->idEmpresa = '';
        $this->nombre = '';
        $this->direccion = '';
    }

	public function getIdEmpresa() {
		return $this->idEmpresa;
	}

	public function setIdEmpresa($id) {
		$this->idEmpresa = $id;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function setNombre($name) {
		$this->nombre = $name;
	}

	public function getDireccion() {
		return $this->direccion;
	}

	public function setDireccion($direc) {
		$this->direccion = $direc;
	}

	public function getMsjOperacion() {
		return $this->msjOperacion;
	}

	public function setMsjOperacion($mensajeOperacion) {
		$this->msjOperacion = $mensajeOperacion;
	}

	public function cargar($id, $name, $direc){
        $this->setIdEmpresa($id);
        $this->setNombre($name);        
        $this->setDireccion($direc);
    }

	//Funcion para realizar Consultas
	public function buscar($id){
        $base = new BaseDatos();
        $consultaEmpresa = "Select * FROM empresa WHERE idempresa =" . $id;
        $resp = false;
        if($base->Iniciar()){
            //Si se pudo conectar la BD, se realiza la consulta
            if($base->Ejecutar($consultaEmpresa)){
                if($row2 = $base->Registro()){
                    $this->cargar($id, $row2['enombre'], $row2['edireccion']);//Con los [] accedemos a la columna
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

    public function listar($condicion=""){
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresas = "Select * from empresa ";
        if ($condicion != ""){
            $consultaEmpresas = $consultaEmpresas . ' where ' . $condicion;
        }
        $consultaEmpresas .= " order by idempresa ";

        if($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresas)){
                $arregloEmpresa = array();
                while ($row2 = $base->Registro()){

                    $IdEmpresa = $row2['idempresa'];
                    $Nombre = $row2['enombre'];
                    $Direccion = $row2['edireccion'];

                    $empresa = new Empresa();
                    $empresa->cargar($IdEmpresa,$Nombre,$Direccion);
                    array_push($arregloEmpresa,$empresa);
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
         } else {
            $this->setMsjOperacion($base->getERROR());
         }
         return $arregloEmpresa;
    }

	//Funcion para añadir datos
    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsert = "INSERT INTO empresa(enombre,edireccion) VALUES 
		('".$this->getNombre()."','".$this->getDireccion()."')";
        
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
    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $consultaUpdate="UPDATE empresa SET enombre='".$this->getNombre()."',edireccion='".$this->getDireccion().
		"' WHERE idempresa=". $this->getIdEmpresa();
        
        if($base->Iniciar()){
            if($base->Ejecutar($consultaUpdate)){
                $resp = true;
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $resp;
    }

	//Funcion para eliminar
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;

        if ($base->Iniciar()) {
            // Verificamos si hay viajes asociados con la empresa
            $consultaVerificacion = "SELECT COUNT(*) AS numViajes FROM viaje WHERE idempresa=" . $this->getIdEmpresa();
            if ($base->Ejecutar($consultaVerificacion)) {
                if ($registro = $base->Registro()) { // Obtenemos los resultados de la consulta
                    $numViajes = $registro['numViajes'];

                    if ($numViajes == 0) {
                        // Si la empresa no tiene viajes, eliminamos la empresa
                        $consultaDelete = "DELETE FROM empresa WHERE idempresa=" . $this->getIdEmpresa();
                        if ($base->Ejecutar($consultaDelete)) {
                            $resp = true;
                        } else {
                            $this->setMsjOperacion($base->getERROR());
                        }
                    } else {
                        // Hay viajes asociados, no se puede eliminar la empresa
                        $this->setMsjOperacion("No se puede eliminar la empresa porque tiene viajes asociados.");
                    }
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $resp;
    }

}