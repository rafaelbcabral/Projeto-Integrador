// Tipagem de Funcionário
interface Funcionario {
  id: number;
  nome: string;
}

// Tipagem de Mesa
interface Mesa {
  id: number;
  numero: number;
}

// Tipagem dos dados da Reserva
interface ReservaData {
  nomeCliente: string;
  data: string;
  horaInicial: string;
  mesa: number;
  funcionario: number;
}

// Função para carregar os funcionários da API
async function carregarFuncionarios(): Promise<void> {
  try {
    const response = await fetch("http://localhost:8000/mesas"); // Substitua pelo endpoint correto
    const funcionarios: Funcionario[] = await response.json();

    const funcionarioSelect = document.getElementById(
      "funcionario"
    ) as HTMLSelectElement;
    funcionarios.forEach((funcionario) => {
      const option = document.createElement("option");
      option.value = funcionario.id.toString();
      option.textContent = `${funcionario.nome}`;
      funcionarioSelect.appendChild(option);
    });
  } catch (error) {
    console.error("Erro ao carregar funcionários:", error);
  }
}

// Função para carregar as mesas da API
async function carregarMesas(): Promise<void> {
  try {
    const response = await fetch("http://localhost:8000/mesas"); // Substitua pelo endpoint correto
    const mesas: Mesa[] = await response.json();
    console.log(mesas);

    const mesaSelect = document.getElementById("mesa") as HTMLSelectElement;
    mesas.forEach((mesa) => {
      const option = document.createElement("option");
      option.value = mesa.id.toString();
      option.textContent = `Mesa ${mesa.numero}`;
      mesaSelect.appendChild(option);
    });
  } catch (error) {
    console.error("Erro ao carregar mesas:", error);
  }
}

// Função para enviar os dados da reserva para a API
async function enviarReserva(event: Event): Promise<void> {
  event.preventDefault();

  const form = document.getElementById("reservationForm") as HTMLFormElement;
  const data = new FormData(form);
  const reservaData: ReservaData = {
    nomeCliente: data.get("name") as string,
    data: data.get("date") as string,
    horaInicial: data.get("time") as string,
    mesa: parseInt(data.get("mesa") as string),
    funcionario: parseInt(data.get("funcionario") as string),
  };

  try {
    const response = await fetch("http://localhost:8000/reservas", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(reservaData),
    });

    const result = await response.json();

    if (response.ok) {
      alert("Reserva realizada com sucesso!");
      adicionarReservaNaLista(result);
    } else {
      alert("Erro ao realizar reserva: " + result.message);
    }
  } catch (error) {
    console.error("Erro ao enviar reserva:", error);
    alert("Erro interno ao realizar a reserva");
  }
}

// Função para adicionar a reserva na lista na interface
function adicionarReservaNaLista(reserva: ReservaData): void {
  const reservationsList = document.getElementById(
    "reservationsList"
  ) as HTMLUListElement;
  const li = document.createElement("li");
  li.textContent = `Reserva para ${reserva.nomeCliente} na mesa ${reserva.mesa} para ${reserva.data} às ${reserva.horaInicial}`;
  reservationsList.appendChild(li);
}

// Inicialização da página: Carregar mesas e funcionários e configurar o formulário
function inicializar(): void {
  carregarMesas();
  carregarFuncionarios();

  const reservationForm = document.getElementById(
    "reservationForm"
  ) as HTMLFormElement;
  reservationForm.addEventListener("submit", enviarReserva);
}

// Chama a função de inicialização ao carregar a página
document.addEventListener("DOMContentLoaded", inicializar);
