<?php

class ListarReservaDTO
{
    public int $id;
    public string $nomeCliente;
    public int $mesa;
    public string $data;
    public string $inicio;
    public string $fim;
    public string $nomeFuncionario;
    public string $status;
    public string $statusPagamento;

    public function __construct(
        int $id,
        string $nomeCliente,
        int $mesa,
        string $data,
        string $inicio,
        string $fim,
        string $nomeFuncionario,
        string $status,
        string $statusPagamento
    ) {
        $this->id = $id;
        $this->nomeCliente = $nomeCliente;
        $this->mesa = $mesa;
        $this->data = $data; // Definindo o valor de $data diretamente
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->nomeFuncionario = $nomeFuncionario;
        $this->status = $status;
        $this->statusPagamento = $statusPagamento;
    }
}
