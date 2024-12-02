USE dbreserva;
-- Tabela de Funcionários
CREATE TABLE funcionario (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(20) NOT NULL
)ENGINE=InnoDB;

-- Tabela de Mesas
CREATE TABLE mesa (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    numero INT NOT NULL UNIQUE,
    disponivel BOOLEAN DEFAULT TRUE
)ENGINE=InnoDB;

-- Tabela de Reservas
CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(100) NOT NULL,
    data_reservada DATE NOT NULL,
    inicio_reserva TIME NOT NULL,
    fim_reserva TIME NOT NULL,
    mesa INT NOT NULL,
    funcionario INT NOT NULL,
    status ENUM('confirmada', 'cancelada', 'pendente') DEFAULT 'confirmada',
    FOREIGN KEY (mesa) REFERENCES mesa(id),
    FOREIGN KEY (funcionario) REFERENCES funcionario(id)
)ENGINE=InnoDB;