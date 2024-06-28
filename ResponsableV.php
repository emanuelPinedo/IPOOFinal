<?php

class ResponsableV extends Persona {
    private $nroEmpleado;
    private $nroLicencia;
    private $msjOperacion; // imprime errores

    public function __construct() {
        parent::__construct();
        $this->nroEmpleado = '';
        $this->nroLicencia = '';
    }

    public function getNroEmpleado() {
        return $this->nroEmpleado;
    }

    public function setNroEmpleado($numEmpleado) {
        $this->nroEmpleado = $numEmpleado;
    }

    public function getNroLicencia() {
        return $this->nroLicencia;
    }

    public function setNroLicencia($numLicencia) {
        $this->nroLicencia = $numLicencia;
    }

    public function getMsjOperacion() {
        return $this->msjOperacion;
    }

    public function setMsjOperacion($mensajeOperacion) {
        $this->msjOperacion = $mensajeOperacion;
    }

    public function cargar($dni, $name, $apell, $numEmpleado = null, $numLicencia = null) {
        parent::cargar($dni, $name, $apell);
        $this->setNroEmpleado($numEmpleado);
        $this->setNroLicencia($numLicencia);
    }

    public function buscar($numEmpleado) {
        $base = new BaseDatos();
        $resp = false;
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado = " . $numEmpleado;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                if ($row2 = $base->Registro()) {
                    parent::buscar($row2['rdocumento']); // buscamos por id en la tabla persona
                    $this->setNroEmpleado($row2['rnumeroempleado']);
                    $this->setNroLicencia($row2['rnumerolicencia']);
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
/*
    public function listar($condicion = "") {
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable ";
        if ($condicion != "") {
            $consultaResponsable .= ' WHERE ' . $condicion;
        }
        $consultaResponsable .= " ORDER BY rnumeroempleado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    $obj = new ResponsableV();
                    $obj->cargar($row2['rdocumento'], null, null, $row2['rnumeroempleado'], $row2['rnumerolicencia']);
                    array_push($arregloResponsable, $obj);
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $arregloResponsable;
    }
*/
    public function listar($condicion = "") { //CON INNER JOIN
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT responsable.*, persona.nombre, persona.apellido FROM responsable 
                                INNER JOIN persona ON responsable.rdocumento  = persona.documento";
        if ($condicion != "") {
            $consultaResponsable .= ' WHERE ' . $condicion;
        }
        $consultaResponsable .= " ORDER BY rnumeroempleado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    $obj = new ResponsableV();
                    $obj->cargar($row2['rdocumento'], $row2['nombre'], $row2['apellido'],$row2['rnumeroempleado'], $row2['rnumerolicencia']);
                    array_push($arregloResponsable, $obj);
                }
            } else {
                $this->setMsjOperacion($base->getERROR());
            }
        } else {
            $this->setMsjOperacion($base->getERROR());
        }
        return $arregloResponsable;
    }
    
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
            // Insertar primero en la tabla persona
            if (parent::insertar()) {
                // Obtener el ID de la persona insertada
                $doc = $this->getDocumento();
                // Verificar si el número de empleado y de licencia ya existen
                $consultaVerificacion = "SELECT * FROM responsable WHERE rnumeroempleado = '" . $this->getNroEmpleado() . "' OR rnumerolicencia = '" . $this->getNroLicencia() . "'";
                if ($base->Iniciar() && $base->Ejecutar($consultaVerificacion)) {
                    if ($base->Registro()) {
                        $this->setMsjOperacion("El número de empleado o de licencia ya existe.");
                    }
                }
                // Insertar en la tabla responsable
                $consultaInsert = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, rdocumento) VALUES ('" . $this->getNroEmpleado() . "', '" . $this->getNroLicencia() . "', '" . $doc . "')";
        
                if ($base->Iniciar()) {
                    if ($base->Ejecutar($consultaInsert)) {
                        $resp = true;
                    } else {
                        $this->setMsjOperacion("Error al insertar en la tabla responsable: " . $base->getERROR());
                    }
                } else {
                    $this->setMsjOperacion("Error al iniciar la conexión: " . $base->getERROR());
                }
            } else {
                $this->setMsjOperacion("Error al insertar en la tabla persona: " . parent::getMsjOperacion());
            }
        return $resp;
    }

    //Funcion modificar
    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        if (parent::modificar()) {
            // Verificar si el número de empleado ya existe y pertenece a otro documento
            $consultaVerificacion = "SELECT * FROM responsable WHERE rnumeroempleado = " . $this->getNroEmpleado() . " AND rdocumento != " . $this->getDocumento();
            if ($base->Iniciar() && $base->Ejecutar($consultaVerificacion)) {
                if ($base->Registro()) {
                    $this->setMsjOperacion("El número de empleado ya existe para otro documento.");
                    return false;
                }
            }
    
            $consultaUpdate = "UPDATE responsable SET rnumerolicencia='" . $this->getNroLicencia() . "' WHERE rnumeroempleado=" . $this->getNroEmpleado();
    
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

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            if ($this->buscar($this->getNroEmpleado())) {
                $consultaVerificacion = "SELECT COUNT(*) AS numResponsables FROM viaje WHERE rnumeroempleado=" . $this->getNroEmpleado();
                if ($base->Ejecutar($consultaVerificacion)) {
                    if ($registro = $base->Registro()) {
                        $numResponsables = $registro['numResponsables'];

                        if ($numResponsables == 0) {
                            $consultaDelete = "DELETE FROM responsable WHERE rnumeroempleado=" . $this->getNroEmpleado();
                            if ($base->Ejecutar($consultaDelete)) {
                                if (parent::eliminar()) {
                                    $resp = true;
                                }
                            } else {
                                $this->setMsjOperacion($base->getERROR());
                            }
                        } else {
                            $this->setMsjOperacion("No se puede eliminar el responsable porque tiene viajes asociados.");
                        } 
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
