<?php

class Pasajero extends Persona{
	private $telefono; 
	private $objViaje;
    private $idPasajero;
	private $msjOperacion;//imprime errores

	public function __construct() {
		parent::__construct();
		$this->telefono = '';
		$this->objViaje = null;
        $this->idPasajero = '';
	}

    public function getIdPasajero() {
		return $this->idPasajero;
	}

	public function setIdPasajero($id) {
		$this->idPasajero = $id;
	}

	public function getTelefono() {
		return $this->telefono;
	}

	public function setTelefono($tel) {
		$this->telefono = $tel;
	}

	public function getObjViaje() {
		return $this->objViaje;
	}

	public function setObjViaje($viaje) {
		$this->objViaje = $viaje;
	}

	public function getMsjOperacion() {
		return $this->msjOperacion;
	}

	public function setMsjOperacion($mensajeOperacion) {
		$this->msjOperacion = $mensajeOperacion;
	}

    public function cargar($dni, $name, $apell, $idPasajero = null, $tel = null, $objViaje = null) {
        parent::cargar($dni, $name, $apell);
        // Asigna los valores adicionales
        $this->setTelefono($tel);
        $this->setObjViaje($objViaje);
        $this->setIdPasajero($idPasajero);
    }

	//Funcion para realizar Consultas
	public function buscar($id) {
		$base = new BaseDatos();
		$consultaPasajero = "SELECT * FROM pasajero WHERE idpasajeros = " . $id;
		$resp = false;
		if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaPasajero)) {
				if ($row2 = $base->Registro()) {
					parent::buscar($row2['pdocumento']);
                    $this->setIdPasajero($id);
					$this->setTelefono($row2['ptelefono']);//con los [] accedemos a los datos
					$this->setObjViaje($row2['idviaje']);
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

    public function listar($condicion = "") {//CON INNER JOIN
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT p.*, per.nombre, per.apellido 
                             FROM pasajero AS p 
                             INNER JOIN persona AS per ON p.pdocumento = per.documento";
        if ($condicion != "") {
            $consultaPasajero .= " WHERE " . $condicion;
        }
        $consultaPasajero .= " ORDER BY p.idpasajeros";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {
                    $obj = new Pasajero();
                    $obj->cargar($row2['pdocumento'], $row2['nombre'], $row2['apellido'], $row2['idpasajeros'] ,$row2['ptelefono'], $row2['idviaje']);
                    array_push($arregloPasajero, $obj);
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $arregloPasajero;
    }

	//Funcion para añadir datos
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        if (!$this->buscar($this->getIdPasajero())) {
            if (parent::insertar()) {
                $consultaInsert = "INSERT INTO pasajero(pdocumento, ptelefono, idviaje) VALUES 
                (" .  $this->getDocumento() . ", '" .$this->getTelefono() . "', " . $this->getObjViaje()->getIdViaje() . ")";
            }
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaInsert)) {
                    $resp = true;
                } else {
                    $this->setMsjOperacion($base->getERROR());
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        }
        return $resp;
    }
  /*   //modificar con el buscar pero no modifica el pasajero
    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
     
        if ($this->buscar($this->getIdPasajero())) {
            if (parent::modificar()) {
    
                $consultaUpdate = "UPDATE pasajero SET ptelefono = '" . $this->getTelefono() . "', idviaje = '" . $this->getObjViaje() . "' WHERE idpasajeros = '" . $this->getIdPasajero() . "'";
                
                echo "Debug: Consulta UPDATE: " . $consultaUpdate . "\n";
                echo "Debug: Teléfono: " . $this->getTelefono() . ", ID Viaje: " . $this->getObjViaje() . ", ID Pasajero: " . $this->getIdPasajero() . "\n";
    
                if ($base->Iniciar()) {
    
                    echo "Debug: Conexión con la base de datos iniciada.\n";
    
                    if ($base->Ejecutar($consultaUpdate)) {
                        $resp = true;
                        $this->setMsjOperacion("¡Modificación exitosa en la tabla pasajero!");
                    } else {
                        $this->setMsjOperacion("Error al ejecutar la modificación en la tabla pasajero: " . $base->getERROR() . ". Query: " . $consultaUpdate);
                        echo "Debug: Error en la ejecución: " . $base->getERROR() . "\n";
                    }
                } else {
                    $this->setMsjOperacion("Error al iniciar la conexión para modificar pasajero: " . $base->getERROR());
                    echo "Debug: Error al iniciar la conexión: " . $base->getERROR() . "\n";
                }
            } else {
                $this->setMsjOperacion("Error en la modificación de datos antes de la inserción: " . parent::getMsjOperacion());
                echo "Debug: Error en la modificación de datos antes de la inserción: " . parent::getMsjOperacion() . "\n";
            }
        } else {
            $this->setMsjOperacion("No se encontró el pasajero con ID: " . $this->getIdPasajero());
            echo "Debug: No se encontró el pasajero con ID: " . $this->getIdPasajero() . "\n";
        }
    
        if ($resp) {
            echo "Debug: Pasajero modificado exitosamente.\n";
        } else {
            echo "Debug: Fallo al modificar el pasajero.\n";
        }
    
        return $resp;
    }
 */    

	//Funcion para modificar la BD según el documento de la persona
    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        if ($this->buscar($this->getIdPasajero())) {
            if (parent::modificar()) {
                $consultaUpdate = "UPDATE pasajero SET ptelefono = '" . $this->getTelefono() . "', idviaje = '" . $this->getObjViaje() . "' WHERE idpasajeros = '" . $this->getIdPasajero() . "'";
            }
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaUpdate)) {
                    $resp = true;
                } else {
                    $this->setMsjOperacion($base->getERROR());
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        }
        return $resp;
    }
  
	//Funcion para eliminar un viaje de la BD según el documento de la persona
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
                if ($this->buscar($this->getIdPasajero())) {
                    $consultaDelete = "DELETE FROM pasajero WHERE idpasajeros = " . $this->getIdPasajero();
                    if ($base->Ejecutar($consultaDelete)) {
                        if (parent::eliminar()) {
                            $resp = true;
                        }
                    } else {
                        $this->setMsjOperacion($base->getERROR());
                    }
                } else {
                    $this->setMsjOperacion($base->getERROR());
                }
        }
        return $resp;
    }

	public function __toString(){
		return parent::__toString() . 
        "\nId de pasajero: " .$this->getIdPasajero().
		"\nTelefono: " . $this->getTelefono() . 
		"\nId del Viaje: " . $this->getObjViaje() . "\n";
	}

}