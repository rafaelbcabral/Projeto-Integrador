-- Inserir dados na tabela mesa
INSERT INTO mesa (numero) VALUES 
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10);

-- Inserir dados na tabela funcionario
INSERT INTO funcionario (nome, usuario, senha) VALUES 
('João Silva', 'joao.silva', 'senha123'),
('Maria Oliveira', 'maria.oliveira', 'senha456'),
('Carlos Souza', 'carlos.souza', 'senha789');

INSERT INTO categoria (nome) VALUES
('Entrada'),
('Prato Principal'),
('Sobremesa'),
('Bebida');

INSERT INTO item (codigo, descricao, preco, categoria) VALUES
('A001', 'Salada de Folhas', 15.90, 1),
('P001', 'Filé de Peixe', 35.50, 2),
('S001', 'Torta de Chocolate', 12.00, 3),
('B001', 'Coca Cola', 10.00, 4);


-- TESTE DE FUNCIONALIDADE DO SQL

INSERT INTO categoria (nome) VALUES
('Entrada'),
('Prato Principal'),
('Sobremesa'),
('Bebida');

INSERT INTO item (codigo, descricao, preco, categoria) VALUES
('A001', 'Salada de Folhas', 15.90, 1),
('P001', 'Filé de Peixe', 35.50, 2),
('S001', 'Torta de Chocolate', 12.00, 3),
('B001', 'Coca Cola', 10.00, 4);

INSERT INTO reserva (nomeCliente, telefoneCliente, dataReservada, inicio, fim, mesa, funcionario) VALUES
('Carlos Pereira', '987654321', '2025-01-15', '19:00:00', '21:00:00', 1, 1),
('Ana Souza', '123456789', '2025-01-16', '20:00:00', '22:00:00', 2, 2);

INSERT INTO consumo (reserva, item, quantidade, funcionario) VALUES
(1, 1, 2, 1),
(1, 2, 1, 1),
(2, 3, 3, 2);

INSERT INTO pagamento (reserva, valorTotal, formaPagamento, desconto, totalComDesconto) VALUES
(1, 75.30, 'dinheiro', 5, 70.30),
(2, 90.00, 'pix', 0, 90.00);

