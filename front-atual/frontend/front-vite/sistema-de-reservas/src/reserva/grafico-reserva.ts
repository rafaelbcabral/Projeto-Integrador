import { Chart, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend } from 'chart.js';

Chart.register(CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend);

type ReservaListar = {
  data: string; 
};

function formatarData(data: Date): string {
  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');
  return `${ano}-${mes}-${dia}`;
}

function obterDatasMesAtual(): { primeiroDia: Date; ultimoDia: Date } {
  const hoje = new Date();
  const ano = hoje.getFullYear();
  const mes = hoje.getMonth(); // (0-11)

  const primeiroDia = new Date(ano, mes, 1); // primeiro dia do mês
  const ultimoDia = new Date(ano, mes + 1, 0); // último dia do mês

  return { primeiroDia, ultimoDia };
}

function criarGrafico(ctx: CanvasRenderingContext2D, labels: string[], dados: number[]): Chart {
  return new Chart(ctx, {
    type: 'bar',
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

function formatarDataParaGrafico(data: string): string {
  const [, mes, dia] = data.split('-'); // Quebra a data no formato 'YYYY-MM-DD'
  return `${dia}/${mes}`; // Retorna a data no formato 'DD/MM'
}

// Processando e atualizando o gráfico
const processarReservas = (reservas: ReservaListar[], labelsX: string[]): void => {
  const valores: number[] = Array(labelsX.length).fill(0); // Reseta os valores

  reservas.forEach((reserva) => {
    const dataReserva = reserva.data; // 'YYYY-MM-DD'

    const dataFormatada = formatarDataParaGrafico(dataReserva);

    const index = labelsX.indexOf(dataFormatada); // Retorna -1 se não encontrar a data
    if (index !== -1) {
      valores[index] += 1; // Conta as reservas para o gráfico
    }
  });

  if (grafico1) {
    grafico1.data.labels = labelsX;
    grafico1.data.datasets[0].data = valores;
    grafico1.update(); // Atualiza o gráfico
  }
};

const obterDados = async (dataInicio: Date, dataFim: Date): Promise<void> => {
  const dataInicial = formatarData(dataInicio); // Formata a data de início
  const dataFinal = formatarData(dataFim); // Formata a data de fim

  const endpoint = `http://localhost:8000/periodo?dataInicial=${dataInicial}&dataFinal=${dataFinal}`; // Endpoint com parâmetros de data

  try {
    const res = await fetch(endpoint);
    const data: ReservaListar[] = await res.json(); // Espera pela conversão para JSON

    if (!Array.isArray(data)) { // Verificando se a resposta é um array de objetos
      console.error("Formato de resposta incorreto. Esperado um array de objetos.");
      return;
    }

    // Gerando datas para o grafico
    const labelsX = Array.from(new Set(data.map((reserva) => formatarDataParaGrafico(reserva.data)))); // Garante que != datas duplicadas

    processarReservas(data, labelsX);
  } catch (erro) {
    console.error("Erro ao obter dados: " + erro);
  }
};


const ctx = (document.getElementById('myChart') as HTMLCanvasElement).getContext('2d')!;

let grafico1: Chart; // Variável para garantir que o gráfico seja atualizado

const { primeiroDia, ultimoDia } = obterDatasMesAtual();

grafico1 = criarGrafico(ctx, [], []); 

obterDados(primeiroDia, ultimoDia); 


const form = document.getElementById('relatorioForm');
if (form) {
  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const dataInicioInput = (document.getElementById('dataInicio') as HTMLInputElement).value;
    const dataInicio = new Date(`${dataInicioInput}T00:00:00`); // Força a hora correta

    const dataFimInput = (document.getElementById('dataFim') as HTMLInputElement).value;
    const dataFim = new Date(`${dataFimInput}T23:59:59`); // Força a hora correta

    if (grafico1) { // Recria o gráfico ao mudar o intervalo
      grafico1.destroy();
    }

    grafico1 = criarGrafico(ctx, [], []);
    obterDados(dataInicio, dataFim); 
  });
} else {
  console.error("Elemento 'relatorioForm' não encontrado.");
}
