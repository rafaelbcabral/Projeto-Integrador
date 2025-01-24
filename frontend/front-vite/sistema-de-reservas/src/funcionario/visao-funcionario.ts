import { Funcionario } from "./funcionario";
import { exibirErro } from "../infra/exibir-erro";

export class VisaoFuncionarios {
  exibirFuncionarios(funcionarios: Funcionario[]) {
    try {
      const selectFuncionario = document.getElementById(
        "funcionario"
      ) as HTMLSelectElement;

      if (!selectFuncionario) {
        throw new Error('Elemento de seleção não encontrado');
      }

      selectFuncionario.innerHTML =
        '<option value="">Selecione o Funcionário</option>'; // Limpa as opções antigas

      funcionarios.forEach((funcionario) => {
        const option = document.createElement("option");
        option.value = String(funcionario.id);
        option.textContent = funcionario.nome;
        selectFuncionario.appendChild(option);
      });
    } catch (error) {
      exibirErro('Erro ao exibir funcionários', error);
    }
  }
}
