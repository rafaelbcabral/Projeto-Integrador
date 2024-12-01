<?php
require_once 'src/infra/DominioException.php';
class Reserva
{
    //Constantes para validações
    const STATUS_VALIDOS = ['ativo', 'inativo'];
    const MESAS_MAX = 10; // Número máximo de mesas
    const HORARIO_INICIO = '11:00'; // Horário de início das reservas
    const HORARIO_FIM = '20:00'; // Horário de fim das reservas
    const DURACAO_RESERVA = 2; // Duração da reserva em horas

    //Variaveis
    public $id;
    public $nomeCliente;
    public $mesa;
    public $data;
    public $horaInicial;
    public $horaTermino;
    public $funcionario;
    public $status;

    public function __construct($id = 0, $nomeCliente, $mesa, $data, $horaInicial, $horaTermino, $funcionario, $status = 'ativo')
    {
        $this->id = $id;
        $this->nomeCliente = $nomeCliente;
        $this->mesa = $mesa;
        $this->data = $data;
        $this->horaInicial = $horaInicial;
        $this->horaTermino = $horaTermino;
        $this->funcionario = $funcionario;
        $this->status = $status;
    }

    /**
     * Valida os dados da reserva
     *
     * @return array<int, string>
     */
    public function validar(): array
    {
        $problemas = [];

        // Validação do ID
        if (!is_numeric($this->id) || $this->id < 0) {
            $problemas[] = 'O ID deve ser um número não negativo.';
        }

        // Validação da mesa (Número máximo de mesas)
        if ($this->mesa < 1 || $this->mesa > self::MESAS_MAX) {
            $problemas[] = 'O número da mesa deve ser entre 1 e ' . self::MESAS_MAX . '.';
        }

        // Validação da data e horário
        $dataReserva = DateTime::createFromFormat('Y-m-d H:i', $this->data . ' ' . $this->horaInicial);
        if (!$dataReserva) {
            $problemas[] = 'Data ou hora de início inválidos.';
            return $problemas; // Retorna imediatamente se a data ou hora for inválida
        }

        // Verificar se a data está dentro do intervalo permitido para reservas
        $diaSemana = $dataReserva->format('N'); // Retorna o dia da semana: 1 (segunda) até 7 (domingo)
        $horaInicial = DateTime::createFromFormat('H:i', $this->horaInicial);
        $horaTermino = DateTime::createFromFormat('H:i', $this->horaTermino);

        // Validar horário de funcionamento e dias permitidos para reserva
        if (($diaSemana < 4 && $horaInicial < DateTime::createFromFormat('H:i', self::HORARIO_INICIO)) ||
            ($horaTermino > DateTime::createFromFormat('H:i', self::HORARIO_FIM))
        ) {
            $problemas[] = 'A reserva deve ser feita entre as 11:00 e as 20:00.';
            return $problemas; // Retorna imediatamente se o horário for inválido
        }

        // Validar se a reserva está acontecendo nos dias permitidos (quinta a domingo)
        if ($diaSemana < 4) {
            $problemas[] = 'A reserva só pode ser feita entre quinta e domingo.';
            return $problemas; // Retorna imediatamente se a reserva for em um dia inválido
        }

        // Validar a duração da reserva
        if ($horaTermino->diff($horaInicial)->h != self::DURACAO_RESERVA) {
            $problemas[] = 'A reserva deve ter duração de 2 horas.';
        }

        // Validação do status
        if (!in_array($this->status, self::STATUS_VALIDOS)) {
            $problemas[] = 'O status deve ser "ativo" ou "inativo".';
        }

        // Validar funcionário (ID do funcionário)
        if (!is_numeric($this->funcionario) || $this->funcionario < 0) {
            $problemas[] = 'O ID do funcionário deve ser um número não negativo.';
        }

        return $problemas;
    }
}
