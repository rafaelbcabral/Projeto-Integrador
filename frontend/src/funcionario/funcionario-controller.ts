import { buscarFuncionarios } from './funcionario-gestor.js';
import { FuncionarioView } from './funcionario-view.js';

export class FuncionarioController {
  private view: FuncionarioView;

  constructor(view: FuncionarioView) {
    this.view = view;
  }

  async carregarFuncionarios() {
    try {
      const funcionarios = await buscarFuncionarios();
      this.view.exibirFuncionarios(funcionarios);
    } catch (erro) {
      this.view.mostrarMensagemErro('Erro ao carregar funcionários.');
    }
  }
}
