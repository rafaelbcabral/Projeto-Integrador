import { Chart, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend, PieController, ArcElement } from 'chart.js';
import { showToast } from '../infra/toastify';

Chart.register(CategoryScale, LinearScale, BarElement, BarController, PieController, ArcElement, Title, Tooltip, Legend);

type VendaFuncionario = {
  nomeFuncionario: string;
  totalVendido: number;
  percentual: number;
};

type RespostaAPI = {
  totalVendidoPeriodo: number;
  vendasPorFuncionario: VendaFuncionario[];
};

function formatarData(data: Date): string {
  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');
  return `${ano}-${mes}-${dia}`;
}

function obterDadosRelatorioFuncionario(dataInicio: Date, dataFim: Date, idFuncionario: string): Promise<RespostaAPI> {
  const dataInicial = formatarData(dataInicio); // Formata a data de início
  const dataFinal = formatarData(dataFim); // Formata a data de fim

  const endpoint = `http://localhost:8000/relatorio/funcionario?idFuncionario=${idFuncionario}&dataInicio=${dataInicial}&dataFim=${dataFinal}`;
  

  return fetch(endpoint, {credentials: 'include'})
    .then(res => res.json())
    .then(data => data)
    .catch((erro) => {
      console.error("Erro ao obter dados: " + erro);
      showToast("Erro ao obter dados. Tente novamente mais tarde.", "erro");
      throw erro;
    });
}

function criarGraficoPizza(ctx: CanvasRenderingContext2D, labels: string[], dados: number[], total: number): Chart {
  return new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels.length ? labels : ['Nenhum dado'], // Exibe "Nenhum dado" se não houver labels
      datasets: [{
        label: 'Vendas por Funcionario',
        data: dados.length ? dados : [0], // Exibe 0 se não houver dados
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
        borderColor: '#fff',
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
              const percentual = ((totalVendido / total) * 100).toFixed(2);
              return `${tooltipItem.label}: R$ ${totalVendido.toFixed(2)} (${percentual}%)`;
            }
          }
        },
        title: {
          display: true,
          text: `Total Vendido no Período: R$ ${total.toFixed(2)}`
        }
      }
    }
  });
}

function processarVendasPorFuncionario(vendas: VendaFuncionario[], totalVendidoPeriodo: number): { labels: string[], valores: number[], totalVendido: number } {
  const labels: string[] = vendas.map(venda => venda.nomeFuncionario);
  const valores: number[] = vendas.map(venda => venda.totalVendido);
  return {
    labels,
    valores,
    totalVendido: totalVendidoPeriodo,
  };
}

const obterDados = async (dataInicio: Date, dataFim: Date): Promise<void> => {
  let idFuncionario = (document.getElementById('funcionario') as HTMLSelectElement).value; // Obtendo o id do funcionário do select

  if (!idFuncionario) {
    // Caso o funcionário não seja selecionado, tenta pegar o id do funcionário logado
    const idFuncionarioLogado = sessionStorage.getItem('idFuncionario');
    if (idFuncionarioLogado) {
      idFuncionario = idFuncionarioLogado;
    } else {
      showToast("Funcionário não encontrado.", "erro");
      return;
    }
  }

  try {
    const { totalVendidoPeriodo, vendasPorFuncionario }: RespostaAPI = await obterDadosRelatorioFuncionario(dataInicio, dataFim, idFuncionario);

    if (vendasPorFuncionario.length === 0) {
      showToast("Nenhuma venda encontrada para o período e funcionário selecionados.", "erro");
      return;
    }

    const { labels, valores, totalVendido } = processarVendasPorFuncionario(vendasPorFuncionario, totalVendidoPeriodo);

    // Criando gráfico de pizza com as vendas
    const ctx = (document.getElementById('myPieChart') as HTMLCanvasElement).getContext('2d')!;
    if (graficoPizza) {
      graficoPizza.destroy();
    }

    graficoPizza = criarGraficoPizza(ctx, labels, valores, totalVendido);

  } catch (erro) {
    console.error("Erro ao obter dados: ", erro);
  }
};

let graficoPizza: Chart; // Variável para garantir que o gráfico de pizza seja atualizado

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

// Função para listar os funcionários no select
const listarFuncionarios = async (): Promise<void> => {
  try {
    const response = await fetch('http://localhost:8000/funcionarios', {credentials: 'include'}); // Endereço da API para listar funcionários
    const funcionarios = await response.json();

    const selectFuncionario = document.getElementById('funcionario') as HTMLSelectElement;
    funcionarios.forEach((funcionario: { id: number, nome: string }) => {
      const option = document.createElement('option');
      option.value = funcionario.id.toString();
      option.textContent = funcionario.nome;
      selectFuncionario.appendChild(option);
    });
  } catch (error) {
    console.error("Erro ao listar funcionários: ", error);
    showToast("Erro ao carregar lista de funcionários.", "erro");
  }
};

// Carregar os funcionários assim que a página for carregada
document.addEventListener('DOMContentLoaded', () => {
  listarFuncionarios();
});
