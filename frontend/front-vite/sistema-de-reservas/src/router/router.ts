import page from 'page';
import { VisaoFuncionarios } from "../funcionario/visao-funcionario";
import { ControladoraFuncionarios } from "../funcionario/funcionario-controller";
import { VisaoMesas } from '../mesa/visao-mesa.ts';  // Caminho correto
import { ControladoraMesas } from '../mesa/mesa-controller.ts'
import { checkLoginStatus } from '../login/login.ts';
import { login } from '../login/login.ts';



export function mostrarHTML(html: string) {
  const divContent = document.getElementById('content');
  divContent!.innerHTML = html;
}

// Página de login
page("/login", async () => {
  const response = await fetch("/pages/login/login.html");
  const html = await response.text();
  mostrarHTML(html);

  // Adiciona o listener ao formulário de login após a página ser carregada
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", login);
  }
});


// Página inicial
page("/home", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/home.html");
  const html = await response.text();
  mostrarHTML(html);

  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/reserva/main-listar-reservas.ts'; // Para listar reservas
  script.defer = true;
  document.body.appendChild(script);
  script.id
});

// Página de fazer reservas
page("/fazer-reservas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/reservas/fazer-reservas.html");
  const html = await response.text();
  mostrarHTML(html);

  // Carregar o script para a criação de reservas
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/reserva/main-criar-reservas.ts'; // Para fazer reservas
  script.defer = true;
  document.body.appendChild(script);

  // Agora, execute a lógica para listar os funcionários
  const visaoFuncionarios = new VisaoFuncionarios();
  const controladoraFuncionarios = new ControladoraFuncionarios(visaoFuncionarios);
  controladoraFuncionarios.listarFuncionarios();

  // Execute a lógica para listar as mesas disponíveis
  const visaoMesas = new VisaoMesas();
  const controladoraMesas = new ControladoraMesas(visaoMesas);
  const data = document.getElementById("data") as HTMLInputElement;
  const horarioInicial = document.getElementById("horarioInicial") as HTMLInputElement;
  const dataString = String(data.value);
  const horarioInicialString = String(horarioInicial.value);
  controladoraMesas.consultarMesasDisponiveis(dataString, horarioInicialString);
});


page("/listar-reservas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/reservas/listar-reservas.html");
  const html = await response.text();
  mostrarHTML(html);

  // Carregar o script para listar reservas
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/reserva/main-listar-reservas.ts'; // Para listar reservas
  script.defer = true;
  document.body.appendChild(script);

  // await import (`/src/reserva/main-listar-reservas.ts`)
});

// Outras páginas (similares)...
page("/consumo-mesas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/mesas/consumo-mesas.html");
  const html = await response.text();
  mostrarHTML(html);
});

page("/fechar-contas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/mesas/fechar-contas.html");
  const html = await response.text();
  mostrarHTML(html);
});

page("/grafico", async () => {
  checkLoginStatus();

  // Carregar a página HTML do gráfico
  const response = await fetch("/pages/relatorios/grafico.html");
  const html = await response.text();
  mostrarHTML(html);

  // Adiciona o script para gerar o gráfico
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/grafico/grafico-reserva.ts'; // Carregar o script do gráfico
  script.defer = true;
  document.body.appendChild(script);
})
// Inicia o roteador
page();
