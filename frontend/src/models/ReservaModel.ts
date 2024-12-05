export interface Reserva {
  id: number;
  nomeFuncionario: string;
  nomeCliente: string;
  dataHora: string;
  mesa: number;
  status: 'confirmada' | 'cancelada';
}

export class ReservaModel {
  private reservas: Reserva[] = [];

  addReserva(reserva: Reserva) {
    this.reservas.push(reserva);
  }

  getReservas() {
    return this.reservas;
  }

  cancelarReserva(id: number) {
    const reserva = this.reservas.find(r => r.id === id);
    if (reserva) {
      reserva.status = 'cancelada';
    }
  }

  // Funcionalidade adicional para buscar reservas por data
  getReservasPorData(data: string) {
    return this.reservas.filter(r => r.dataHora.startsWith(data));
  }
}
