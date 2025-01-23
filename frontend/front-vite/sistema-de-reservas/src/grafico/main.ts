import { ReservaView } from "./reserva-visao.ts";
import { ReservaController } from "./reserva-controller.ts";
import { showToast } from '../infra/toastify.ts';

const ctx = (document.getElementById('myChart') as HTMLCanvasElement).getContext('2d')!;
const view = new ReservaView(ctx);
const Controller = new ReservaController(view);

const form = document.getElementById('relatorioForm');
if (form) {
  form.addEventListener('submit', (event) => {
    event.preventDefault();

    console.log('Formulário enviado'); // Adicionando o log para verificar

    const dataInicioInput = (document.getElementById('dataInicio') as HTMLInputElement).value;
    const dataFimInput = (document.getElementById('dataFim') as HTMLInputElement).value;

    const dataInicio = new Date(`${dataInicioInput}T00:00:00`);
    const dataFim = new Date(`${dataFimInput}T23:59:59`);

    Controller.carregarReservas(dataInicio, dataFim);
  });
} else {
  showToast(`Elemento 'relatorioForm' não encontrado.`, 'erro');
}


// Carrega o gráfico inicial (mês atual)
const hoje = new Date();
const primeiroDia = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
const ultimoDia = new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0);

Controller.carregarReservas(primeiroDia, ultimoDia);
