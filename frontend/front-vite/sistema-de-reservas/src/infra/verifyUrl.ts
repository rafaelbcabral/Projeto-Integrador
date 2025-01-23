const currentPath = window.location.pathname;
const sidebar = document.getElementById("sidebar");

if (currentPath === '/login') {
  if (sidebar) {
    sidebar.style.display = 'none'; // Oculta a sidebar
  }
}

Jogar no router