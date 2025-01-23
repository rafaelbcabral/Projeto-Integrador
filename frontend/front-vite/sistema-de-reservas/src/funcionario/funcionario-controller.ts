import { GestorFuncionarios } from "./gestor-funcionario";
import { VisaoFuncionarios } from "./visao-funcionario";
import { exibirErro } from '../infra/exibir-erro.ts';

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
      console.log(funcionarios);
      this.visao.exibirFuncionarios(funcionarios);
    } catch (error) {
      exibirErro('Erro ao listar funcionários', error);
    }
  }
  
  
}
