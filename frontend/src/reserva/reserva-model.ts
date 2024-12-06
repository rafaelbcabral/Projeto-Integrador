export class Reserva {
  id: number;
  nomeCliente: string;
  mesa: number;
  data: string;
  horario: string;
  funcionario: string;

  constructor(id: number, nomeCliente: string, mesa: number, data: string, horario: string, funcionario: string) {
    this.id = id;
    this.nomeCliente = nomeCliente;
    this.mesa = mesa;
    this.data = data;
    this.horario = horario;
    this.funcionario = funcionario;
  }
}
