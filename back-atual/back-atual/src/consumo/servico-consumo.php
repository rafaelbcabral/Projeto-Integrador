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
                    $data->valorTotalPorItem // VALOR TOTAL POR ITEM
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


    // public function adicionarConsumo($data)
    // {
    //     // Validação da reserva, funcionario e itens
    //     if (empty($data->reserva) || empty($data->funcionario) || empty($data->itens)) {
    //         throw new Exception("Dados incompletos");
    //     }

    //     // Inicializando o total de itens e o array de itens consumidos
    //     $totalDeItens = 0;
    //     $itensConsumidos = [];

    //     // Somando as quantidades e montando o array de itens
    //     foreach ($data->itens as $item) {
    //         $totalDeItens += $item->quantidade;
    //         $itensConsumidos[] = [
    //             'id' => $item->id,
    //             'quantidade' => $item->quantidade
    //         ];
    //     }

    //     // Criando o consumo com os itens em formato JSON
    //     $consumo = new Consumo(0, $data->reserva, $data->funcionario, json_encode($itensConsumidos), $totalDeItens);

    //     // Inserir o consumo no banco de dados
    //     $this->consumoRepo->adicionarConsumo($consumo);
    // }
}
