export class CriarReserva {
  nomeCliente: string;
  mesaId: number;
  data: string;
  inicio: string;
  funcionarioId: number;
  telefoneCliente: number;

  constructor(
    nomeCliente: string,
    mesaId: number,
    data: string,
    inicio: string,
    funcionarioId: number,
    telefoneCliente: number,
  ) {
    this.nomeCliente = nomeCliente;
    this.mesaId = mesaId;
    this.data = data;
    this.inicio = inicio;
    this.funcionarioId = funcionarioId;
    this.telefoneCliente = telefoneCliente;
  }
}
