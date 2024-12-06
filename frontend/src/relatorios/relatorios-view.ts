import { Relatorio } from './relatorios-model.js';

export class RelatoriosView {
  private grafico: HTMLElement;

  constructor() {
    this.grafico = document.getElementById('grafico')!;
  }

  exibirRelatorio(relatorio: Relatorio[]): void {
    // Exibe o gráfico (exemplo básico, pode ser integrado com uma biblioteca de gráficos)
    const dados = relatorio.map(r => ({ x: r.data, y: r.qtdReservas }));
    // Aqui você pode gerar um gráfico com uma biblioteca como Chart.js
  }

  mostrarMensagemErro(mensagem: string) {
    alert(mensagem);
  }
}
