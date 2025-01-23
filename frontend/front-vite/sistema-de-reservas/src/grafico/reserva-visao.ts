import { Chart } from 'chart.js';
//, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend = caso precise

export class ReservaView {
  private chart: Chart | null = null;

  constructor(private ctx: CanvasRenderingContext2D) {}
  
  criarGrafico(labels: string[], dados: number[]): void {
    console.log('Criando gráfico com os seguintes dados:', { labels, dados }); // Logando os dados do gráfico
  
    if (this.chart) {
      this.chart.destroy(); // Destroi o gráfico antigo
    }
  
    this.ctx.clearRect(0, 0, this.ctx.canvas.width, this.ctx.canvas.height);
  
    this.chart = new Chart(this.ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'Relatório de Reservas',
            data: dados,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: {
            title: { display: true, text: 'Datas' },
          },
          y: {
            title: { display: true, text: 'Quantidade de Reservas' },
            beginAtZero: true,
          },
        },
      },
    });
  }
}

