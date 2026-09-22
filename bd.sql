CREATE DATABASE bd;
USE bd;

CREATE TABLE professores (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nm_professor VARCHAR(200) NOT NULL,
    ds_email VARCHAR(200) NOT NULL UNIQUE,
    rm INT NOT NULL

   
);

CREATE TABLE turma (
    id INT NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    ds_curso VARCHAR(200) NOT NULL

  
);


CREATE TABLE lab (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    numero_lab INT NOT NULL UNIQUE,
    status enum('Liberado','Reservado') NOT NULL DEFAULT 'Liberado'

   
) ;


CREATE TABLE reservas (
    id INT NOT NULL AUTO_INCREMENT   PRIMARY KEY ,
    cd_professor INT NOT NULL,
    cd_turma INT NOT NULL,
    cd_lab INT NOT NULL,
    horario_inicio DATETIME NOT NULL,
    horario_termino DATETIME NOT NULL,
    
    FOREIGN KEY (cd_professor) REFERENCES professores(id),
    FOREIGN KEY (cd_turma) REFERENCES turma(id),
    FOREIGN KEY (cd_lab) REFERENCES lab(id)

) ;
