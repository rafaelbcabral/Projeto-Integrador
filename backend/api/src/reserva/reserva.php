<?php
require_once 'src/infra/DominioException.php';

class Reserva
{
    const MESAS_MAX = 10; // Número máximo de mesas
    const HORARIO_INICIO = '11:00:00'; // Horário de início das reservas
    const HORARIO_FIM = '20:00:00'; // Horário de fim das reservas
    const DURACAO_RESERVA = 2; // Duração da reserva em horas

    //Variaveis
    public int $id;
    public string $nomeCliente;
    public int $mesa;
    public  $data;
    public  $horaInicial;
    public $horaTermino;
    public int $funcionario;
    public  $status;

    public function __construct($id = 0, $nomeCliente, $mesa, $data, $horaInicial, $funcionario)
    {
        $this->id = $id ?? 0;
        $this->nomeCliente = $nomeCliente;
        $this->mesa = $mesa;
        $this->data = DateTime::createFromFormat('Y-m-d', $data)->format('Y-m-d'); // Somente data
        $this->horaInicial = DateTime::createFromFormat('H:i:s', $horaInicial)->format('H:i:s'); // Somente hora
        $this->horaTermino = DateTime::createFromFormat('H:i:s', $horaInicial)->modify('+2 hours')->format('H:i:s'); // Adiciona 2 horas e formata;
        $this->funcionario = $funcionario;
    }

    /**
     * Valida os dados da reserva
     *
     * @return array<int, string>
     */
    public function validar(): array
    {
        $problemas = [];

        // Validação da mesa (Número máximo de mesas)
        if ($this->mesa < 1 || $this->mesa > self::MESAS_MAX) {
            $problemas[] = 'O número da mesa deve ser entre 1 e ' . self::MESAS_MAX . '.';
        }

        // Validação da data e horário
        $dataHoraInicial = DateTime::createFromFormat('Y-m-d H:i:s', $this->data . ' ' . $this->horaInicial);

        if (!$dataHoraInicial) {
            $problemas[] = 'Data ou hora de início inválidos.';
        } else {
            // Verificar se a data está dentro do intervalo permitido para reservas
            $diaSemana = $dataHoraInicial->format('N'); // Retorna o dia da semana: 1 (segunda) até 7 (domingo)
            $horaInicial = DateTime::createFromFormat('H:i:s', $this->horaInicial);
            $horaTermino = DateTime::createFromFormat('H:i:s', $this->horaTermino);

            // Criar DateTime com apenas o horário para as comparações
            $horaInicioValida = DateTime::createFromFormat('H:i:s', self::HORARIO_INICIO);
            $horaFimValida = DateTime::createFromFormat('H:i:s', self::HORARIO_FIM);

            // Validar horário de funcionamento e dias permitidos para reserva
            if (($diaSemana < 4 && $horaInicial < $horaInicioValida) || $horaTermino > $horaFimValida) {
                $problemas[] = 'A reserva deve ser feita entre as 11:00 e as 20:00.';
            }

            // Validar se a reserva está acontecendo nos dias permitidos (quinta a domingo)
            if ($diaSemana < 4) {
                $problemas[] = 'A reserva só pode ser feita entre quinta e domingo.';
            }

            $duracaoReserva = $horaInicial->diff($horaTermino);
            if ($duracaoReserva->h != self::DURACAO_RESERVA) {
                $problemas[] = 'A reserva deve ter duração de 2 horas.';
            }
        }

        // Validar funcionário (ID do funcionário)
        if (!is_numeric($this->funcionario) || $this->funcionario < 0) {
            $problemas[] = 'O ID do funcionário deve ser um número não negativo.';
        }

        return $problemas;
    }
}
