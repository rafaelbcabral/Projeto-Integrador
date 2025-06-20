import { ControladoraFuncionarios } from "../funcionario/funcionario-controller";
import { ControladoraMesas } from "../mesa/mesa-controller";
import { Reserva } from "./criar-reserva";
import { ControladoraReservas } from "./reserva-controller";
import { showToast } from "../infra/toastify";

export class VisaoCriarReservas {
  controladoraFuncionario: ControladoraFuncionarios;
  controladoraMesa: ControladoraMesas;
  controladoraReserva: ControladoraReservas;

  constructor(
    controladoraMesas: ControladoraMesas,
    controladoraFuncionarios: ControladoraFuncionarios
  ) {
    this.controladoraFuncionario = controladoraFuncionarios;
    this.controladoraMesa = controladoraMesas;
    this.controladoraReserva = new ControladoraReservas(this);
  }

  // Inicializa as controladoras e chama os métodos necessários
  iniciar() {
    this.controladoraFuncionario.listarFuncionarios();
    this.adicionarListenersParaDataEHorario();
    
    const form = document.querySelector("form"); // Ensure this matches your form's selector
    if (!form) {
      showToast('Formulário não encontrado! ', 'erro');
      return;
    }

    // Adiciona o evento de submit ao formulário
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      this.controladoraReserva.criarReserva();
    });
  }

  // Adiciona os listeners de evento nos campos de data e horário
  adicionarListenersParaDataEHorario() {
    const inputData = document.getElementById("data");
    const inputHorario = document.getElementById("horarioInicial");
  
    if (inputData && inputHorario) {
      inputData.addEventListener("change", this.atualizarMesasDisponiveis.bind(this));
      inputHorario.addEventListener("change", this.atualizarMesasDisponiveis.bind(this));
    } else {
      console.error("Erro: os elementos 'data' ou 'horarioInicial' não foram encontrados.");
    }
  }
  

  // Função chamada quando data ou horário inicial mudar
  atualizarMesasDisponiveis() {
    const data = (document.getElementById("data") as HTMLInputElement).value;
    const horarioInicial = (
      document.getElementById("horarioInicial") as HTMLSelectElement
    ).value;

    if (data && horarioInicial) {
      // Chama a controladora de mesas para consultar as mesas disponíveis
      this.controladoraMesa.consultarMesasDisponiveis(data, horarioInicial);
    }
  }

  // Exibe a reserva criada com sucesso
  exibirReservaCriada(reserva: Reserva) {
    const listaDeReservas = document.getElementById("lista-de-reservas");
    if (listaDeReservas) {
      listaDeReservas.innerHTML = `
        <p>Reserva confirmada!</p>
        <p>Cliente: ${reserva.nomeCliente}</p>
        <p>Mesa: ${reserva.mesa}</p>
        <p>Data: ${reserva.data}</p>
        <p>Hora: ${reserva.inicio}</p>
        <p>telefone: ${reserva.telefoneCliente}</p>
      `;
    }
  }

  // Captura os dados do formulário e os envia para a controladora de reserva
  capturarDadosFormulario() {
    const nomeCliente = (document.getElementById("nome") as HTMLInputElement)
      .value;
    const mesaId = (document.getElementById("mesa") as HTMLSelectElement).value;
    const data = (document.getElementById("data") as HTMLInputElement).value;
    const inicio = (
      document.getElementById("horarioInicial") as HTMLSelectElement
    ).value;
    const funcionarioId = (
      document.getElementById("funcionario") as HTMLSelectElement
    ).value;
    const telefoneCliente = (
      document.getElementById("telefone") as HTMLSelectElement
    ).value;

    return {
      nomeCliente,
      mesa: Number(mesaId),
      data,
      inicio,
      funcionario: Number(funcionarioId),
      telefoneCliente: Number(telefoneCliente)
    };
  }
}
