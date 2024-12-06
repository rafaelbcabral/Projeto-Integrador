import { criarReserva, cancelarReserva } from './reserva-gestor.js';
import { ReservaView } from './reserva-view.js';

export class ReservaController {
  private view: ReservaView;

  constructor(view: ReservaView) {
    this.view = view;
  }

  async realizarReserva(reserva: any) {
    try {
      const novaReserva = await criarReserva(reserva);
      this.view.exibirReserva(novaReserva);
    } catch (erro) {
      this.view.mostrarMensagemErro('Erro ao realizar a reserva.');
    }
  }

  async cancelarReserva(id: number) {
    try {
      await cancelarReserva(id);
      this.view.atualizarListaReservas();
    } catch (erro) {
      this.view.mostrarMensagemErro('Erro ao cancelar a reserva.');
    }
  }
}
