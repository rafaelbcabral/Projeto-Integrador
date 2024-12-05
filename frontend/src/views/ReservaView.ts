export class ReservaView {
  private rootElement: HTMLElement;

  constructor(rootElement: HTMLElement) {
    this.rootElement = rootElement;
  }

  renderForm() {
    this.rootElement.innerHTML = `
      <h2>Fazer Reserva</h2>
      <form id="formReserva">
        <label for="nomeFuncionario">Funcionário:</label>
        <input type="text" id="nomeFuncionario" required><br>

        <label for="nomeCliente">Cliente:</label>
        <input type="text" id="nomeCliente" required><br>

        <label for="dataHora">Data e Hora:</label>
        <input type="datetime-local" id="dataHora" required><br>

        <label for="mesa">Mesa:</label>
        <select id="mesa">
          <option value="1">Mesa 1</option>
          <option value="2">Mesa 2</option>
          <option value="3">Mesa 3</option>
          <option value="4">Mesa 4</option>
          <option value="5">Mesa 5</option>
          <option value="6">Mesa 6</option>
          <option value="7">Mesa 7</option>
          <option value="8">Mesa 8</option>
          <option value="9">Mesa 9</option>
          <option value="10">Mesa 10</option>
        </select><br>

        <button type="submit">Reservar</button>
      </form>
    `;
  }

  renderReservas(reservas: any[]) {
    const reservasList = document.createElement('ul');
    reservas.forEach(reserva => {
      const listItem = document.createElement('li');
      listItem.innerHTML = `
        <strong>${reserva.nomeCliente}</strong> reservou a <strong>Mesa ${reserva.mesa}</strong> para o horário ${reserva.dataHora}.
        <button class="cancelar" data-id="${reserva.id}">Cancelar</button>
      `;
      reservasList.appendChild(listItem);
    });

    this.rootElement.appendChild(reservasList);
  }

  showError(message: string) {
    alert(message);
  }

  bindFormReserva(handler: Function) {
    const form = document.getElementById('formReserva') as HTMLFormElement;
    form.addEventListener('submit', event => {
      event.preventDefault();

      const nomeFuncionario = (document.getElementById('nomeFuncionario') as HTMLInputElement).value;
      const nomeCliente = (document.getElementById('nomeCliente') as HTMLInputElement).value;
      const dataHora = (document.getElementById('dataHora') as HTMLInputElement).value;
      const mesa = parseInt((document.getElementById('mesa') as HTMLSelectElement).value, 10);

      handler({ nomeFuncionario, nomeCliente, dataHora, mesa });
    });
  }

  bindCancelarReserva(handler: Function) {
    this.rootElement.addEventListener('click', event => {
      if ((event.target as HTMLElement).classList.contains('cancelar')) {
        const id = (event.target as HTMLElement).dataset.id;
        if (id) {
          handler(parseInt(id, 10));
        }
      }
    });
  }
}
