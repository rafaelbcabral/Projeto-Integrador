<?php


class ServicoMesa
{
    protected MesaRepositorio $mesaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->mesaRepo = new MesaRepositorio($pdo);
    }

    // Método para listar todas as mesas
    public function listarMesas()
    {
        return $this->mesaRepo->listarMesas();
    }

    // Método para listar as mesas disponíveis com base na data e horário
    public function listarMesasDisponiveis(array $dados)
    {
        // Validações
        if (empty($dados['data'])) {
            throw new \InvalidArgumentException('O parâmetro "data" é obrigatório.');
        }

        if (empty($dados['horarioInicial'])) {
            throw new \InvalidArgumentException('O parâmetro "horarioInicial" é obrigatório.');
        }

        // Dados validados, chama o repositório
        return $this->mesaRepo->listarMesasDisponiveis($dados['data'], $dados['horarioInicial']);
    }
}
