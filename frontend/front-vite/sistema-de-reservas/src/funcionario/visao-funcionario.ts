import { Funcionario } from "./funcionario";

export class VisaoFuncionarios {
  exibirFuncionarios(funcionarios: Funcionario[]) {
    const selectFuncionario = document.getElementById(
      "funcionario"
    ) as HTMLSelectElement;
    selectFuncionario.innerHTML =
      '<option value="">Selecione o Funcionário</option>'; // Limpa as opções antigas

    funcionarios.forEach((funcionario) => {
      const option = document.createElement("option");
      option.value = String(funcionario.id);
      option.textContent = funcionario.nome;
      selectFuncionario.appendChild(option);
    });
  }
}

