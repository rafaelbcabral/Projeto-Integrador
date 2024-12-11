import { ControladoraFuncionarios } from "../funcionario/funcionario-controller";
import { ControladoraMesas } from "../mesa/mesa-controller";
import { Reserva } from "./criar-reserva";
import { ControladoraReservas } from "./reserva-controller";

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
      console.error("Formulário não encontrado.");
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
    const inputData = document.getElementById("data") as HTMLInputElement;
    const inputHorario = document.getElementById(
      "horarioInicial"
    ) as HTMLSelectElement;

    // Quando a data ou o horário inicial mudar, consulta as mesas disponíveis
    inputData.addEventListener(
      "change",
      this.atualizarMesasDisponiveis.bind(this)
    );
    inputHorario.addEventListener(
      "change",
      this.atualizarMesasDisponiveis.bind(this)
    );
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
        <p>Hora: ${reserva.horarioInicial}</p>
      `;
    }
  }

  // Exibe mensagem de erro
  exibirErro(error: any) {
    alert(error instanceof Error ? error.message : error);
  }

  // Captura os dados do formulário e os envia para a controladora de reserva
  capturarDadosFormulario() {
    const nomeCliente = (document.getElementById("nome") as HTMLInputElement)
      .value;
    const mesaId = (document.getElementById("mesa") as HTMLSelectElement).value;
    const data = (document.getElementById("data") as HTMLInputElement).value;
    const horarioInicial = (
      document.getElementById("horarioInicial") as HTMLSelectElement
    ).value;
    const funcionarioId = (
      document.getElementById("funcionario") as HTMLSelectElement
    ).value;

    return {
      nomeCliente,
      mesa: Number(mesaId),
      data,
      horarioInicial,
      funcionario: Number(funcionarioId),
    };
  }
}
