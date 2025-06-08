import { Mesa } from "./mesa";
import { exibirErro } from "../infra/exibir-erro.ts";

export class VisaoMesas {
  exibirMesas(mesas: Mesa[]) {
    try {
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
    } catch (error) {
      // Chama a função exibirErro em caso de erro
      exibirErro("Erro ao exibir mesas", error);
    }
  }

  //

  
}
