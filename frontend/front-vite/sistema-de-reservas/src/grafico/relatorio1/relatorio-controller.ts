import { GraficoView } from "./relatorio-view";
import { GestorReservas } from "./gestor-relatorio";
import { Chart } from "chart.js";

export class GraficoController {
  grafico1: Chart | null = null; 

  public processarReservas(reservas: any[], labelsX: string[]): void {
    const valores: number[] = Array(labelsX.length).fill(0); // Reseta os valores

    reservas.forEach((reserva) => {
      const dataReserva = reserva.data; // 'YYYY-MM-DD'
      const dataFormatada = gestorReservas.formatarDataParaGrafico(dataReserva);
      const index = labelsX.indexOf(dataFormatada); // Retorna -1 se não encontrar a data
      if (index !== -1) {
        valores[index] += 1; // Conta as reservas para o gráfico
      }
    });

    if (this.grafico1) {
      this.grafico1.data.labels = labelsX;
      this.grafico1.data.datasets[0].data = valores;
      this.grafico1.update(); // Atualiza o gráfico
    }
  }

  public atualizarGraficoPorIntervalo(form: HTMLFormElement): void {
    if (form) {
      form.addEventListener('submit', (event) => {
        event.preventDefault();

        const dataInicioInput = (document.getElementById('dataInicio') as HTMLInputElement).value;
        const dataInicio = new Date(`${dataInicioInput}T00:00:00`); // Força a hora correta

        const dataFimInput = (document.getElementById('dataFim') as HTMLInputElement).value;
        const dataFim = new Date(`${dataFimInput}T23:59:59`); // Força a hora correta

        if (this.grafico1) { // Recria o gráfico ao mudar o intervalo
          this.grafico1.destroy();
        }

        const graficoView = new GraficoView(); // Passa a instância de GraficoController
        this.grafico1 = graficoView.criarGrafico([], []); // Cria novo gráfico
        gestorReservas.obterDados(dataInicio, dataFim); // Atualiza dados
      });
    } else {
      console.error("Elemento 'relatorioForm' não encontrado.");
    }
  }
}

// Instancia o GraficoController fora de qualquer dependência circular
const gestorReservas = new GestorReservas();
const graficoController = new GraficoController();
