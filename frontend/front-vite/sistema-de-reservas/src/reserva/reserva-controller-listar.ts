import { GestorReservas } from "./gestor-reserva";
import { ReservaListar } from "./listar-reservas";
import { VisaoListarReservas } from "./visao-listar-reservas";
import { exibirErro } from "../infra/exibir-erro";

export class ControladoraListarReservas {
  private gestor: GestorReservas;
  visao: VisaoListarReservas;

  constructor(visao: VisaoListarReservas) {
    this.gestor = new GestorReservas();
    this.visao = visao;
  }

  async ListarReservas(): Promise<void> {
    try {
      // Realiza a requisição à API para obter as reservas
      const reservas: ReservaListar[] = await this.gestor.listarReservas();
      // Passa os dados para a visão para exibir na tabela
      this.visao.desenharReservas(reservas);
    } catch (error) {
      exibirErro('Erro ao listar reservas. ', error);
    }
  }

  async cancelarReserva(id: string): Promise<void> {
    try {
      await this.gestor.cancelarReserva(id);
    } catch (error) {
      exibirErro('Erro ao listar reservas. ', error);
    }
  }
}
