import { Chart, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend, PieController, ArcElement } from 'chart.js';
import { showToast } from '../infra/toastify';

Chart.register(CategoryScale, LinearScale, BarElement, BarController, PieController, ArcElement, Title, Tooltip, Legend);

type VendaCategoria = {
  nomeCategoria: string;
  totalVendido: number;
  percentual: number;
};

type RespostaAPI = {
  totalVendidoPeriodo: number;
  vendasPorCategoria: VendaCategoria[];
};

type Categoria = {
  id: number;
  nome: string;
};

function formatarData(data: Date): string {
  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');
  return `${ano}-${mes}-${dia}`;
}

function obterCategorias(): Promise<Categoria[]> {
  return fetch('http://localhost:8000/categorias', {credentials: 'include'})
    .then(res => res.json())
    .then(data => data)
    .catch(erro => {
      console.error("Erro ao carregar categorias: " + erro);
      showToast("Erro ao carregar categorias. Tente novamente mais tarde.", "erro");
      throw erro;
    });
}

function obterDadosRelatorioCategoria(dataInicio: Date, dataFim: Date, idCategoria: string): Promise<RespostaAPI> {
  const dataInicial = formatarData(dataInicio); // Formata a data de início
  const dataFinal = formatarData(dataFim); // Formata a data de fim

  const endpoint = `http://localhost:8000/relatorio/categoria?dataInicio=${dataInicial}&dataFim=${dataFinal}&idCategoria=${idCategoria}`;

  return fetch(endpoint)
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
        label: 'Vendas por Categoria',
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

function processarVendasPorCategoria(vendas: VendaCategoria[], totalVendidoPeriodo: number): { labels: string[], valores: number[], totalVendido: number } {
  const labels: string[] = vendas.map(venda => venda.nomeCategoria);
  const valores: number[] = vendas.map(venda => venda.totalVendido);
  return {
    labels,
    valores,
    totalVendido: totalVendidoPeriodo,
  };
}

const obterDados = async (dataInicio: Date, dataFim: Date): Promise<void> => {
  const idCategoria = (document.getElementById('categoria') as HTMLSelectElement).value || ''; // Obtendo a categoria selecionada (caso exista)

  try {
    const { totalVendidoPeriodo, vendasPorCategoria }: RespostaAPI = await obterDadosRelatorioCategoria(dataInicio, dataFim, idCategoria);

    if (vendasPorCategoria.length === 0) {
      showToast("Nenhuma venda encontrada para o período e categoria selecionados.", "erro");
    }

    const { labels, valores, totalVendido } = processarVendasPorCategoria(vendasPorCategoria, totalVendidoPeriodo);

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

// Carregar categorias na seleção
const carregarCategorias = async () => {
  try {
    const categorias: Categoria[] = await obterCategorias();
    const categoriaSelect = document.getElementById('categoria') as HTMLSelectElement;

    categorias.forEach(categoria => {
      const option = document.createElement('option');
      option.value = categoria.id.toString();
      option.textContent = categoria.nome;
      categoriaSelect.appendChild(option);
    });
  } catch (erro) {
    console.error("Erro ao carregar categorias", erro);
  }
};

carregarCategorias();

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
