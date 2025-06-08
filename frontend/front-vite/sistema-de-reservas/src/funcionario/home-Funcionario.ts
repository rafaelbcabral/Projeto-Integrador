import { url } from "../infra/url";
import { showToast } from "../infra/toastify";
async function carregarNomeFuncionario() {
  const nomeFuncionarioElement = document.getElementById("nomeFuncionario");

  if (!nomeFuncionarioElement) {
    showToast("Elemento com o ID 'nomeFuncionario' não encontrado.", "erro");
    return;
  }

  try {
    // Simulando chamada para uma API que retorna o nome do funcionário
    const response = await fetch(`${url}/funcionario`, {credentials: 'include'}); // Altere para o endpoint real
    if (!response.ok) {
      showToast("Erro ao carregar o nome do funcionário.", "erro");
    }

    const data = await response.json();
    const nomeFuncionario = data.nome || "Funcionário";

    // Exibe o nome do funcionário no elemento
    nomeFuncionarioElement.textContent = nomeFuncionario;
  } catch (error) {
    showToast("Erro ao carregar o nome do funcionário:", "erro");
    nomeFuncionarioElement.textContent = "Erro ao carregar nome";
  }
}

// Executa a função quando o DOM estiver completamente carregado
document.addEventListener("DOMContentLoaded", () => {
  carregarNomeFuncionario();
});
