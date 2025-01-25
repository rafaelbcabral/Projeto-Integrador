<?php

require_once 'repositorio-consumo.php';
require_once 'consumo.php';

class ServicoConsumo
{
    protected ConsumoRepositorio $consumoRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->consumoRepo = new ConsumoRepositorio($pdo);
    }


    public function adicionarConsumo($data)
    {
        // Validação da reserva, funcionário e itens
        if (empty($data->reserva) || empty($data->funcionario) || empty($data->itens)) {
            throw new Exception("Dados incompletos");
        }

        // Para cada item na lista de itens, criamos uma instância de Consumo e inserimos no banco
        foreach ($data->itens as $item) {
            // Validando os campos do item
            if (empty($item->id) || empty($item->quantidade)) {
                throw new Exception("Item ou quantidade inválidos");
            }

            // Verificando se o item já foi consumido para essa reserva
            $itemExistente = $this->consumoRepo->verificarConsumoExistente($data->reserva, $item->id);

            if ($itemExistente) {
                // Se o item já existe, apenas atualiza a quantidade
                $novaQuantidade = $itemExistente->quantidade + $item->quantidade;
                $this->consumoRepo->atualizarQuantidadeConsumo($data->reserva, $item->id, $novaQuantidade);
            } else {
                // Caso o item não tenha sido consumido ainda, cria um novo consumo
                $consumo = new Consumo(
                    0,                      // ID do consumo (gerado automaticamente no banco)
                    $data->reserva,         // ID da reserva
                    $item->id,              // ID do item
                    $item->quantidade,      // Quantidade do item
                    $data->funcionario,     // ID do funcionário
                );

                // Inserindo o consumo no banco de dados
                $this->consumoRepo->adicionarConsumo($consumo);
            }
        }
    }

    public function visualizarConsumoParcial($reservaId)
    {
        return $this->consumoRepo->obterResumoConsumoParcial($reservaId);
    }

    public function relatorioVendasPorPeriodoFormaPagamento($dados)
    {
        // Aplicando htmlspecialchars em todos os valores do array $dados
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        // Extrai as variáveis de dados
        $dataInicio = $dados['dataInicio'];
        $dataFim = $dados['dataFim'];
        $formaDePagamento = $dados['formaDePagamento'];

        // Chama o repositório para buscar os dados do relatório
        return $this->consumoRepo->relatorioVendasPorPeriodoFormaPagamento($formaDePagamento, $dataInicio, $dataFim);
    }

    public function relatorioVendasPorPeriodoFuncionario($dados)
    {
        // Aplicando htmlspecialchars em todos os valores do array $dados
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        // Extrai as variáveis de dados
        $dataInicio = $dados['dataInicio'];
        $dataFim = $dados['dataFim'];
        $idFuncionario = $dados['idFuncionario'];

        // Chama o repositório para buscar os dados do relatório
        return $this->consumoRepo->relatorioVendasPorPeriodoFuncionario($idFuncionario, $dataInicio, $dataFim);
    }

    public function relatorioVendasPorPeriodoCategoria($dados)
    {
        // Aplicando htmlspecialchars em todos os valores do array $dados
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        // Extrai as variáveis de dados
        $dataInicio = $dados['dataInicio'];
        $dataFim = $dados['dataFim'];
        $idCategoria = $dados['idCategoria'];

        // Chama o repositório para buscar os dados do relatório
        return $this->consumoRepo->relatorioVendasPorPeriodoCategoria($idCategoria, $dataInicio, $dataFim);
    }

    public function relatorioVendasPorPeriodoDia($dados)
    {
        // Aplicando htmlspecialchars em todos os valores do array $dados
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        // Extrai as variáveis de dados
        $dataInicio = $dados['dataInicio'];
        $dataFim = $dados['dataFim'];

        // Chama o repositório para buscar os dados do relatório
        return $this->consumoRepo->relatorioVendasPorPeriodoDia($dataInicio, $dataFim);
    }
}
