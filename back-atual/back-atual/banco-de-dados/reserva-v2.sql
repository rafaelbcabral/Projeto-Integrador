CREATE TABLE reserva (
    id INT NOT NULL AUTO_INCREMENT,
    nomeCliente VARCHAR(100) NOT NULL,
    telefoneCliente VARCHAR(100) NOT NULL,
    dataReservada DATE NOT NULL,
    inicio TIME NOT NULL,
    fim TIME NOT NULL,
    mesa INT NOT NULL,
    funcionario INT NOT NULL,
    status ENUM('confirmada', 'cancelada') DEFAULT 'confirmada',
    PRIMARY KEY (id),
    FOREIGN KEY (mesa) REFERENCES mesa(id),
    FOREIGN KEY (funcionario) REFERENCES funcionario(id)
) ENGINE=InnoDB;

CREATE TABLE funcionario (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(20) NOT NULL,
    salt VARCHAR(32) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE mesa (
    id INT NOT NULL AUTO_INCREMENT,
    numero INT NOT NULL UNIQUE,
    disponivel TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único da categoria
    nome VARCHAR(100) NOT NULL,         -- Nome da categoria (por exemplo, "entrada", "prato principal")
);

CREATE TABLE item (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único do item
    codigo VARCHAR(50) UNIQUE NOT NULL,     -- Código único do item
    descricao VARCHAR(255) NOT NULL,        -- Descrição do item
    preco DECIMAL(10, 2) NOT NULL,         -- Preço do item
    categoria INT NOT NULL,             -- Identificador da categoria do item
    FOREIGN KEY (categoria) REFERENCES categoria (id)  -- Relaciona com a tabela categoria_item
);

CREATE TABLE consumo (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Identificador único do consumo
    reserva INT NOT NULL,                   -- Identificador da reserva associada ao consumo
    item INT NOT NULL,             -- Identificador do item do cardápio consumido
    quantidade INT NOT NULL DEFAULT 1,         -- Quantidade consumida do item
    funcionario INT NOT NULL,               -- Identificador do funcionário que registrou o consumo
    FOREIGN KEY (reserva) REFERENCES reserva(id),  -- Relaciona com a tabela reserva
    FOREIGN KEY (item) REFERENCES item_cardapio(id),  -- Relaciona com a tabela item_cardapio
    FOREIGN KEY (funcionario) REFERENCES usuario(id)  -- Relaciona com a tabela de usuários
);

CREATE TABLE pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Identificador único do pagamento
    reserva INT NOT NULL,                          -- Identificador da reserva associada ao pagamento
    valor DECIMAL(10, 2) NOT NULL,                    -- Valor total pago
    formaPagamento ENUM('dinheiro', 'pix', 'debito', 'credito') NOT NULL,  -- Forma de pagamento
    desconto DECIMAL(5, 2) DEFAULT 0,                 -- Desconto aplicado (se houver)
    totalComDesconto DECIMAL(10, 2),                -- Total com desconto
    FOREIGN KEY (reserva) REFERENCES reserva(id)  -- Relaciona com a tabela reserva
);

CREATE TABLE reserva (
    id INT NOT NULL AUTO_INCREMENT,
    nomeCliente VARCHAR(100) NOT NULL,
    telefoneCliente VARCHAR(100) NOT NULL,
    dataReservada DATE NOT NULL,
    inicio TIME NOT NULL,
    fim TIME NOT NULL,
    mesa INT NOT NULL,
    funcionario INT NOT NULL,
    status ENUM('confirmada', 'cancelada') DEFAULT 'confirmada',
    PRIMARY KEY (id),
    FOREIGN KEY (mesa) REFERENCES mesa(id),
    FOREIGN KEY (funcionario) REFERENCES funcionario(id)
) ENGINE=InnoDB;

CREATE TABLE funcionario (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(20) NOT NULL,
    -- salt VARCHAR(32) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE mesa (
    id INT NOT NULL AUTO_INCREMENT,
    numero INT NOT NULL UNIQUE,
    disponivel TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único da categoria
    nome VARCHAR(100) NOT NULL,         -- Nome da categoria (por exemplo, "entrada", "prato principal")
)ENGINE=InnoDB;

CREATE TABLE item (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único do item
    codigo VARCHAR(50) UNIQUE NOT NULL,     -- Código único do item
    descricao VARCHAR(255) NOT NULL,        -- Descrição do item
    preco DECIMAL(10, 2) NOT NULL,         -- Preço do item
    categoria INT NOT NULL,             -- Identificador da categoria do item
    FOREIGN KEY (categoria) REFERENCES categoria (id)  -- Relaciona com a tabela categoria_item
)ENGINE=InnoDB;

CREATE TABLE consumo (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Identificador único do consumo
    reserva INT NOT NULL,                   -- Identificador da reserva associada ao consumo
    item INT NOT NULL,             -- Identificador do item do cardápio consumido
    quantidade INT NOT NULL DEFAULT 1,         -- Quantidade consumida do item
    funcionario INT NOT NULL,               -- Identificador do funcionário que registrou o consumo
    FOREIGN KEY (reserva) REFERENCES reserva(id),  -- Relaciona com a tabela reserva
    FOREIGN KEY (item) REFERENCES item_cardapio(id),  -- Relaciona com a tabela item_cardapio
    FOREIGN KEY (funcionario) REFERENCES usuario(id)  -- Relaciona com a tabela de usuários
)ENGINE=InnoDB;

CREATE TABLE pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Identificador único do pagamento
    reserva INT NOT NULL,                          -- Identificador da reserva associada ao pagamento
    valorTotal DECIMAL(10, 2) NOT NULL,                    -- Valor total pago
    formaPagamento ENUM('dinheiro', 'pix', 'debito', 'credito') NOT NULL,  -- Forma de pagamento
    desconto DECIMAL(5, 2) DEFAULT 0,                 -- Desconto aplicado (se houver)
    totalComDesconto DECIMAL(10, 2),                -- Total com desconto
    FOREIGN KEY (reserva) REFERENCES reserva(id)  -- Relaciona com a tabela reserva
)ENGINE=InnoDB;