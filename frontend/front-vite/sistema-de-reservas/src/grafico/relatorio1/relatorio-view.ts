import { Chart, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';

// Registre os componentes necessários do Chart.js
Chart.register(
  CategoryScale,   // Escala de categorias (para o eixo X)
  LinearScale,     // Escala linear (para o eixo Y)
  BarElement,      // Elemento de barra
  Title,           // Título do gráfico
  Tooltip,         // Ferramenta de dicas
  Legend           // Legenda
);

export class GraficoView {
  canvas: HTMLCanvasElement;
  form: HTMLElement | null;
  ctx: CanvasRenderingContext2D;

  constructor() {
    const { canvas, form, ctx } = this.obterDadosHtml(); // Preenche as propriedades com as referências
    this.canvas = canvas;
    this.form = form;
    this.ctx = ctx;
  }

  obterDadosHtml() {
    const canvas = document.getElementById('myChart') as HTMLCanvasElement;
    const form = document.getElementById('relatorioForm');
    const ctx = canvas.getContext('2d')!;

    return { canvas, form, ctx };
  }

  public criarGrafico(labels: string[], dados: number[]): Chart {
    return new Chart(this.ctx, {
      type: 'bar',  // Tipo do gráfico (barra)
      data: {
        labels: labels,
        datasets: [{
          label: 'Relatório de Reservas',
          data: dados,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            title: {
              display: true,
              text: 'Datas'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Quantidade de Reservas'
            },
            beginAtZero: true,
            min: 0,
            max: 10 // Limite para a quantidade de reservas
          }
        }
      }
    });
  }
}
