<?php

require_once 'src/mesa/repositorio-mesa.php';
require_once 'repositorio-reserva.php';

class ServicoReserva
{
    protected ReservaRepositorio $reservaRepo;
    protected MesaRepositorio $mesaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->reservaRepo = new ReservaRepositorio($pdo);
        $this->mesaRepo = new MesaRepositorio($pdo);
    }

    public function criarReserva(array $dados)
    {

        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        $reserva = new Reserva(
            0,
            $dados['nomeCliente'],
            $dados['mesa'],
            $dados['data'],
            $dados['inicio'],
            $dados['funcionario']
        );

        $disponivel = $this->reservaRepo->verificarDisponibilidade($reserva);
        $reservasDia =  $this->reservaRepo->contarReservasDia($reserva->data);

        if ($disponivel) {
            throw new Exception('Mesa não disponível para o horário solicitado');
        }

        if (date('N', strtotime($reserva->data)) > 5) {
            if ($reservasDia >= 10) {
                throw new \Exception('Total de reservas excedido para o final de semana');
            }
        } else if (date('N', strtotime($reserva->data)) == 4 || date('N', strtotime($reserva->data)) == 5) {
            if ($reservasDia >= 7) {
                throw new \Exception('Total de reservas excedido para quinta e sexta-feira');
            }
        }

        $problemas = $reserva->validar();
        if (count($problemas)) {
            throw (new DominioException())->setProblemas($problemas);
        }

        $this->reservaRepo->salvarReserva($reserva);

        $this->mesaRepo->atualizarStatusMesa($dados['mesa'], false);
    }

    public function listarReservas()
    {
        return $this->reservaRepo->listarReservas();
    }

    public function listarReservasPorPeriodo($dataInicial, $dataFinal)
    {
        return $this->reservaRepo->listarReservasPorPeriodo($dataInicial, $dataFinal);
    }

    public function cancelarReserva($id)
    {
        $this->reservaRepo->cancelarReserva($id);
    }
}
