CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE persona (
    nombre varchar(150),
    apellido varchar(150),
    documento int(15),
    PRIMARY KEY (documento)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint UNIQUE,
    rdocumento int(15),
    PRIMARY KEY (rnumeroempleado),
     FOREIGN KEY (rdocumento) REFERENCES persona (documento)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, /*codigo de viaje*/
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajero (
     idpasajeros bigint AUTO_INCREMENT,
    pdocumento int(15),
	ptelefono int, 
	idviaje bigint,
    PRIMARY KEY (idpasajeros),
    FOREIGN KEY (pdocumento) REFERENCES persona (documento),
    FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;