-- Afegir taula/es noves per la pràctica de PHP

SET ECHO OFF

PROMPT Construint la taula Premis per la BD de la pràctica...
  
SET termout on
SET feedback off
-- si alguna cosa no va prou bé, podem posar el feedback a on en la linia anterior

--
-- Table structure for table `Premis`
--

DROP TABLE Premis CASCADE CONSTRAINT;
CREATE TABLE Premis (
  personatge varchar2(15) DEFAULT NULL,
  cursa varchar2(15) NOT NULL,
  vehicle varchar2(10) DEFAULT NULL,
  inici_real date DEFAULT NULL,
  temps decimal(6,3) DEFAULT NULL,
  premi decimal(5,0) DEFAULT NULL,
  n_participants decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (cursa, personatge),
  CONSTRAINT cf_Premis_cursa
    FOREIGN KEY (cursa)
    REFERENCES curses (codi),
  CONSTRAINT cf_Premis_personatge
    FOREIGN KEY (personatge)
    REFERENCES personatges (alias)
);

COMMIT;

PROMPT Proces finalitzat.

SET termout on
SET feedback on 
SET ECHO ON
