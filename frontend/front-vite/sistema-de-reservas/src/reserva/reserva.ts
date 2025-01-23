export class CriarReserva {
  nomeCliente: string;
  mesaId: number;
  data: string;
  horarioInicial: string;
  funcionarioId: number;
  telefone: number;

  constructor(
    nomeCliente: string,
    mesaId: number,
    data: string,
    horarioInicial: string,
    funcionarioId: number,
    telefone: number,
  ) {
    this.nomeCliente = nomeCliente;
    this.mesaId = mesaId;
    this.data = data;
    this.horarioInicial = horarioInicial;
    this.funcionarioId = funcionarioId;
    this.telefone = telefone;
  }
}
