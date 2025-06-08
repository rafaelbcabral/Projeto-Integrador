import { Chart, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend } from 'chart.js';
import { showToast } from '../infra/toastify';

Chart.register(CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend);

type VendaDia = {
  dia: string;
  totalVendido: string; // Alterado para string, pois a API retorna como string
};

type RespostaAPI = {
  vendasPorDia: VendaDia[];
};

function formatarData(data: Date): string {
  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');
  return `${ano}-${mes}-${dia}`;
}

function obterDadosRelatorioDia(dataInicio: Date, dataFim: Date): Promise<RespostaAPI> {
  const dataInicial = formatarData(dataInicio);
  const dataFinal = formatarData(dataFim);

  const endpoint = `http://localhost:8000/relatorio/dia?dataInicio=${dataInicial}&dataFim=${dataFinal}`;

  return fetch(endpoint)
    .then(res => res.json())
    .then(data => data)
    .catch((erro) => {
      console.error("Erro ao obter dados: " + erro);
      showToast("Erro ao obter dados. Tente novamente mais tarde.", "erro");
      throw erro;
    });
}

function criarGraficoColunas(ctx: CanvasRenderingContext2D, labels: string[], dados: number[], total: number): Chart {
  return new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels.length ? labels : ['Nenhum dado'],
      datasets: [{
        label: 'Vendas por Dia',
        data: dados.length ? dados : [0],
        backgroundColor: '#4BC0C0',
        borderColor: '#1D9A9A',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: (tooltipItem) => {
              const totalVendido = Number(tooltipItem.raw);
              return `${tooltipItem.label}: R$ ${totalVendido}`;
            }
          }
        },
        title: {
          display: true,
          text: `Total Vendido no Período: R$ ${total}`
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Dia'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Valor Vendido (R$)'
          },
          beginAtZero: true
        }
      }
    }
  });
}

function processarVendasPorDia(vendas: VendaDia[]): { labels: string[], valores: number[], totalVendido: number } {
  const labels: string[] = vendas.map(venda => venda.dia);
  const valores: number[] = vendas.map(venda => parseFloat(venda.totalVendido));
  const totalVendido = valores.reduce((acc, valor) => acc + valor, 0);
  return {
    labels,
    valores,
    totalVendido,
  };
}

const obterDados = async (dataInicio: Date, dataFim: Date): Promise<void> => {
  try {
    const { vendasPorDia }: RespostaAPI = await obterDadosRelatorioDia(dataInicio, dataFim);

    if (vendasPorDia.length === 0) {
      showToast("Nenhuma venda encontrada para o período selecionado.", "erro");
    }

    const { labels, valores, totalVendido } = processarVendasPorDia(vendasPorDia);

    // Criando gráfico de colunas com as vendas
    const ctx = (document.getElementById('myBarChart') as HTMLCanvasElement).getContext('2d')!;
    if (graficoColunas) {
      graficoColunas.destroy();
    }

    graficoColunas = criarGraficoColunas(ctx, labels, valores, totalVendido);

  } catch (erro) {
    console.error("Erro ao obter dados: ", erro);
  }
};

let graficoColunas: Chart;

const form = document.getElementById('relatorioForm');
if (form) {
  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const dataInicioInput = (document.getElementById('dataInicio') as HTMLInputElement).value;
    const dataInicio = new Date(`${dataInicioInput}T00:00:00`); // Força a hora correta

    const dataFimInput = (document.getElementById('dataFim') as HTMLInputElement).value;
    const dataFim = new Date(`${dataFimInput}T23:59:59`); // Força a hora correta

    obterDados(dataInicio, dataFim);
  });
} else {
  console.error("Elemento 'relatorioForm' não encontrado.");
}