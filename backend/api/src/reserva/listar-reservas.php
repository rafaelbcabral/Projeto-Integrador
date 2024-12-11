<?php
class ListarReservaDTO
{
    public int $id;
    public string $nomeCliente;
    public int $mesa;
    public string $data;
    public string $horaInicial;
    public string $horaTermino;
    public string $nomeFuncionario;
    public string $status;

    public function __construct(
        int $id,
        string $nomeCliente,
        int $mesa,
        string $data,
        string $horaInicial,
        string $horaTermino,
        string $nomeFuncionario,
        string $status
    ) {
        $this->id = $id;
        $this->nomeCliente = $nomeCliente;
        $this->mesa = $mesa;
        $this->data = $data;
        $this->horaInicial = $horaInicial;
        $this->horaTermino = $horaTermino;
        $this->nomeFuncionario = $nomeFuncionario;
        $this->status = $status;
    }

    public function formatarData(): string
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $this->data);
        return $dateTime ? $dateTime->format('d/m/Y') : $this->data;
    }
}
