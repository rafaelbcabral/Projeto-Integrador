import { Chart, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend } from 'chart.js';

// registrando os componentes
Chart.register(CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend);

// Tipo para representar uma reserva
type Reserva = {
  data: string;
};

function gerarDatasDoIntervalo(dataInicio: Date, dataFim: Date): string[] {
  const datas: string[] = [];
  let dataAtual = new Date(dataInicio);
  while (dataAtual <= dataFim) { 
    const dataFormatada = formatarData(dataAtual);
    datas.push(dataFormatada);
    dataAtual.setDate(dataAtual.getDate() + 1); // incrementando
  }
  return datas;
}

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

  const primeiroDia = new Date(ano, mes, 1); // primeiro dia do mes
  const ultimoDia = new Date(ano, mes + 1, 0); // (0-11) e zero no date é o ultimo dia do mes anterior

  return { primeiroDia, ultimoDia };
}

function criarGrafico(
  ctx: CanvasRenderingContext2D,
  labels: string[],
  dados: number[]
): Chart {
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
          max: 50 // Limite
        }
      }
    }
  });
}


function formatarDataParaGrafico(data: string): string { 
  const [, mes, dia] = data.split('-'); // quebrar data
  return `${dia}/${mes}`; 
}

// processando e atualizando..
const processarReservas = (reservas: Reserva[], labelsX: string[]): void => {
  const valores: number[] = Array(labelsX.length).fill(0); // resetando todos os valores 

  reservas.forEach((reserva) => {
    const dataReserva = reserva.data; //  'ANO-MES-DIA'

    const dataFormatada = formatarDataParaGrafico(dataReserva); 

    const index = labelsX.indexOf(dataFormatada); // retorna 1 se sim e -1 se nao
    if (index !== -1) {
      valores[index] += 1; // + reservas para o loop
    }
  });

  if (grafico1) {
    grafico1.data.labels = labelsX;
    grafico1.data.datasets[0].data = valores;
    grafico1.update(); // Atualizar
  }
};

const obterDados = (dataInicio: Date, dataFim: Date): void => {
  const endpoint = "http://localhost:8000/reservas";

  fetch(endpoint)
    .then((res) => res.json())
    .then((data: Reserva[]) => {
      if (!Array.isArray(data)) { // Verificando se a resposta é um array de objetos, se n for, die
        console.error("Formato de resposta incorreto. Esperado um array de objetos.");
        return;
      }

      const reservasNoPeriodo = data.filter((reserva) => {
        const dataReserva = new Date(reserva.data); // Facilitar a comp com as datas dataInicio e dataFim
        return dataReserva >= dataInicio && dataReserva <= dataFim;
      });

      const labelsX = gerarDatasDoIntervalo(dataInicio, dataFim).map((data) =>
        formatarDataParaGrafico(data)
      ); // Eixo X gerado

      processarReservas(reservasNoPeriodo, labelsX);
    })
    .catch((erro) => {
      console.error("Erro ao obter dados: " + erro);
    });
};

// Obtendo o contexto do canvas
const ctx = (document.getElementById('myChart') as HTMLCanvasElement).getContext('2d')!;


let grafico1: Chart; // garantir que exista para atualizar os dados do gráfico, destruir o gráfico, etc


const { primeiroDia, ultimoDia } = obterDatasMesAtual();

grafico1 = criarGrafico(ctx, [], []); // Inicializando vazio

obterDados(primeiroDia, ultimoDia);

// Adicionar evento para gerar o relatório no intervalo selecionado
const form = document.getElementById('relatorioForm');
if (form) {
  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const dataInicio = new Date((document.getElementById('dataInicio') as HTMLInputElement).value);
    const dataFim = new Date((document.getElementById('dataFim') as HTMLInputElement).value);

    if (grafico1) {
      grafico1.destroy();
    }

    grafico1 = criarGrafico(ctx, [], []); // Recriar o grapico
    obterDados(dataInicio, dataFim); // Obter dados e atualizar o grafico
  });
} else {
  console.error("Elemento 'relatorioForm' não encontrado.");
}

