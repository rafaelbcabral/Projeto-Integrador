import { Mesa } from "./mesa";

export class VisaoMesas {
  exibirMesas(mesas: Mesa[]) {
    const mesaSelect = document.getElementById("mesa") as HTMLSelectElement;
    mesaSelect.innerHTML = '<option value="">Selecione a mesa</option>';

    if (mesas && mesas.length > 0) {
      mesas.forEach((mesa) => {
        const option = document.createElement("option");
        option.value = mesa.id;
        option.textContent = `Mesa ${mesa.id}`;
        mesaSelect.appendChild(option);
      });
    } else {
      const option = document.createElement("option");
      option.value = "";
      option.textContent = "Nenhuma mesa disponível";
      mesaSelect.appendChild(option);
    }
  }
}
