import { url

 } from "../infra/url";
async function carregarNomeFuncionario() {
  const nomeFuncionarioElement = document.getElementById("nomeFuncionario");

  if (!nomeFuncionarioElement) {
    console.error("Elemento com o ID 'nomeFuncionario' não encontrado.");
    return;
  }

  try {
    // Simulando chamada para uma API que retorna o nome do funcionário
    const response = await fetch(`${url}/funcionario`); // Altere para o endpoint real
    if (!response.ok) {
      throw new Error("Erro ao carregar o nome do funcionário.");
    }

    const data = await response.json();
    const nomeFuncionario = data.nome || "Funcionário";

    // Exibe o nome do funcionário no elemento
    nomeFuncionarioElement.textContent = nomeFuncionario;
  } catch (error) {
    console.error("Erro ao carregar o nome do funcionário:", error);
    nomeFuncionarioElement.textContent = "Erro ao carregar nome";
  }
}

// Executa a função quando o DOM estiver completamente carregado
document.addEventListener("DOMContentLoaded", () => {
  carregarNomeFuncionario();
});
