<?php
require_once 'src/infra/dominio-exception.php';

class Reserva
{
    const MESAS_MAX = 10; // Número máximo de mesas
    const HORARIO_INICIO = '11:00:00'; // Horário de início das reservas
    const HORARIO_FIM = '20:00:00'; // Horário de fim das reservas
    const DURACAO_RESERVA = 2; // Duração da reserva em horas
    // Definir constantes para os limites de mesas
    const MESAS_MAX_FINAL_DE_SEMANA = 10; // Limite padrão de mesas
    const MESAS_MAX_DIA_DE_SEMANA = 7; // Limite para quinta e sexta

    //Variaveis
    public int $id;
    public string $nomeCliente;
    public int $mesa;
    public string $data;
    public  string $inicio;
    public string $fim;
    public int $funcionario;
    public string $status;
    public string $statusPagamento;


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param string $nomeCliente
     * @param integer $mesa
     * @param string $data
     * @param string $horaInicial
     * @param integer|null $funcionario
     */
    public function __construct($id, string $nomeCliente, int $mesa, string $data, string $horaInicial, int $funcionario = null)
    {
        $this->id = 0;
        $this->nomeCliente = $nomeCliente;
        $this->mesa = $mesa;
        $this->data = DateTime::createFromFormat('Y-m-d', $data)->format('Y-m-d'); // Somente data
        $this->inicio = DateTime::createFromFormat('H:i:s', $horaInicial)->format('H:i:s'); // Somente hora
        $this->fim = DateTime::createFromFormat('H:i:s', $horaInicial)->modify('+2 hours')->format('H:i:s'); // Adiciona 2 horas e formata;
        $this->funcionario = $funcionario;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function validar(): array
    {
        $problemas = [];

        $dataHoraInicial = DateTime::createFromFormat('Y-m-d H:i:s', $this->data . ' ' . $this->inicio);
        $diaSemana = $dataHoraInicial->format('N');

        $dataAtual = new DateTime();
        // Definindo a hora do $dataAtual para 00:00:00 para comparar apenas as datas
        $dataAtual->setTime(0, 0, 0);

        // Comparando apenas as datas (ignorando a hora)
        if ($dataHoraInicial < $dataAtual) {
            $problemas[] = 'A data e hora da reserva não podem ser anteriores ao dia atual.';
        }

        $mesasMax = in_array($diaSemana, [6, 7]) ? self::MESAS_MAX_FINAL_DE_SEMANA : self::MESAS_MAX_DIA_DE_SEMANA;

        // Validar a mesa
        if ($this->mesa < 1 || $this->mesa > $mesasMax) {
            $problemas[] = 'O número da mesa deve ser entre 1 e ' . $mesasMax . '.';
        }

        // Verificar se a data e hora são válidas
        if (!$dataHoraInicial) {
            $problemas[] = 'Data ou hora de início inválidos.';
        } else {
            $horaInicial = DateTime::createFromFormat('H:i:s', $this->inicio);
            $horaTermino = DateTime::createFromFormat('H:i:s', $this->fim);
            $horaInicioValida = DateTime::createFromFormat('H:i:s', self::HORARIO_INICIO);
            $horaFimValida = DateTime::createFromFormat('H:i:s', self::HORARIO_FIM);

            if ($horaInicial < $horaInicioValida || $horaTermino > $horaFimValida) {
                $problemas[] = 'A reserva deve ser feita entre as 11:00 e as 20:00.';
            }

            if ($diaSemana < 4) {
                $problemas[] = 'A reserva só pode ser feita entre quinta e domingo.';
            }

            $duracaoReserva = $horaInicial->diff($horaTermino);
            if ($duracaoReserva->h != self::DURACAO_RESERVA) {
                $problemas[] = 'A reserva deve ter duração de 2 horas.';
            }
        }

        if (!is_numeric($this->funcionario) || $this->funcionario < 0) {
            $problemas[] = 'O ID do funcionário deve ser um número não negativo.';
        }

        return $problemas;
    }
}
