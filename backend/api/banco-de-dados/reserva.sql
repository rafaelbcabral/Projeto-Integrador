-- CREATE TABLE reserva (
--     id INT NOT NULL AUTO_INCREMENT,
--     nome_cliente VARCHAR(100) NOT NULL,
--     data_reservada DATE NOT NULL,
--     inicio_reserva TIME NOT NULL,
--     fim_reserva TIME NOT NULL,
--     mesa INT NOT NULL,
--     funcionario INT NOT NULL,
--     status ENUM('confirmada', 'cancelada', 'pendente') DEFAULT 'confirmada',
--     PRIMARY KEY (id),
--     KEY fk_mesa (mesa),
--     KEY fk_funcionario (funcionario)
-- );

-- CREATE TABLE funcionario (
--     id INT NOT NULL AUTO_INCREMENT,
--     nome VARCHAR(100) NOT NULL,
--     usuario VARCHAR(100) NOT NULL UNIQUE,
--     senha VARCHAR(20) NOT NULL,
--     PRIMARY KEY (id)
-- );

-- CREATE TABLE mesa (
--     id INT NOT NULL AUTO_INCREMENT,
--     numero INT NOT NULL UNIQUE,
--     disponivel TINYINT(1) DEFAULT 1,
--     PRIMARY KEY (id)
-- );

use dbreserva;


CREATE TABLE reserva (
    id INT NOT NULL AUTO_INCREMENT,
    nome_cliente VARCHAR(100) NOT NULL,
    data_reservada DATE NOT NULL,
    inicio_reserva TIME NOT NULL,
    fim_reserva TIME NOT NULL,
    mesa INT NOT NULL,
    funcionario INT NOT NULL,
    status ENUM('confirmada', 'cancelada', 'pendente') DEFAULT 'confirmada',
    PRIMARY KEY (id),
    FOREIGN KEY (mesa) REFERENCES mesa(id),
    FOREIGN KEY (funcionario) REFERENCES funcionario(id)
) ENGINE=InnoDB;

CREATE TABLE funcionario (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE mesa (
    id INT(11) NOT NULL AUTO_INCREMENT,
    numero INT(11) NOT NULL UNIQUE,
    disponivel TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;


-- COMANDOS EXTRAS USADOS NA TABELA
-- use dbreservas;

-- DELETE from reserva;
-- ALTER TABLE reserva AUTO_INCREMENT = 1;