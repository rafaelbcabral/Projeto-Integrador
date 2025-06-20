import page from 'page';
import { VisaoFuncionarios } from "../funcionario/visao-funcionario";
import { ControladoraFuncionarios } from "../funcionario/funcionario-controller";
import { VisaoMesas } from '../mesa/visao-mesa.ts';  // Caminho correto
import { ControladoraMesas } from '../mesa/mesa-controller.ts'
import { checkLoginStatus } from '../login/login.ts';
import { login } from '../login/login.ts';

const currentPath = window.location.pathname;
const sidebar = document.getElementById("sidebar");

if (currentPath === '/login') {
  if (sidebar) {
    sidebar.style.display = 'none'; // Oculta a sidebar
  }
}


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

  // const script1 = document.createElement('script');
  // script1.type = 'module';
  // script1.src = '/src/reserva/main-listar-reservas.ts'; // Para listar reservas
  // script1.defer = true;
  // document.body.appendChild(script1);
  // script1.id

  // @ts-ignore
  const contexto = await import (`/src/reserva/visao-listar-reservas.ts`)
  const visao = new contexto.default();
  visao.iniciar();

  // const script2 = document.createElement('script');
  // script2.type = 'module';
  // script2.src = '/src/grafico/grafico-reserva.ts'; // Carregar o script2 do gráfico
  // script2.defer = true;
  // document.body.appendChild(script2);

  // @ts-ignore
  const { iniciar } = await import (`/src/grafico/grafico-reserva.ts`)
  iniciar();
});

// Página de fazer reservas
page("/fazer-reservas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/reservas/fazer-reservas.html");
  const html = await response.text();
  mostrarHTML(html);

  // Carregar o script para a criação de reservas
  // const script = document.createElement('script');
  // script.type = 'module';
  // script.src = '/src/reserva/main-criar-reservas.ts'; // Para fazer reservas
  // script.defer = true;
  // document.body.appendChild(script);

    // @ts-ignore
    await import (`/src/reserva/main-criar-reservas.ts`)

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
  // const script = document.createElement('script');
  // script.type = 'module';
  // script.src = '/src/reserva/main-listar-reservas.ts'; // Para listar reservas
  // script.defer = true;
  // document.body.appendChild(script);

  // @ts-ignore
  const contexto = await import (`/src/reserva/visao-listar-reservas.ts`)
  const visao = new contexto.default();
  visao.iniciar();
});

// Outras páginas (similares)...
page("/consumo-mesas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/mesas/consumo-mesas.html");
  const html = await response.text();
  mostrarHTML(html);

  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/consumo/appConsumo.ts'; // Carregar o script do gráfico
  script.defer = true;
  document.body.appendChild(script);
});

page("/fechar-contas", async () => {
  checkLoginStatus();
  const response = await fetch("/pages/mesas/fechar-contas.html");
  const html = await response.text();
  mostrarHTML(html);
});

page("/relatorio1", async () => {
  checkLoginStatus();

  // Carregar a página HTML do gráfico
  const response = await fetch("/pages/relatorios/relatorio1.html");
  const html = await response.text();
  mostrarHTML(html);

  // Adiciona o script para gerar o gráfico
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/grafico/relatorio01.ts'; // Carregar o script do gráfico
  script.defer = true;
  document.body.appendChild(script);
})

page("/relatorio2", async () => {
  checkLoginStatus();

  // Carregar a página HTML do gráfico
  const response = await fetch("/pages/relatorios/relatorio2.html");
  const html = await response.text();
  mostrarHTML(html);

  // Adiciona o script para gerar o gráfico
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/grafico/relatorio02.ts'; // Carregar o script do gráfico
  script.defer = true;
  document.body.appendChild(script);

  const script2 = document.createElement('script');
  script2.type = 'module';
  script2.src = '/src/reserva/main-criar-reservas.ts'; // Para fazer reservas
  script2.defer = true;
  document.body.appendChild(script2);

  // Agora, execute a lógica para listar os funcionários
  const visaoFuncionarios = new VisaoFuncionarios();
  const controladoraFuncionarios = new ControladoraFuncionarios(visaoFuncionarios);
  controladoraFuncionarios.listarFuncionarios();

  // Execute a lógica para listar as mesas disponíveis
  // const visaoMesas = new VisaoMesas();
  // const controladoraMesas = new ControladoraMesas(visaoMesas);
  // controladoraMesas.consultarMesasDisponiveis(dataString, horarioInicialString);
})

page("/relatorio3", async () => {
  checkLoginStatus();

  // Carregar a página HTML do gráfico
  const response = await fetch("/pages/relatorios/relatorio3.html");
  const html = await response.text();
  mostrarHTML(html);

  // Adiciona o script para gerar o gráfico
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/grafico/relatorio03.ts'; // Carregar o script do gráfico
  script.defer = true;
  document.body.appendChild(script);
})

page("/relatorio4", async () => {
  checkLoginStatus();

  // Carregar a página HTML do gráfico
  const response = await fetch("/pages/relatorios/relatorio4.html");
  const html = await response.text();
  mostrarHTML(html);

  // Adiciona o script para gerar o gráfico
  const script = document.createElement('script');
  script.type = 'module';
  script.src = '/src/grafico/relatorio04.ts'; // Carregar o script do gráfico
  script.defer = true;
  document.body.appendChild(script);
})

// Inicia o roteador
page();
