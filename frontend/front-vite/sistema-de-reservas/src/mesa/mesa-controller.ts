import { GestorMesas } from "./gestor-mesas";
import { VisaoMesas } from "./visao-mesa.ts";
import { exibirErro } from '../infra/exibir-erro.ts';

export class ControladoraMesas {
  private gestor: GestorMesas;
  private visao: VisaoMesas;

  constructor(visao: VisaoMesas) {
    if (!visao) {
      throw new Error("Visão das mesas não foi fornecida corretamente!");
    }
    this.gestor = new GestorMesas();
    this.visao = visao;
  }

  async consultarMesasDisponiveis(data: string, horarioInicial: string) {
    try {
      const mesas = await this.gestor.consultarMesasDisponiveis(data, horarioInicial);
      this.visao.exibirMesas(mesas);
    } catch (error) {
      exibirErro('Erro ao consultar mesas: ', error);
    }
  }
}

