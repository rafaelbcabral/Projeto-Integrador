import { GestorFuncionarios } from "./gestor-funcionario";
import { VisaoFuncionarios } from "./visao-funcionario";

export class ControladoraFuncionarios {
  private gestor: GestorFuncionarios;
  private visao: VisaoFuncionarios;

  constructor(visao: VisaoFuncionarios) {
    this.gestor = new GestorFuncionarios();
    this.visao = visao;
  }

  async listarFuncionarios() {
    try {
      const funcionarios = await this.gestor.listarFuncionarios();
      this.visao.exibirFuncionarios(funcionarios);
    } catch (error) {
      throw new Error("Erro ao listar funcionários");
    }
  }
  
  
}
