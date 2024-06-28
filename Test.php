<?php

include_once 'BaseDatos.php';
include_once 'Persona.php';
include_once 'Empresa.php';
include_once 'ResponsableV.php';
include_once 'Viaje.php';
include_once 'Pasajero.php';

$salir = false;

do {
  echo "***********************************\n";
  echo "|   Bienvenido                    |\n";
  echo "| Elija una sección para acceder  |\n";
  echo "|   1. Empresa                    |\n";
  echo "|   2. Responsable                |\n";
  echo "|   3. Viaje                      |\n";
  echo "|   4. Pasajero                   |\n";
  echo "|   5. Salir                      |\n";
  echo "***********************************\n";

  $opcion = trim(fgets(STDIN));

  switch ($opcion) {
    case 1:
      menuDeEmpresa();
      break;

    case 2:
      menuDeResponsableV();
      break;

    case 3:
      menuDeViaje();
      break;

    case 4:
      menuDePasajero();
      break;

    case 5:
      $salir = true;
      break;

    default:
      echo "Opción no válida \n";
      break;
  }
} while (!$salir);

echo "Usted ha salido del menú de opciones.\n";

function menuDeEmpresa(){
    do {
        echo "************************************\n";
        echo "|Usted accedió a la sección Empresa|\n";
        echo "| Elija una opción                 |\n";
        echo "|   1. Agregar                     |\n";
        echo "|   2. Modificar                   |\n";
        echo "|   3. Eliminar                    |\n";
        echo "|   4. Buscar                      |\n";
        echo "|   5. Listar                      |\n";
        echo "|   6. Volver al menú              |\n";
        echo "************************************\n";

        $opcionEmpr = trim(fgets(STDIN));
        $salirMenu = false;

        switch($opcionEmpr){
            case 1:
                echo "OPCIÓN AGREGAR EMPRESA\n";
                echo "Ingrese el nombre de la empresa: \n";
                $nombreEmp = trim(fgets(STDIN));
                echo "Ingrese la dirección de la empresa: \n";
                $direcEmp = trim(fgets(STDIN));
                //Creo el objeto
                $objEmp = new Empresa();
                
                $verificarNombre = "enombre LIKE '%" . $nombreEmp . "%'";
                //Si el nombre ingresado ya existe no podrá agregarlo.
                $colecEmp = $objEmp->listar($verificarNombre);
                if(!empty($colecEmp)){
                    echo "Este nombre ya esta en uso en otra empresa: \n";
                    foreach ($colecEmp as $emp){
                        echo $emp . "\n";//Le muestro la empresa en uso
                    }
                } else {
                    //Si el nombre no esta en uso encontes lo cargo
                    $objEmp->cargar(0, $nombreEmp, $direcEmp);
                    if($objEmp->insertar()) {
                        echo "Su Empresa ha sido agregada.\n";
                    } else {
                        echo "Ha habido un error al cargar su Empresa.\n";
                    }
                }
                break;
            case 2:
                echo "OPCIÓN MODIFICAR EMPRESA\n";
                echo "Ingrese ID de la Empresa a modificar: \n";
                $idEmp = trim(fgets(STDIN));
                $objEmp = new Empresa();
                if ($objEmp->buscar($idEmp)) {//Busco le empresa con el id ingresado
                    echo "Empresa encontrada: \n";
                    echo "Id de Empresa: " . $objEmp->getIdEmpresa() . "\n";
                    echo "Nombre de Empresa: " . $objEmp->getNombre() . "\n";
                    echo "Dirección: " . $objEmp->getDireccion() . "\n";
                    echo "Ingrese el nuevo nombre para la Empresa: \n";
                    $nombreNuevo = trim(fgets(STDIN));
                    echo "Ingrese la nueva dirección para la Empresa: \n";
                    $direcNueva = trim(fgets(STDIN));

                    if(!empty($nombreNuevo)) {
                        $objEmp->setNombre($nombreNuevo);
                    }
                    if(!empty($direcNueva)) {
                        $objEmp->setDireccion($direcNueva);
                    }
                    if ($objEmp->modificar()) {
                        echo "Su Empresa ha sido modificada.\n";
                    } else {
                        echo "Ha habido un error al modificar su Empresa.\n";
                    }
                } else {
                    echo "Su Empresa no existe.\n";
                }
                break;
            case 3:
                echo "OPCIÓN ELIMINAR EMPRESA\n";
                echo "Ingrese ID de la Empresa a eliminar: \n";
                $idEmp = trim(fgets(STDIN));

                if (!is_numeric($idEmp)) {
                    echo "El ID de la Empresa debe ser numérico.\n";
                    break;
                }

                $objEmp = new Empresa();
                if ($objEmp->buscar($idEmp)) {
                    if ($objEmp->eliminar()) {
                        echo "Su Empresa ha sido eliminada.\n";
                    } else {
                        echo "Ha habido un error al eliminar su Empresa.\n";
                    }
                } else {
                    echo "No se encontró la Empresa con ese ID.\n";
                }
                break;
            case 4:
                echo "OPCIÓN BUSCAR EMPRESA\n";
                echo "Ingrese ID de la Empresa a buscar: \n";
                $idEmp = trim(fgets(STDIN));

                if (!is_numeric($idEmp)) {
                    echo "El ID de la Empresa debe ser numérico.\n";
                    break;
                }

                $objEmp = new Empresa();
                if ($objEmp->buscar($idEmp)) {
                    echo "Su Empresa ha sido encontrada: \n";
                    echo "Id de Empresa: " . $objEmp->getIdEmpresa() . "\n";
                    echo "Nombre de Empresa: " . $objEmp->getNombre() . "\n";
                    echo "Dirección: " . $objEmp->getDireccion() . "\n";
                } else {
                    echo "El ID es incorrecto o la Empresa no existe.\n";
                }
                break;
            case 5:
                echo "OPCIÓN LISTAR EMPRESA\n";
                $empresaObj = new Empresa();
                $empresas = $empresaObj->listar();

                if (!empty($empresas)) {
                    foreach ($empresas as $empresa) {
                        echo "Id de Empresa: " . $empresa->getIdEmpresa() . "\n";
                        echo "Nombre de Empresa: " . $empresa->getNombre() . "\n";
                        echo "Dirección: " . $empresa->getDireccion() . "\n";
                        echo "\n";
                    }
                } else {
                    echo "No hay empresas disponibles.\n";
                }
                break;
            case 6:
                $salirMenu = true;
                break;
            default:
            echo "Esta Opción no existe. \n";
            break;
        }
    } while (!$salirMenu);
}

function menuDeViaje(){
    do {
        echo "************************************\n";
        echo "| Usted accedió a la sección Viaje |\n";
        echo "| Elija una opción                 |\n";
        echo "|   1. Agregar                     |\n";
        echo "|   2. Modificar                   |\n";
        echo "|   3. Eliminar                    |\n";
        echo "|   4. Buscar                      |\n";
        echo "|   5. Listar viajes               |\n";
        echo "|   6. Listar pasajeros del viaje  |\n";
        echo "|   7. Volver al menú              |\n";
        echo "************************************\n";

        $opcionViaje = trim(fgets(STDIN));
        $salirMenu = false;

        switch($opcionViaje){
            case 1:
                echo "OPCIÓN AGREGAR Viaje\n";
                echo "Ingrese el destino del Viaje: \n";
                $destinoViaje = trim(fgets(STDIN));
                echo "Ingrese la capacidad max. de pasajeros del Viaje: \n";
                $cantMax = trim(fgets(STDIN));
                if (!is_numeric($cantMax)) {
                    echo "La cantidad máxima de pasajeros debe ser un número.\n";
                    break;
                }
                echo "Ingrese ID de la Empresa: \n";
                $idEmp = trim(fgets(STDIN));
                if (!is_numeric($idEmp)) {
                    echo "El ID de la empresa debe ser un número.\n";
                    break;
                }
                //Creo el objeto de la empresa para el id y lo busco
                $objEmp = new Empresa();
                if (!$objEmp->buscar($idEmp)) {
                    echo "Esta Empresa no ha sido encontrada.\n";
                    break;
                }
                echo "Ingrese el nro de Empleado del Responsable del Viaje: \n";
                $nroEmp = trim(fgets(STDIN));
                if (!is_numeric($nroEmp)) {
                    echo "El número de empleado del responsable debe ser un número.\n";
                    break;
                }
                // Creo el objeto ResponsableV y lo busco
                $objRespV = new ResponsableV();
                if (!$objRespV->buscar($nroEmp)) {
                    echo "Este Responsable no ha sido encontrado.\n";
                    break;
                }
                echo "Ingrese el Importe a pagar en el Viaje: \n";
                $importe = trim(fgets(STDIN));
                if (!is_numeric($importe)) {
                    echo "El importe debe ser un número.\n";
                    break;
                }
                //Creo el objeto viaje
                $objViaje = new Viaje();
                $objViaje->cargar(0, $destinoViaje, $cantMax, $objEmp, $objRespV, $importe, []);
                if ($objViaje->insertar()) {
                    echo "Su Viaje ha sido agregado.\n";
                } else {
                    echo "Ha habido un error al cargar su Viaje.\n";
                }
                break;
    
            case 2:
                echo "OPCIÓN MODIFICAR Viaje\n";
                echo "Ingrese ID de la Viaje a modificar: \n";
                $idViaje = trim(fgets(STDIN));
                if (!is_numeric($idViaje)) {
                    echo "El ID del viaje debe ser un número.\n";
                    break;
                }
    
                $objViaje = new Viaje();
                if ($objViaje->buscar($idViaje)) {//Busco el Viaje con el id ingresado
                    echo "Viaje encontrado: \n";
                    echo "Ingrese el nuevo destino de viaje: \n";
                    $destinoViaje = trim(fgets(STDIN));
                    //Evaluamos que si ingresa un destino como vacío, el destino seguirá siendo el mismo
                    if ($destinoViaje == '') {
                        $destinoViaje = $objViaje->getVDestino();
                    }
    
                    echo "Ingrese la nueva cantidad max. de Pasajeros en el Viaje: \n";
                    $cantMax = trim(fgets(STDIN));
                    if ($cantMax == '') {
                        $cantMax = $objViaje->getVCantMaxPasajeros();
                    } elseif (!is_numeric($cantMax)) {
                        echo "La cantidad máxima de pasajeros debe ser un número.\n";
                        break;
                    }
                    //Hago lo mismo que hice con el destino
    
                    echo "Ingrese el nuevo ID de Empresa: \n";
                    $idEmp = trim(fgets(STDIN));
                    $objEmp = new Empresa;
                    //Evaluamos que exista la Empresa
                    if ($idEmp != "" && !$objEmp->buscar($idEmp)) {
                        echo "Esta Empresa no ha sido encontrada. Es probable que no exista.\n";
                        break;
                    }
                    if ($idEmp == "") {
                        $objEmp = $objViaje->getObjEmpresa();
                    }
    
                    echo "Ingrese el nuevo nro de empleado del Responsable del Viaje: \n";
                    $nroEmpleado = trim(fgets(STDIN));
                    $objRespV = new ResponsableV();
                    //Hago lo mismo que con el id de la empresa
                    if ($nroEmpleado != "" && !$objRespV->Buscar($nroEmpleado)) {
                        echo "Esta Responsable no ha sido encontrado. Es probable que no exista.\n";
                        $objRespV = $objViaje->getObjResponsable();
                    } elseif ($nroEmpleado == "") {
                        $objRespV = $objViaje->getObjResponsable();
                    } elseif (!is_numeric($nroEmpleado)) {
                        echo "El número de empleado del responsable debe ser un número.\n";
                        break;
                    }
    
                    echo "Ingrese el nuevo Importe del Viaje: \n";
                    $importe = trim(fgets(STDIN));
                    if ($importe == "") {
                        $importe = $objViaje->getVImporte();
                    } elseif (!is_numeric($importe)) {
                        echo "El importe debe ser un número.\n";
                        break;
                    }
    
                    //Cargamos todos los datos
                    $objViaje->cargar($idViaje, $destinoViaje, $cantMax, $objEmp, $objRespV, $importe);
    
                    if ($objViaje->modificar()) {
                        echo "Su Viaje ha sido modificado.\n";
                    } else {
                        echo "Ha habido un error al modificar su Viaje.\n";
                    }
                } else {
                    echo "Su Viaje no existe.\n";
                }
                break;
            case 3:
                echo "OPCIÓN ELIMINAR Viaje\n";
                echo "Ingrese ID de la Viaje a eliminar: \n";
                $idViaje = trim(fgets(STDIN));

                if (!is_numeric($idViaje)) {
                    echo "El ID del Viaje debe ser numérico.\n";
                    break;
                }

                $objViaje = new Viaje();
                if ($objViaje->buscar($idViaje)) {
                    if ($objViaje->eliminar()) {
                        echo "Su Viaje ha sido eliminado.\n";
                    } else {
                        echo "Ha habido un error al eliminar su Viaje.\n";
                    }
                } else {
                    echo "No se encontró el Viaje con ese ID.\n";
                }
                break;
            case 4:
                echo "OPCIÓN BUSCAR VIAJE\n";
                echo "Ingrese ID del viaje a buscar: \n";
                $idViaje = trim(fgets(STDIN));

                if (!is_numeric($idViaje)) {
                    echo "El ID del Viaje debe ser numérico.\n";
                    break;
                }

                $objViaje = new Viaje();
                if ($objViaje->buscar($idViaje)) {
                    echo "Viaje encontrado: \n";
                    echo "Id de Viaje: " . $objViaje->getIdViaje() . "\n";
                    echo "Destino: " . $objViaje->getVDestino() . "\n";
                    echo "Cantidad Máxima de Pasajeros: " . $objViaje->getVCantMaxPasajeros() . "\n";
                    echo "Importe: " . $objViaje->getVImporte() . "\n";
                    
                    // Mostrar datos de la Empresa
                    $empresa = $objViaje->getObjEmpresa();
                    echo "Id de Empresa: " . $empresa->getIdEmpresa() . "\n";
                    echo "Nombre de Empresa: " . $empresa->getNombre() . "\n";
                    echo "Dirección de Empresa: " . $empresa->getDireccion() . "\n";
                    
                    // Mostrar datos del Responsable
                    $responsable = $objViaje->getObjResponsable();
                    echo "Número de Empleado Responsable: " . $responsable->getNroEmpleado() . "\n";
                    echo "Nombre del Responsable: " . $responsable->getNombre() . "\n";
                    echo "Apellido del Responsable: " . $responsable->getApellido() . "\n";
                    echo "Número de Licencia del Responsable: " . $responsable->getNroLicencia() . "\n";
                    
                    echo "\n";
                } else {
                    echo "Este Viaje no existe o el ID es incorrecto.\n";
                }
                break;
            case 5:
                echo "OPCIÓN LISTAR VIAJE\n";
                $objViaje = new Viaje();
                $viajeLista = $objViaje->listar();

                if ($viajeLista != null) {
                    foreach ($viajeLista as $viaje) {
                        echo "Id de Viaje: " . $viaje->getIdViaje() . "\n";
                        echo "Destino: " . $viaje->getVDestino() . "\n";
                        echo "Cantidad Máxima de Pasajeros: " . $viaje->getVCantMaxPasajeros() . "\n";
                        echo "Importe: " . $viaje->getVImporte() . "\n";
                        
                        // Mostrar datos de la Empresa
                        $empresa = $viaje->getObjEmpresa();
                        echo "Id de Empresa: " . $empresa->getIdEmpresa() . "\n";
                        echo "Nombre de Empresa: " . $empresa->getNombre() . "\n";
                        echo "Dirección de Empresa: " . $empresa->getDireccion() . "\n";
                        
                        // Mostrar datos del Responsable
                        $responsable = $viaje->getObjResponsable();
                        echo "Número de Empleado Responsable: " . $responsable->getNroEmpleado() . "\n";
                        echo "Nombre del Responsable: " . $responsable->getNombre() . "\n";
                        echo "Apellido del Responsable: " . $responsable->getApellido() . "\n";
                        echo "Número de Licencia del Responsable: " . $responsable->getNroLicencia() . "\n";
                        
                        echo "------------------------------------------\n";
                    }
                } else {
                    echo "No hay Viajes cargados.\n";
                }
                break;
            case 6:
                echo "OPCIÓN LISTAR PASAJEROS DEL VIAJE\n";
                echo "Ingrese ID del viaje en el que quiera ver los Pasajeros: \n";
                $idViaje = trim(fgets(STDIN));
                $objViaje = new Viaje();

                if ($objViaje->buscar($idViaje)) {
                    $pasajeros = $objViaje->getColPasajeros();

                    if (!empty($pasajeros)) {
                        echo "Viaje con ID: " . $idViaje . "\nLista de Pasajeros: \n";
                        foreach ($pasajeros as $pasajero) {
                            echo "Documento: " . $pasajero->getDocumento() . "\n";
                            echo "Nombre: " . $pasajero->getNombre() . "\n";
                            echo "Apellido: " . $pasajero->getApellido() . "\n";
                            echo "Teléfono: " . $pasajero->getTelefono() . "\n";
                            echo "------------------------------------------\n";
                        }
                    } else {
                        echo "No hay pasajeros para este viaje.\n";
                    }
                } else {
                    echo "Este Viaje no existe o el ID es el incorrecto.\n";
                }
                break;
            case 7:
                $salirMenu = true;
                break;
            default:
            echo "Esta Opción no existe. \n";
            break;
        }
    } while (!$salirMenu);
}

function menuDeResponsableV() {
    do {
        echo "****************************************\n";
        echo "|Usted accedió a la sección Responsable|\n";
        echo "| Elija una opción                     |\n";
        echo "|   1. Agregar                         |\n";
        echo "|   2. Modificar                       |\n";
        echo "|   3. Eliminar                        |\n";
        echo "|   4. Buscar                          |\n";
        echo "|   5. Listar                          |\n";
        echo "|   6. Volver al menú                  |\n";
        echo "****************************************\n";

        $opcionResp = trim(fgets(STDIN));
        $salirMenu = false;

        switch ($opcionResp) {
            case 1:
                echo "OPCIÓN AGREGAR Responsable\n";
                echo "Ingrese el Nombre del Responsable: \n";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese el Apellido del Responsable: \n";
                $apellido = trim(fgets(STDIN));
                echo "Ingrese el N° de Documento del Responsable: \n";
                $dni = trim(fgets(STDIN));
                if (!is_numeric($dni)) {
                    echo "El N° de Documento debe ser un número.\n";
                    break;
                }
                echo "Ingrese N° de Licencia: \n";
                $numLic = trim(fgets(STDIN));
                if (!is_numeric($numLic)) {
                    echo "El N° de Licencia debe ser un número.\n";
                    break;
                }
                // Crear un objeto ResponsableV
                $objRespV = new ResponsableV();
            
                // Cargar los datos en el objeto
                $objRespV->cargar($dni, $nombre, $apellido, 0, $numLic);
            
                // Verificar que los campos necesarios no estén vacíos
                if ($nombre != "" && $apellido != "" && $dni != "" && $numLic != "") {
                    // Intentar insertar el responsable
                    if ($objRespV->insertar()) {
                        echo "El Responsable ha sido agregado.\n";
                    } else {
                        echo "El Responsable no se pudo agregar.\n";
                        echo "Error: " . $objRespV->getMsjOperacion() . "\n";
                    }
                } else {
                    echo "Faltan datos.\n";
                }
                break;

                case 2:
                    echo "OPCIÓN MODIFICAR Responsable\n";
                    echo "Ingrese el nro de empleado del Responsable a modificar: \n";
                    $nroEmpleado = trim(fgets(STDIN));
                    if (!is_numeric($nroEmpleado)) {
                        echo "El N° de empleado debe ser un número.\n";
                        break;
                    }
        
                    $objRespV = new ResponsableV();
                    if ($objRespV->buscar($nroEmpleado)) {
                        echo "Ingrese el nuevo Nombre del Responsable (actual: " . $objRespV->getNombre() . "): \n";
                        $nombre = trim(fgets(STDIN));
                        echo "Ingrese el nuevo Apellido del Responsable (actual: " . $objRespV->getApellido() . "): \n";
                        $apellido = trim(fgets(STDIN));
                        echo "Ingrese el nuevo N° de Licencia (actual: " . $objRespV->getNroLicencia() . "): \n";
                        $numLic = trim(fgets(STDIN));
                        if (!is_numeric($numLic)) {
                            echo "El N° de Licencia debe ser un número.\n";
                            break;
                        }
        
                        // Verificar y mantener los valores existentes si están vacíos
                        if (empty($nombre)) {
                            $nombre = $objRespV->getNombre();
                        }
                        if (empty($apellido)) {
                            $apellido = $objRespV->getApellido();
                        }
                        if (empty($numLic)) {
                            $numLic = $objRespV->getNroLicencia();
                        }
        
                        $objRespV->cargar($objRespV->getDocumento(), $nombre, $apellido, $nroEmpleado, $numLic);
                        if ($objRespV->modificar()) {
                            echo "El Responsable ha sido modificado.\n";
                        } else {
                            echo "El Responsable no se pudo modificar.\n";
                            echo "Error al modificar en persona: " . $objRespV->getMsjOperacion() . "\n";
                        }
                    } else {
                        echo "No se encontró un Responsable con ese ID.\n";
                    }
                    break;
    
                case 3:
                    echo "OPCIÓN ELIMINAR Responsable\n";
                    echo "Ingrese el nro de empleado del Responsable a eliminar: \n";
                    $nroEmpleado = trim(fgets(STDIN));

                    if (!is_numeric($nroEmpleado)) {
                        echo "El Nro del Empleado debe ser numérico.\n";
                        break;
                    }
    
                    $objRespV = new ResponsableV();
                    if ($objRespV->buscar($nroEmpleado)) {
                        if ($objRespV->eliminar()) {
                            echo "El Responsable ha sido eliminado.\n";
                        } else {
                            echo "El Responsable no se pudo eliminar.\n";
                            echo "Error al eliminar en persona: " . $objRespV->getMsjOperacion() . "\n";
                        }
                    } else {
                        echo "No se encontró un Responsable con ese nro de empleado.\n";
                    }
                    break;
    
                case 4:
                    echo "OPCIÓN BUSCAR Responsable\n";
                    echo "Ingrese el nro de empleado del Responsable a buscar: \n";
                    $nroEmpleado = trim(fgets(STDIN));

                    if (!is_numeric($nroEmpleado)) {
                        echo "El Nro del Empleado debe ser numérico.\n";
                        break;
                    }

                    $objRespV = new ResponsableV();
                    if ($objRespV->buscar($nroEmpleado)) {
                        echo "Responsable encontrado: \n";
                        echo "Documento: " . $objRespV->getDocumento() . "\n";
                        echo "Nombre: " . $objRespV->getNombre() . "\n";
                        echo "Apellido: " . $objRespV->getApellido() . "\n";
                        echo "Número de Empleado: " . $objRespV->getNroEmpleado() . "\n";
                        echo "Número de Licencia: " . $objRespV->getNroLicencia() . "\n";
                        echo "\n";
                    } else {
                        echo "No se encontró un Responsable con ese nro de empleado.\n";
                    }
                    break;
    
                case 5:
                    echo "OPCIÓN LISTAR Responsables\n";
                    $objRespV = new ResponsableV();
                    $colResponsables = $objRespV->listar();

                    if (!empty($colResponsables)) {
                        foreach ($colResponsables as $respV) {
                            echo "Documento: " . $respV->getDocumento() . "\n";
                            echo "Nombre: " . $respV->getNombre() . "\n";
                            echo "Apellido: " . $respV->getApellido() . "\n";
                            echo "Número de Empleado: " . $respV->getNroEmpleado() . "\n";
                            echo "Número de Licencia: " . $respV->getNroLicencia() . "\n";
                            echo "------------------------------------------\n";
                        }
                    } else {
                        echo "No hay Responsables para listar.\n";
                    }
                    break;
    
                case 6:
                    $salirMenu = true;
                    break;
    
                default:
                    echo "Esta Opción no existe. \n";
                    break;
            }
    } while (!$salirMenu);
}


function menuDePasajero() {
    do {
        echo "*************************************\n";
        echo "|Usted accedió a la sección Pasajero|\n";
        echo "| Elija una opción                  |\n";
        echo "|   1. Agregar                      |\n";
        echo "|   2. Modificar                    |\n";
        echo "|   3. Eliminar                     |\n";
        echo "|   4. Buscar                       |\n";
        echo "|   5. Listar                       |\n";
        echo "|   6. Volver al menú               |\n";
        echo "*************************************\n";

        $opcionPasaj = trim(fgets(STDIN));
        $salirMenu = false;

        switch($opcionPasaj) {

            case 1:
                echo "OPCIÓN AGREGAR Pasajero\n";
                echo "Ingrese el ID del Viaje al que pertenece el Pasajero: \n";
                $idViaje = trim(fgets(STDIN));
            
                // Verificar si el idViaje es numérico
                if (!is_numeric($idViaje)) {
                    echo "El ID del Viaje debe ser numérico.\n";
                    break;
                }
            
                // Verificar si el viaje existe
                $objViaje = new Viaje();
                if (!$objViaje->buscar($idViaje)) {
                    echo "El Viaje con ID $idViaje no existe.\n";
                    break;
                }
            
                // Verificar disponibilidad de pasajes
                if (!$objViaje->pasajeDisponible()) {
                    echo "El viaje seleccionado está lleno.\n";
                    break;
                }
            
                // Continuar con la carga de datos del pasajero
                $objPasajero = new Pasajero();
            
                echo "Ingrese el nombre del Pasajero: \n";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese el apellido del Pasajero: \n";
                $apellido = trim(fgets(STDIN));
                echo "Ingrese el teléfono del Pasajero: \n";
                $telefono = trim(fgets(STDIN));
            
                // Verificar si el teléfono es numérico
                if (!is_numeric($telefono)) {
                    echo "El teléfono debe ser numérico.\n";
                    break;
                }
            
                echo "Ingrese el documento del Pasajero: \n";
                $documento = trim(fgets(STDIN));
            
                // Verificar si el documento es numérico
                if (!is_numeric($documento)) {
                    echo "El documento debe ser numérico.\n";
                    break;
                }
            
                // Crear el objeto Pasajero y cargar los datos
                $objPasajero->cargar($documento, $nombre, $apellido, 0, $telefono, $objViaje);
            
                // Insertar el pasajero en la base de datos
                $id = $objPasajero->getIdPasajero();
                if ($objPasajero->buscar($id)) {
                    echo "El pasajero con ID $id ya existe.\n";
                } else {
                    if ($objPasajero->insertar()) {
                        echo "El Pasajero ha sido agregado correctamente.\n";
                    } else {
                        echo "Error al agregar el Pasajero.\n";
                        echo "Mensaje de error: " . $objPasajero->getMsjOperacion() . "\n";
                    }
                }
                break;
            
            case 2:
                echo "OPCIÓN MODIFICAR Pasajero\n";
                echo "Ingrese ID del Pasajero que desea modificar: \n";
                $id = trim(fgets(STDIN));

                // Verificar si el pasajero existe
                $objPasajero = new Pasajero();

                if (!is_numeric($id)) {
                    echo "El ID del Pasajero debe ser numérico.\n";
                    break;
                }

                if (!$objPasajero->buscar($id)) {
                    echo "El Pasajero con ID: $id no existe.\n";
                    break;
                }

                echo "Ingrese el nuevo nombre del Pasajero (dejar en blanco para mantener el actual): \n";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese el nuevo apellido del Pasajero (dejar en blanco para mantener el actual): \n";
                $apellido = trim(fgets(STDIN));
                echo "Ingrese el nuevo teléfono del Pasajero (dejar en blanco para mantener el actual): \n";
                $telefono = trim(fgets(STDIN));
                
                // Modificar el pasajero en la base de datos y verificar q no esten vacios
                if (empty($nombre)) {
                    $nombre = $objPasajero->getNombre();
                } else {
                    $objPasajero->setNombre($nombre);
                }
                if (empty($apellido)) {
                    $apellido = $objPasajero->getApellido();
                } else {
                    $objPasajero->setApellido($apellido);
                }
                if (empty($telefono)) {
                    $telefono = $objPasajero->getTelefono();
                } else {
                    $objPasajero->setTelefono($telefono);
                }

                if ($objPasajero->modificar()) {
                    echo "El Pasajero ha sido modificado correctamente.\n";
                } else {
                    echo "Error al modificar el Pasajero.\n";
                    echo "Mensaje de error: " . $objPasajero->getMsjOperacion() . "\n";
                }
                break;

            case 3:
                echo "OPCIÓN ELIMINAR Pasajero\n";
                echo "Ingrese ID del Pasajero que desea eliminar: \n";
                $id = trim(fgets(STDIN));
                if (!is_numeric($id)) {
                    echo "El ID del Pasajero debe ser numérico.\n";
                    break;
                }

                // Verificar si el pasajero existe
                $objPasajero = new Pasajero();
                if (!$objPasajero->buscar($id)) {
                    echo "El Pasajero con ID: $id no existe.\n";
                    break;
                }

                // Eliminar el pasajero de la base de datos
                if ($objPasajero->eliminar()) {
                    echo "El Pasajero ha sido eliminado correctamente.\n";
                } else {
                    echo "Error al eliminar el Pasajero.\n";
                    echo "Mensaje de error: " . $objPasajero->getMsjOperacion() . "\n";
                }
                break;

            case 4:
                echo "OPCIÓN BUSCAR Pasajero\n";
                echo "Ingrese el ID del Pasajero que desea buscar: \n";
                $id = trim(fgets(STDIN));
                
                // Verificar si el ID del Pasajero es numérico
                if (!is_numeric($id)) {
                    echo "El ID del Pasajero debe ser numérico.\n";
                    break;
                }
                
                // Buscar el pasajero en la base de datos
                $objPasajero = new Pasajero();
                if ($objPasajero->buscar($id)) {
                    echo "Información del Pasajero:\n";
                    echo "ID de Pasajero: " . $objPasajero->getIdPasajero() . "\n";
                    echo "Nombre: " . $objPasajero->getNombre() . "\n";
                    echo "Apellido: " . $objPasajero->getApellido() . "\n";
                    echo "Teléfono: " . $objPasajero->getTelefono() . "\n";
                    echo "Documento: " . $objPasajero->getDocumento() . "\n";
                    
                    // Mostrar id del Viaje al que pertenece el Pasajero
                    echo "ID de Viaje: ". $objPasajero->getObjViaje() . "\n";
                } else {
                    echo "El Pasajero con el ID: $id no fue encontrado.\n";
                }
                break;

            case 5:
                echo "OPCIÓN LISTAR Pasajeros\n";

                // Listar todos los pasajeros
                $objPasajero = new Pasajero();
                $listaPasajeros = $objPasajero->listar();

                if (!empty($listaPasajeros)) {
                    echo "Lista de Pasajeros:\n";
                    foreach ($listaPasajeros as $pasajero) {
                        echo "ID del Viaje: " . $pasajero->getObjViaje() . "\n";
                        echo "Nombre: " . $pasajero->getNombre() . "\n";
                        echo "Apellido: " . $pasajero->getApellido() . "\n";
                        echo "Teléfono: " . $pasajero->getTelefono() . "\n";
                        echo "Documento: " . $pasajero->getDocumento() . "\n";
                        echo "ID de Pasajero: " . $pasajero->getIdPasajero() . "\n";
                        echo "------------------------------------------\n";
                    }
                } else {
                    echo "No se encontraron pasajeros.\n";
                }
                break;

            case 6:
                $salirMenu = true;
                break;

            default:
                echo "Esta Opción no existe. \n";
                break;
        }

    } while (!$salirMenu);
}