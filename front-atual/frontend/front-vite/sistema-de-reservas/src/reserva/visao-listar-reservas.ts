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
    tr.append(
      this.criarCelula(reserva.id),
      this.criarCelula(reserva.nomeCliente),
      this.criarCelula(reserva.mesa),
      this.criarCelula(reserva.data),
      this.criarCelula(reserva.horaInicial),
      this.criarCelula(reserva.horaTermino),
      this.criarCelula(reserva.nomeFuncionario),
      this.criarCelula(reserva.status),
      this.criarBotaoCancelar(reserva.id)
    );
    return tr;
  }

  criarBotaoCancelar(id: string): HTMLButtonElement {
    const botaoCancelar = document.createElement("button");
    botaoCancelar.innerText = "Cancelar";
    botaoCancelar.classList.add("btn", "btn-cancel");
    botaoCancelar.onclick = () => {
      if (window.confirm("Tem certeza que deseja cancelar esta reserva?")) {
        this.controladoraReserva.cancelarReserva(id);
      }
    }; // Adiciona a confirmação de cancelamento
    return botaoCancelar;
  }

  criarCelula(texto: string): HTMLTableCellElement {
    const td = document.createElement("td");
    td.innerText = texto;
    return td;
  }
}
