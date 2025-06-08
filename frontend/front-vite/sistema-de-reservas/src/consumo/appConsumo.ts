import { ConsumoView } from './consumo-view';
import { ConsumoController } from './consumo-controller';
import { ConsumoGestor } from './consumo-gestor';



const consumoGestor = new ConsumoGestor();
const consumoView = new ConsumoView();
const consumoController = new ConsumoController(consumoGestor, consumoView);

// Função para carregar dados
document.getElementById('data')!.addEventListener('change', async () => {
  await carregarMesasReservadas();
});

document.getElementById('horarioInicial')!.addEventListener('change', async () => {
  await carregarMesasReservadas();
});

// Função para carregar as mesas reservadas com base na data e horário
async function carregarMesasReservadas() {
  const data = (document.getElementById('data') as HTMLInputElement).value;
  const horario = (document.getElementById('horarioInicial') as HTMLInputElement).value;

  if (!data || !horario) {
    console.log("Por favor, selecione uma data e um horário.");
    return;
  }

  try {
    // Faz a requisição ao backend para obter as mesas reservadas
    const mesasReservadas = await consumoGestor.obterMesasReservadas(data, horario);
    consumoView.exibirMesas(mesasReservadas);
  } catch (error) {
    console.error("Erro ao carregar mesas reservadas:", error);
    consumoView.mostrarErro("Erro ao carregar mesas reservadas.");
  }
}

async function carregarDados() {
  try {
     
    console.log("Carregando itens do servidor...");
    const itens = await consumoController.carregarItensDoServidor();
    consumoView.mostrarItensDoServidor(itens);

  } catch (error) {
    consumoView.mostrarErro('Erro ao carregar dados.');
    console.error("Erro durante o carregamento:", error);
  }
}


// Carrega os dados ao iniciar
carregarDados();


document.getElementById('confirmarLancamento')!.addEventListener('click', async () => {
  const mesaId = String((document.getElementById('mesa') as HTMLSelectElement).value);
  const data = (document.getElementById('data') as HTMLInputElement).value;
  const horario = (document.getElementById('horarioInicial') as HTMLSelectElement).value;
  
  if (!mesaId || !data || !horario) {
    alert('Por favor, preencha todos os campos.');
    return;
  }
  
  try {
    await consumoController.finalizarCompra(mesaId);
  } catch (error) {
    console.error('Erro ao confirmar o lançamento:', error);
  }
  
});

document.addEventListener('adicionarItem', (event) => {
  const item = (event as CustomEvent).detail; // Acessa o item do evento
  console.log('Item recebido no app:', item);

  
  consumoController.adicionarItemAoCarrinho(item);
  consumoController.carregarItensCarrinho();
});



