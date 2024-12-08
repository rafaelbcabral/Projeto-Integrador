export class CriarReserva {
  nomeCliente: string;
  mesaId: number;
  data: string;
  horarioInicial: string;
  funcionarioId: number;

  constructor(
    nomeCliente: string,
    mesaId: number,
    data: string,
    horarioInicial: string,
    funcionarioId: number
  ) {
    this.nomeCliente = nomeCliente;
    this.mesaId = mesaId;
    this.data = data;
    this.horarioInicial = horarioInicial;
    this.funcionarioId = funcionarioId;
  }
}
