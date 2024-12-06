import { Mesa } from './mesa-model.js';

export class MesaView {
  private selectMesas: HTMLSelectElement;

  constructor() {
    this.selectMesas = document.getElementById('selectMesas') as HTMLSelectElement;
  }

  exibirMesas(mesas: Mesa[]): void {
    this.selectMesas.innerHTML = '';
    mesas.forEach(mesa => {
      const option = document.createElement('option');
      option.value = mesa.id.toString();
      option.textContent = `Mesa ${mesa.id}`;
      this.selectMesas.appendChild(option);
    });
  }

  mostrarMensagemErro(mensagem: string) {
    alert(mensagem);
  }
}
