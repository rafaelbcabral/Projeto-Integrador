<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;
use Kahlan\Plugin\Double;

describe('ControladoraCategoria', function () {
    beforeAll(function () {
        // Criando mocks para o ServicoCategoria, HttpRequest e HttpResponse
        $this->servicoMock = Double::instance(['class' => ServicoCategoria::class]);

        // Criando mock para HttpRequest e HttpResponse
        $this->reqMock = Double::instance(['class' => HttpRequest::class]);
        $this->resMock = Double::instance(['class' => HttpResponse::class]);

        // Criando instância da ControladoraCategoria passando o mock do serviço
        $this->controladora = new ControladoraCategoria($this->servicoMock);
    });

    describe('->listarCategorias()', function () {
        it('deve retornar uma lista de categorias em formato JSON', function () {
            $categoriasMock = [
                ['id' => 1, 'nome' => 'Categoria 1'],
                ['id' => 2, 'nome' => 'Categoria 2']
            ];

            // Configura o mock para retornar a lista de categorias
            allow($this->servicoMock)->toReceive('listarCategorias')->andReturn($categoriasMock);

            // Espera-se que o método json seja chamado na resposta com o conteúdo correto
            allow($this->resMock)->toReceive('json')->with($categoriasMock);

            // Chama o método da controladora e verifica o comportamento
            $this->controladora->listarCategorias($this->reqMock, $this->resMock);

            // Verifica se o método json foi chamado com os dados corretos
            expect($this->resMock)->toHaveReceived('json')->with($categoriasMock);
        });
    });

    describe('->buscarCategoriaPorId()', function () {
        it('deve retornar uma categoria válida quando o ID existir', function () {
            $categoriaMock = ['id' => 1, 'nome' => 'Categoria 1'];

            // Configura o mock para buscarCategoriaPorId
            allow($this->servicoMock)->toReceive('buscarCategoriaPorId')->with(1)->andReturn($categoriaMock);

            // Espera-se que o método json seja chamado na resposta com a categoria
            allow($this->resMock)->toReceive('json')->with($categoriaMock);

            // Simula o parâmetro "id" da requisição
            allow($this->reqMock)->toReceive('param')->with('id')->andReturn(1);

            // Chama o método da controladora e verifica o comportamento
            $this->controladora->buscarCategoriaPorId($this->reqMock, $this->resMock);

            // Verifica se o método json foi chamado com os dados corretos
            expect($this->resMock)->toHaveReceived('json')->with($categoriaMock);
        });

        it('deve retornar erro quando a categoria não for encontrada', function () {
            // Configura o mock para lançar exceção
            allow($this->servicoMock)->toReceive('buscarCategoriaPorId')->with(999)->andThrow(new NaoEncontradoException('Categoria com ID 999 nao encontrada.'));

            // Espera-se que o método json seja chamado na resposta com o erro
            $erroMock = ['erro' => 'Categoria com ID 999 nao encontrada.'];
            allow($this->resMock)->toReceive('json')->with($erroMock, 404);

            // Simula o parâmetro "id" da requisição
            allow($this->reqMock)->toReceive('param')->with('id')->andReturn(999);

            // Chama o método da controladora e verifica o comportamento
            $this->controladora->buscarCategoriaPorId($this->reqMock, $this->resMock);

            // Verifica se o método json foi chamado com o erro
            expect($this->resMock)->toHaveReceived('json')->with($erroMock, 404);
        });
    });
});
