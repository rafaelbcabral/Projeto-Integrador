import { Chart, CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend, PieController, ArcElement } from 'chart.js';
import { showToast } from '../infra/toastify';

Chart.register(CategoryScale, LinearScale, BarElement, BarController, PieController, ArcElement, Title, Tooltip, Legend);

type RespostaAPI = {
  totalVendidoPeriodo: number;
  formasPagamento: string[];
};

function formatarData(data: Date): string {
  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');
  return `${ano}-${mes}-${dia}`;
}

function obterDatasMesAtual(): { primeiroDia: Date; ultimoDia: Date; pagPadrao: string } {
  const hoje = new Date();
  const ano = hoje.getFullYear();
  const mes = hoje.getMonth(); // (0-11)

  const primeiroDia = new Date(ano, mes, 1); // primeiro dia do mês
  const ultimoDia = new Date(ano, mes + 1, 0); // último dia do mês
  const pagPadrao = "cartao";

  return { primeiroDia, ultimoDia, pagPadrao };
}

function criarGraficoPizza(ctx: CanvasRenderingContext2D, labels: string[], dados: number[], total: number): Chart {
  return new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels.length ? labels : ['Nenhum dado'], // Exibe "Nenhum dado" se não houver labels
      datasets: [{
        label: 'Vendas por Forma de Pagamento',
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
          text: `Total Vendido: R$ ${total.toFixed(2)}`
        }
      }
    }
  });
}

const obterDados = async (dataInicio: Date, dataFim: Date, formaDePagamento: string): Promise<void> => {
  const dataInicial = formatarData(dataInicio); // Formata a data de início
  const dataFinal = formatarData(dataFim); // Formata a data de fim
  const formaPag = formaDePagamento;
  const endpoint = `http://localhost:8000/relatorio/pagamento?dataInicio=${dataInicial}&dataFim=${dataFinal}&formaDePagamento=${formaPag}`; // Endpoint com parâmetros de data

  try {
    const res = await fetch(endpoint, {credentials: 'include'});
    const contentType = res.headers.get('Content-Type');

    if (contentType && contentType.includes('application/json')) {
      const data: RespostaAPI = await res.json(); // Espera pela conversão para JSON
      console.log("Resposta da API:", data); // Adicionando o log aqui para depuração

      // Verificando se a resposta tem as chaves esperadas
      if (data && typeof data === 'object' && 'totalVendidoPeriodo' in data && 'formasPagamento' in data) {
        const totalVendido = data.totalVendidoPeriodo;
        const formasPagamento = data.formasPagamento;

        // Se não houver dados de formas de pagamento, exiba uma mensagem informando o usuário
        if (formasPagamento.length === 0) {
          showToast("Não há dados de formas de pagamento para este período.", "erro");
        }

        // Criando o gráfico de pizza com as formas de pagamento ou uma mensagem alternativa
        const ctx = (document.getElementById('myPieChart') as HTMLCanvasElement).getContext('2d')!;
        if (graficoPizza) {
          graficoPizza.destroy();
        }

        if (formasPagamento.length > 0) {
          // Se houver dados de formas de pagamento, mostra o gráfico
          graficoPizza = criarGraficoPizza(ctx, formasPagamento, [totalVendido], totalVendido);
        } else {
          // Se não houver dados de formas de pagamento, mostra um gráfico vazio ou com uma mensagem
          showToast("Não há dados para gerar o gráfico.", "erro");
          graficoPizza = criarGraficoPizza(ctx, ['Nenhum dado'], [0], 0);
        }

      } else {
        showToast("Formato de resposta incorreto. Esperado um objeto com totalVendidoPeriodo e formasPagamento.", "erro");
      }

    } else {
      const errorText = await res.text(); // Pega a resposta como texto
      console.error("Erro no servidor ou formato de resposta inesperado: ", errorText);
      showToast("Erro ao obter dados. Verifique a resposta do servidor.", "erro");
    }
  } catch (erro) {
    console.error("Erro ao obter dados: " + erro);
    showToast("Erro ao obter dados. Tente novamente mais tarde.", "erro");
  }
};

let graficoPizza: Chart; // Variável para garantir que o gráfico de pizza seja atualizado

const { primeiroDia, ultimoDia, pagPadrao } = obterDatasMesAtual();

// Criar o gráfico de pizza com as vendas do mês atual
graficoPizza = criarGraficoPizza((document.getElementById('myPieChart') as HTMLCanvasElement).getContext('2d')!, [], [], 0);

obterDados(primeiroDia, ultimoDia, pagPadrao);

const form = document.getElementById('relatorioForm');
if (form) {
  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const dataInicioInput = (document.getElementById('dataInicio') as HTMLInputElement).value;
    const dataInicio = new Date(`${dataInicioInput}T00:00:00`); // Força a hora correta
    const formPag = (document.getElementById('formaDePagamento') as HTMLSelectElement).value;
    const dataFimInput = (document.getElementById('dataFim') as HTMLInputElement).value;
    const dataFim = new Date(`${dataFimInput}T23:59:59`); // Força a hora correta

    if (graficoPizza) { // Recria o gráfico ao mudar o intervalo
      graficoPizza.destroy();
    }

    graficoPizza = criarGraficoPizza((document.getElementById('myPieChart') as HTMLCanvasElement).getContext('2d')!, [], [], 0);
    obterDados(dataInicio, dataFim, formPag);
  });
} else {
  console.error("Elemento 'relatorioForm' não encontrado.");
}
