import { Reserva } from "./criar-reserva";
import { GestorReservas } from "./gestor-reserva";
import { VisaoCriarReservas } from "./visao-criar-reserva";

export class ControladoraReservas {
  private gestor: GestorReservas;
  private visao: VisaoCriarReservas;

  constructor(visao: VisaoCriarReservas) {
    this.gestor = new GestorReservas();
    this.visao = visao;
  }

  async criarReserva() {
    const { nomeCliente, mesa, data, horarioInicial, funcionario } =
      this.visao.capturarDadosFormulario();
    try {
      // Cria o objeto de reserva
      const reserva: Reserva = {
        nomeCliente,
        mesa,
        data,
        horarioInicial,
        funcionario,
      };

      console.log(reserva);

      // Envia o objeto para o gestor para criar a reserva
      await this.gestor.criarReserva(reserva);
    } catch (error) {
      console.error("Erro ao criar reserva:", error);
      this.visao.exibirErro(error);
    }
  }
}
