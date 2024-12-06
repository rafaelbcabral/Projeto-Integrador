import { buscarMesasDisponiveis } from './mesa-gestor.js';
import { MesaView } from './mesa-view.js';

export class MesaController {
  private view: MesaView;

  constructor(view: MesaView) {
    this.view = view;
  }

  async carregarMesasDisponiveis(data: string, horario: string) {
    try {
      const mesas = await buscarMesasDisponiveis(data, horario);
      this.view.exibirMesas(mesas);
    } catch (erro) {
      this.view.mostrarMensagemErro('Erro ao carregar mesas disponíveis.');
    }
  }
}
