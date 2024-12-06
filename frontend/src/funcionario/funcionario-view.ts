import { Funcionario } from './funcionario-model.js';

export class FuncionarioView {
  private tabelaFuncionarios: HTMLTableElement;

  constructor() {
    this.tabelaFuncionarios = document.getElementById('tabelaFuncionarios') as HTMLTableElement;
  }

  exibirFuncionarios(funcionarios: Funcionario[]): void {
    this.tabelaFuncionarios.innerHTML = '';
    funcionarios.forEach(funcionario => {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${funcionario.id}</td><td>${funcionario.nome}</td>`;
      this.tabelaFuncionarios.appendChild(tr);
    });
  }

  mostrarMensagemErro(mensagem: string) {
    alert(mensagem);
  }
}
