use dbreserva;

CREATE TABLE IF NOT EXISTS funcionario (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    cargo ENUM('gerente', 'atendente') NOT NULL,
    senha VARCHAR(255) NOT NULL,
    salt VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mesa (
    id INT NOT NULL AUTO_INCREMENT,
    numero INT NOT NULL UNIQUE,
    disponivel TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reserva (
    id INT NOT NULL AUTO_INCREMENT,
    nomeCliente VARCHAR(100) NOT NULL,
    telefoneCliente VARCHAR(100) NOT NULL,
    dataReservada DATE NOT NULL,
    inicio TIME NOT NULL,
    fim TIME NOT NULL,
    mesa INT NOT NULL,
    funcionario INT NOT NULL,
    status ENUM('confirmada', 'cancelada') DEFAULT 'confirmada',
    statusPagamento ENUM('aberto', 'fechado') DEFAULT 'aberto',
    PRIMARY KEY (id),
    FOREIGN KEY (mesa) REFERENCES mesa(id),
    FOREIGN KEY (funcionario) REFERENCES funcionario(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    nome VARCHAR(100) NOT NULL         
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS item (
    id INT AUTO_INCREMENT PRIMARY KEY,     
    codigo VARCHAR(50) UNIQUE NOT NULL,    
    descricao VARCHAR(255) NOT NULL,        
    preco DECIMAL(10, 2) NOT NULL,         
    categoria INT NOT NULL,             
    FOREIGN KEY (categoria) REFERENCES categoria (id)  
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS consumo (
    id INT AUTO_INCREMENT PRIMARY KEY,         
    reserva INT NOT NULL,                   
    item INT NOT NULL,            
    quantidade INT NOT NULL DEFAULT 1,         
    funcionario INT NOT NULL,              
    valorTotalPorItem decimal(10,2) NOT NULL, 
    FOREIGN KEY (reserva) REFERENCES reserva(id),  
    FOREIGN KEY (item) REFERENCES item (id),  
    FOREIGN KEY (funcionario) REFERENCES funcionario(id) 
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,               
    reserva INT NOT NULL,                          
    valorTotal DECIMAL(10, 2) NOT NULL,                   
    formaPagamento ENUM('dinheiro', 'pix', 'debito', 'credito') NOT NULL,  
    desconto DECIMAL(5, 2) DEFAULT 0,                
    totalComDesconto DECIMAL(10, 2),                
    totalDeItens INT NOT NULL, 
    FOREIGN KEY (reserva) REFERENCES reserva(id)  
)ENGINE=InnoDB;