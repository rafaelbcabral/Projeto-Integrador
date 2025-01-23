import { url } from "../infra/url.ts";
import { exibirErro } from "../infra/exibir-erro.ts";
import { Mesa } from "./mesa.ts";

export class GestorMesas {
  async consultarMesasDisponiveis(
    data: string,
    horarioInicial: string
  ): Promise<Mesa[]> {
    try {
      const response = await fetch(
        `${url}/mesas-disponiveis?data=${data}&horarioInicial=${horarioInicial}`
      );
      if (!response.ok) {
        exibirErro('Erro ao consultar mesas disponíveis', response.status);
      }
      const mesas: Mesa[] = await response.json();
      return mesas;
    } catch (error) {
      exibirErro('Erro ao consultar mesas: ', error);
      return [];
    }
  }
}
