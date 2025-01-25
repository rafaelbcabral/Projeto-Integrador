<?php

require_once 'src/conexao/conexao.php';

describe('Função conectar', function () {

    it('deve retornar uma instância de PDO', function () {
        // Chama a função conectar e verifica se retorna uma instância de PDO
        $result = conectar();

        // Espera que a função conectar retorne uma instância de PDO
        expect($result)->toBeAnInstanceOf(PDO::class);
    });
});
