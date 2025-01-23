<?php

use Kahlan\Plugin\Double;

describe('CategoriaRepositorio', function () {
    beforeAll(function () {
        // Mock para a classe PDO
        $this->pdoMock = Double::instance(['methods' => ['query', 'prepare']]);

        // Mock para a classe CategoriaRepositorio
        $this->categoriaRepositorio = new CategoriaRepositorio($this->pdoMock);
    });

    describe('->listarCategorias()', function () {
        it('deve retornar uma lista de categorias', function () {
            // Dados simulados
            $categoriasMock = [
                ['id' => 1, 'nome' => 'Categoria 1'],
                ['id' => 2, 'nome' => 'Categoria 2'],
            ];

            // Mock para o método query do PDO
            $stmtMock = Double::instance(['implements' => PDOStatement::class]);
            allow($this->pdoMock)->toReceive('query')->andReturn($stmtMock);
            allow($stmtMock)->toReceive('fetchAll')->andReturn($categoriasMock);

            // Chamada do método
            $categorias = $this->categoriaRepositorio->listarCategorias();

            // Verificações
            expect($categorias)->toBe($categoriasMock);
        });
    });

    describe('->buscarCategoriaPorId()', function () {
        it('deve retornar a categoria quando o ID existir', function () {
            // Dados simulados
            $categoriaMock = ['id' => 1, 'nome' => 'Categoria Teste'];

            // Mock para o método prepare e execução
            $stmtMock = Double::instance(['implements' => PDOStatement::class]);
            allow($this->pdoMock)->toReceive('prepare')->andReturn($stmtMock);
            allow($stmtMock)->toReceive('execute')->with(['id' => 1]);
            allow($stmtMock)->toReceive('fetch')->andReturn($categoriaMock);

            // Chamada do método
            $categoria = $this->categoriaRepositorio->buscarCategoriaPorId(1);

            // Verificações
            expect($categoria)->toBe($categoriaMock);
        });

        it('deve retornar NULL quando o ID não existir', function () {
            // Mock para o método prepare e execução
            $stmtMock = Double::instance(['implements' => PDOStatement::class]);
            allow($this->pdoMock)->toReceive('prepare')->andReturn($stmtMock);
            allow($stmtMock)->toReceive('execute')->with(['id' => 999]);
            allow($stmtMock)->toReceive('fetch')->andReturn(null);

            // Chamada do método
            $categoria = $this->categoriaRepositorio->buscarCategoriaPorId(999);

            // Verificações
            expect($categoria)->toBe(null);
        });
    });
});
