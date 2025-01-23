import { ReservaListar } from "./listar-reservas";
import { ControladoraListarReservas } from "./reserva-controller-listar";

export class VisaoListarReservas {
  controladoraReserva: ControladoraListarReservas;

  constructor() {
    this.controladoraReserva = new ControladoraListarReservas(this);
  }

  iniciar() {
    this.controladoraReserva.ListarReservas();
  }

  desenharReservas(reservas: ReservaListar[]): void {
    console.log(reservas);
    const tbody = document.querySelector("tbody") as HTMLElement;
    tbody.innerHTML = ""; // Limpa a tabela antes de adicionar novas linhas

    const fragmento = document.createDocumentFragment();
    reservas.forEach((reserva) => {
      const linha = this.criarLinha(reserva);
      fragmento.appendChild(linha);
    });

    tbody.appendChild(fragmento); // Adiciona todas as linhas ao corpo da tabela
  }

  criarLinha(reserva: ReservaListar): HTMLTableRowElement {
    const tr = document.createElement("tr");
    tr.classList.add("border-b", "border-gray-200"); // Adicionando borda entre as linhas

    tr.append(
      // this.criarCelula(reserva.id, "px-4", "py-2", "text-center"),
      this.criarCelula(reserva.nomeCliente, "px-4", "py-2"),
      this.criarCelula(reserva.mesa, "px-4", "py-2", "text-center"),
      this.criarCelula(reserva.data, "px-4", "py-2", "text-center"),
      this.criarCelula(reserva.inicio, "px-4", "py-2", "text-center"),
      this.criarCelula(reserva.fim, "px-4", "py-2", "text-center"),
      this.criarCelula(reserva.nomeFuncionario, "px-4", "py-2"),
      this.criarCelula(reserva.status, "px-4", "py-2", "text-center")
    );

    // Adicionando o botão de cancelar apenas se o status não for "cancelada"
    if (reserva.status !== "cancelada") {
      tr.append(this.criarBotaoCancelar(reserva.id));
    }

    return tr;
  }

  criarBotaoCancelar(id: string): HTMLButtonElement {
    const botaoCancelar = document.createElement("button");
    botaoCancelar.innerText = "Cancelar";
    botaoCancelar.classList.add(
      "mt-1",
      "ml-5",
      "bg-white",
      "text-red-500",
      "px-3",
      "py-1",
      "rounded",
      "hover:text-white",
      "hover:bg-red-700",
      "transition",
      "duration-200",
      "flex",   // Adicionando flexbox para o botão ficar alinhado
      "justify-center",
      "items-center"
    );
    botaoCancelar.onclick = () => {
      if (window.confirm("Tem certeza que deseja cancelar esta reserva?")) {
        this.controladoraReserva.cancelarReserva(id);
      }
    }; // Adiciona a confirmação de cancelamento
    return botaoCancelar;
  }

  criarCelula(texto: string, ...classes: string[]): HTMLTableCellElement {
    const td = document.createElement("td");
    td.innerText = texto;
    td.classList.add(...classes); // Adicionando as classes separadas por vírgula
    return td;
  }
}
