import { url } from "../infra/url.ts";
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
        throw new Error(`Erro na consulta das mesas. Status: ${response.status}`);
      }

      const mesas: Mesa[] = await response.json();
      return mesas;
    } catch (error) {

      throw new Error('Não foi possível obter as mesas disponíveis. Tente novamente mais tarde.');
    }
  }
}
