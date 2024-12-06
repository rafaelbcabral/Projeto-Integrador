import { FuncionarioController } from './funcionario/funcionario-controller.js';
import { FuncionarioView } from './funcionario/funcionario-view.js';
import { MesaController } from './mesa/mesa-controller.js';
import { MesaView } from './mesa/mesa-view.js';
import { ReservaController } from './reserva/reserva-controller.js';
import { ReservaView } from './reserva/reserva-view.js';
import { RelatoriosController } from './relatorios/relatorios-controller.js';
import { RelatoriosView } from './relatorios/relatorios-view.js';

// Inicialização das views
const funcionarioView = new FuncionarioView();
const mesaView = new MesaView();
const reservaView = new ReservaView();
const relatoriosView = new RelatoriosView();

// Inicialização dos controladores
const funcionarioController = new FuncionarioController(funcionarioView);
const mesaController = new MesaController(mesaView);
const reservaController = new ReservaController(reservaView);
const relatoriosController = new RelatoriosController(relatoriosView);

// Carregar a lista de funcionários quando a página for carregada
document.addEventListener('DOMContentLoaded', () => {
  // Carregar a lista de funcionários quando a página for carregada
  funcionarioController.carregarFuncionarios();

  // Lidar com a reserva de mesas
  const formReserva = document.getElementById('formReserva') as HTMLFormElement;
  formReserva?.addEventListener('submit', (event) => {
    event.preventDefault();

    const nomeCliente = (document.getElementById('nomeCliente') as HTMLInputElement).value;
    const mesaSelecionada = (document.getElementById('selectMesas') as HTMLSelectElement).value;
    const dataReserva = (document.getElementById('dataReserva') as HTMLInputElement).value;
    const horarioReserva = (document.getElementById('horarioReserva') as HTMLInputElement).value;
    const funcionarioNome = (document.getElementById('funcionarioNome') as HTMLSelectElement).value;

    const reserva = {
      nomeCliente,
      mesa: parseInt(mesaSelecionada),
      data: dataReserva,
      horario: horarioReserva,
      funcionario: funcionarioNome,
    };

    reservaController.realizarReserva(reserva);
  });

  // Lidar com o cancelamento de uma reserva
  document.getElementById('listaReservas')?.addEventListener('click', (event) => {
    if ((event.target as HTMLElement).tagName === 'BUTTON') {
      const reservaId = (event.target as HTMLElement).closest('tr')?.getAttribute('data-id');
      if (reservaId) {
        reservaController.cancelarReserva(Number(reservaId));
      }
    }
  });

  // Gerar relatório de reservas
  const formRelatorio = document.getElementById('formRelatorio') as HTMLFormElement;
  formRelatorio?.addEventListener('submit', (event) => {
    event.preventDefault();

    const dataInicio = (document.getElementById('dataInicio') as HTMLInputElement).value;
    const dataFim = (document.getElementById('dataFim') as HTMLInputElement).value;

    relatoriosController.gerarRelatorio(dataInicio, dataFim);
  });

  // Carregar mesas disponíveis ao escolher data e horário
  const formMesa = document.getElementById('formMesa') as HTMLFormElement;
  formMesa?.addEventListener('submit', (event) => {
    event.preventDefault();

    const dataReserva = (document.getElementById('dataReserva') as HTMLInputElement).value;
    const horarioReserva = (document.getElementById('horarioReserva') as HTMLInputElement).value;

    mesaController.carregarMesasDisponiveis(dataReserva, horarioReserva);
  });
});

