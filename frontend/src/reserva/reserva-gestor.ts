import { fazerRequisicao } from '../utills/api-utils.js';
import { Reserva } from './reserva-model.js';

export async function criarReserva(reserva: Reserva): Promise<Reserva> {
  const resposta = await fazerRequisicao('/reservas', 'POST', reserva);
  return new Reserva(reserva.id, reserva.nomeCliente, reserva.mesa, reserva.data, reserva.horario, reserva.funcionario);
}

export async function cancelarReserva(id: number): Promise<void> {
  await fazerRequisicao(`/reservas/${id}`, 'DELETE');
}
