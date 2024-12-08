import { GestorMesas } from "./gestor-mesas";
import { VisaoMesas } from "./visao-mesa";

export class ControladoraMesas {
  private gestor: GestorMesas;
  private visao: VisaoMesas;

  constructor(visao: VisaoMesas) {
    this.gestor = new GestorMesas();
    this.visao = visao;
  }

  async consultarMesasDisponiveis(data: string, horarioInicial: string) {
    try {
      const mesas = await this.gestor.consultarMesasDisponiveis(
        data,
        horarioInicial
      );
      this.visao.exibirMesas(mesas);
    } catch (error) {
      console.error("Erro ao consultar mesas:", error);
    }
  }
}
