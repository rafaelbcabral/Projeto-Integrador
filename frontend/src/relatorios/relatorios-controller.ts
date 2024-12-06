import { gerarRelatorioReservas } from './relatorios-gestor.js';
import { RelatoriosView } from './relatorios-view.js';

export class RelatoriosController {
  private view: RelatoriosView;

  constructor(view: RelatoriosView) {
    this.view = view;
  }

  async gerarRelatorio(inicio: string, fim: string) {
    try {
      const relatorio = await gerarRelatorioReservas(inicio, fim);
      this.view.exibirRelatorio(relatorio);
    } catch (erro) {
      this.view.mostrarMensagemErro('Erro ao gerar relatório.');
    }
  }
}
