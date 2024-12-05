import { ReservaModel } from '../models/ReservaModel.js';
import { ReservaView } from '../views/ReservaView.js';

export class ReservaController {
  private model: ReservaModel;
  private view: ReservaView;

  constructor(model: ReservaModel, view: ReservaView) {
    this.model = model;
    this.view = view;
    this.view.bindFormReserva(this.handleReserva.bind(this));
    this.view.bindCancelarReserva(this.handleCancelarReserva.bind(this));
    this.updateView();
  }

  handleReserva(reservaData: any) {
    const reserva = {
      ...reservaData,
      id: Date.now(),  // Gerando um ID único temporário
      status: 'confirmada',
    };
    this.model.addReserva(reserva);
    this.updateView();
  }

  handleCancelarReserva(id: number) {
    this.model.cancelarReserva(id);
    this.updateView();
  }

  updateView() {
    const reservas = this.model.getReservas();
    this.view.renderReservas(reservas);
  }
}
