import { showToast } from "../infra/toastify";

// Verifica se o usuário está logado
function checkLogin() {
  const user_id = sessionStorage.getItem("user_id");

  // Verifica se o usuário não está logado e se a página atual não é a página de login
  if (!user_id && window.location.pathname !== '/login') {
    showToast('Você precisa estar logado!', 'erro')
    window.location.href = "/login"; // Redireciona para a página de login
  }
}

// Verificar o status de login quando a página carregar
window.onload = () => {
  // Verifica o login apenas se não estiver na página de login
  if (window.location.pathname !== '/login') {
    checkLogin();
  }
};
