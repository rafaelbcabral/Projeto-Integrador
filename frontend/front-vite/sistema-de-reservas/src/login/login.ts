import { url } from "../infra/url";

(window as any).logout = logout;
// Verificar se o usuário está logado
export function checkLoginStatus() {
  const user_id = sessionStorage.getItem("user_id");
  if(user_id === null){
    alert('a')
  }
  const currentPath = window.location.pathname;

  if (!user_id) {
    // Se não estiver logado, redireciona para login
    if (currentPath !== '/login') {
      alert('Você precisa fazer o login!');
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

ROTA DO LOGIN NO ROUTER
export async function login(event: Event) {
  event.preventDefault(); // Impede o recarregamento da página

  // Seleção dos elementos do DOM
  const usuarioElement = document.getElementById("usuario") as HTMLInputElement;
  const senhaElement = document.getElementById("senha") as HTMLInputElement;

  // Verificação dos campos
  if (!usuarioElement || !senhaElement) {
    console.error("Os campos de usuário ou senha não foram encontrados no DOM.");
    return;
  }

  const usuario = usuarioElement.value;
  const senha = senhaElement.value;

  // Validação dos valores dos campos
  if (!usuario || !senha) {
    alert("Preencha o usuário e a senha");
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
    console.log("Resposta da API:", result); // Log da resposta da API para debug

    if (response.ok) {
      // Exibir mensagem de sucesso e armazenar user_id
      alert(result.message);
      sessionStorage.setItem("user_id", result.user_id); // Guardar ID de usuário no sessionStorage

      // Verificar se o user_id foi armazenado corretamente
      const user_id = sessionStorage.getItem("user_id");
      if (!user_id) {
        console.warn("user_id não encontrado no sessionStorage.");
        alert("Ocorreu um erro ao recuperar o ID do usuário.");
      } else {
        console.log("Login bem-sucedido. Atualizando a interface...");
        checkLoginStatus(); // Atualizar a interface com o status de login
      }
    } else {
      console.error("Erro no login:", result.error);
      alert(result.error); // Exibir mensagem de erro retornada pela API
    }
  } catch (error) {
    console.error("Erro no login:", error);
    alert("Ocorreu um erro ao tentar fazer login. Tente novamente.");
  }
}





// Logout
export async function logout() {
  const response = await fetch(`${url}/logout`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  });

  const result = await response.json();

  if (response.ok) {
    alert(result.message);
    sessionStorage.removeItem("user_id"); // Remover o ID de usuário do sessionStorage
    checkLoginStatus();
  } else {
    alert(result.error);
  }
}

// Verificar o status de login quando a página carregar
window.onload = checkLoginStatus;
