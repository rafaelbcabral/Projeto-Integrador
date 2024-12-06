import { Reserva } from './reserva-model.js';

export class ReservaView {
  private listaReservas: HTMLTableElement;

  constructor() {
    this.listaReservas = document.getElementById('listaReservas') as HTMLTableElement;
  }

  exibirReserva(reserva: Reserva): void {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${reserva.nomeCliente}</td>
      <td>${reserva.mesa}</td>
      <td>${reserva.data}</td>
      <td>${reserva.horario}</td>
      <td>${reserva.funcionario}</td>
      <td><button onclick="cancelarReserva(${reserva.id})">Cancelar</button></td>
    `;
    this.listaReservas.appendChild(tr);
  }

  atualizarListaReservas() {
    // Atualizar a lista de reservas, chamando o back-end para pegar as novas reservas
  }

  mostrarMensagemErro(mensagem: string) {
    alert(mensagem);
  }
}
