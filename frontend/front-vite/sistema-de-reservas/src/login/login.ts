import { url } from "../infra/url";
import { showToast } from "../infra/toastify";
import { exibirErro } from "../infra/exibir-erro";

(window as any).logout = logout;
// Verificar se o usuário está logado
export function checkLoginStatus() {
  const user_id = sessionStorage.getItem("user_id");

  const currentPath = window.location.pathname;

  if (!user_id) {
    // Se não estiver logado, redireciona para login
    if (currentPath !== '/login') {
      showToast('Você precisa fazer o login!', 'erro');

      window.location.href = "/login"; // Redireciona para login
    }
  } else {
    // Se estiver logado e na página de login, redireciona para home
    if (currentPath === '/login') {
      window.location.href = "/home"; // Redireciona para página inicial
    }
  }
}


// Login
// Certifique-se de que o script está carregando após o DOM ser montado
window.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", login);
  }
});

// Função de login

// ROTA DO LOGIN NO ROUTER
export async function login(event: Event) {
  event.preventDefault(); // Impede o recarregamento da página

  // Seleção dos elementos do DOM
  const usuarioElement = document.getElementById("usuario") as HTMLInputElement;
  const senhaElement = document.getElementById("senha") as HTMLInputElement;

  // Verificação dos campos
  if (!usuarioElement || !senhaElement) {
    showToast("Os campos de usuário ou senha não foram encontrados no DOM.", "erro");
    return;
  }

  const usuario = usuarioElement.value;
  const senha = senhaElement.value;

  // Validação dos valores dos campos
  if (!usuario || !senha) {
    showToast('Preencha o usuário e a senha!', 'erro');

    return;
  }

  try {
    // Enviar requisição para o endpoint de login
    const response = await fetch(`${url}/login`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ usuario, senha }),
    });

    // Processar resposta do servidor
    const result = await response.json();

    if (response.ok) {
      // Exibir mensagem de sucesso e armazenar user_id
      showToast('Usuário logado!', 'sucesso');

      sessionStorage.setItem("user_id", result.user_id); // Guardar ID de usuário no sessionStorage

      // Verificar se o user_id foi armazenado corretamente
      const user_id = sessionStorage.getItem("user_id");
      if (!user_id) {
        showToast('Ocorreu um erro ao recuperar o ID do usuário.', 'erro');

      } else {
        checkLoginStatus(); // Atualizar a interface com o status de login
      }
    } else {
      exibirErro("Erro ao fazer login", result.error)
    }
  } catch (error) {
    exibirErro("Erro ao fazer login", error)
  }
}





// Logout
export async function logout() {
  try {
    const response = await fetch(`${url}/logout`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
    });

    // Se a resposta não for ok, lança um erro
    if (!response.ok) {
      const result = await response.json();
      throw new Error(result.error || "Erro desconhecido ao fazer logout.");
    }

    // Se a resposta for bem-sucedida, remova o item do sessionStorage e exiba uma mensagem
    alert("Usuário deslogado!");
    sessionStorage.removeItem("user_id"); // Remover o ID de usuário do sessionStorage
    checkLoginStatus(); // Verificar status de login após logout

  } catch (error) {
    // Se ocorrer algum erro no processo (seja ao chamar a API ou processar a resposta), exibe o erro
    exibirErro("Erro ao fazer logout", error || "erro");
  }
}

// Verificar o status de login quando a página carregar
window.onload = checkLoginStatus;
