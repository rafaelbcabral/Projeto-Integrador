import { Reserva } from '../models/ReservaModel.js';
import { enviarReserva } from '../utills/apiUtils.js';

export class ReservaGestor {
  static async criarReserva(reserva: Reserva) {
    return enviarReserva(reserva);
  }
}
