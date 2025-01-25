<?php

require_once 'src/categoria/repositorio-categoria.php';

use Kahlan\Plugin\Double;

describe('ServicoCategoria', function () {
    beforeAll(function () {
        // Cria um mock para o PDOStatement
        $this->stmtMock = Double::instance(['class' => PDOStatement::class]);

        // Cria um mock para o PDO
        $this->pdoMock = Double::instance(['methods' => ['prepare', 'query']]);
        allow($this->pdoMock)->toReceive('prepare')->andReturn($this->stmtMock);
        allow($this->pdoMock)->toReceive('query')->andReturn($this->stmtMock);

        // Cria um mock para o CategoriaRepositorio
        // Agora passamos o argumento necessário para o construtor
        $this->categoriaRepoMock = Double::instance([
            'extends' => CategoriaRepositorio::class,
            'constructor' => [$this->pdoMock] // Aqui passamos o argumento esperado
        ]);

        // Inicializa a classe ServicoCategoria
        $this->servicoCategoria = new ServicoCategoria($this->pdoMock);

        // Substitui o repositório por um mock
        $this->servicoCategoria->categoriaRepo = $this->categoriaRepoMock;
    });

    describe('->listarCategorias()', function () {
        it('deve retornar uma lista de categorias', function () {
            $categoriasMock = [
                ['id' => 1, 'nome' => 'Categoria 1'],
                ['id' => 2, 'nome' => 'Categoria 2']
            ];

            // Configura o mock para listarCategorias
            allow($this->categoriaRepoMock)->toReceive('listarCategorias')->andReturn($categoriasMock);

            // Chama o método e verifica o retorno
            $categorias = $this->servicoCategoria->listarCategorias();
            expect($categorias)->toBe($categoriasMock);
        });
    });

    describe('->buscarCategoriaPorId()', function () {
        it('deve retornar uma categoria válida quando o ID existir', function () {
            $categoriaMock = ['id' => 1, 'nome' => 'Categoria 1'];

            // Configura o mock para buscarCategoriaPorId
            allow($this->categoriaRepoMock)->toReceive('buscarCategoriaPorId')->with(1)->andReturn($categoriaMock);

            // Chama o método e verifica o retorno
            $categoria = $this->servicoCategoria->buscarCategoriaPorId(1);
            expect($categoria)->toBeAnInstanceOf(Categoria::class);
            expect($categoria->id)->toBe(1);
            expect($categoria->nome)->toBe('Categoria 1');
        });

        it('deve lançar uma exceção quando a categoria não for encontrada', function () {
            // Configura o mock para retornar null
            allow($this->categoriaRepoMock)->toReceive('buscarCategoriaPorId')->with(999)->andReturn(null);

            // Verifica que a exceção é lançada
            expect(function () {
                $this->servicoCategoria->buscarCategoriaPorId(999);
            })->toThrow(new NaoEncontradoException('Categoria com ID 999 nao encontrada.'));
        });
    });
});
