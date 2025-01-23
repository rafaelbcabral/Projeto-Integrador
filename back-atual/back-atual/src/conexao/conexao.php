<?php

/**
 * Undocumented function
 *
 * @return PDO
 */
function conectar(): PDO
{

    return new PDO(
        'mysql:dbname=dbreserva;host=localhost;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}


// function conectar(): PDO
// {
//     return new PDO(
//         'sqlite:lasaveur.db',  // Caminho do arquivo .db
//         null,                  // SQLite não precisa de usuário
//         null,                  // SQLite não precisa de senha
//         [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // Configura para lançar exceções em caso de erro
//     );
// }

// try {
//     // Conectar ao banco de dados SQLite
//     $pdo = conectar();

//     $sql = "
//     CREATE TABLE IF NOT EXISTS mesa (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         numero INTEGER NOT NULL UNIQUE,
//         disponivel INTEGER DEFAULT 1
//     );

//     CREATE TABLE IF NOT EXISTS funcionario (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         nome TEXT NOT NULL,
//         usuario TEXT NOT NULL UNIQUE,
//         senha TEXT NOT NULL
//     );

//     CREATE TABLE IF NOT EXISTS categoria (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         nome TEXT NOT NULL
//     );

//     CREATE TABLE IF NOT EXISTS item (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         codigo TEXT UNIQUE NOT NULL,
//         descricao TEXT NOT NULL,
//         preco DECIMAL(10, 2) NOT NULL,
//         categoria INTEGER NOT NULL,
//         FOREIGN KEY (categoria) REFERENCES categoria(id)
//     );

//     CREATE TABLE IF NOT EXISTS reserva (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         nomeCliente TEXT NOT NULL,
//         telefoneCliente TEXT NOT NULL,
//         dataReservada DATE NOT NULL,
//         inicio TIME NOT NULL,
//         fim TIME NOT NULL,
//         mesa INTEGER NOT NULL,
//         funcionario INTEGER NOT NULL,
//         status TEXT CHECK(status IN ('confirmada', 'cancelada')) DEFAULT 'confirmada',
//         FOREIGN KEY (mesa) REFERENCES mesa(id),
//         FOREIGN KEY (funcionario) REFERENCES funcionario(id)
//     );

//     CREATE TABLE IF NOT EXISTS consumo (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         reserva INTEGER NOT NULL,
//         item INTEGER NOT NULL,
//         quantidade INTEGER NOT NULL DEFAULT 1,
//         funcionario INTEGER NOT NULL,
//         FOREIGN KEY (reserva) REFERENCES reserva(id),
//         FOREIGN KEY (item) REFERENCES item(id),
//         FOREIGN KEY (funcionario) REFERENCES funcionario(id)
//     );

//     CREATE TABLE IF NOT EXISTS pagamento (
//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         reserva INTEGER NOT NULL,
//         valorTotal DECIMAL(10, 2) NOT NULL,
//         formaPagamento TEXT CHECK(formaPagamento IN ('dinheiro', 'pix', 'debito', 'credito')) NOT NULL,
//         desconto DECIMAL(5, 2) DEFAULT 0,
//         totalComDesconto DECIMAL(10, 2),
//         FOREIGN KEY (reserva) REFERENCES reserva(id)
//     );
//     ";

//     // Executa o SQL para criar as tabelas
//     $pdo->exec($sql);
//     echo "Tabelas criadas com sucesso!";
// } catch (PDOException $e) {
//     echo "Erro ao conectar ou criar o banco de dados: " . $e->getMessage();
// }
