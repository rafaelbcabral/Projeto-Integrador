import { Mesa } from "./mesa";

export class GestorMesas {
  async consultarMesasDisponiveis(
    data: string,
    horarioInicial: string
  ): Promise<Mesa[]> {
    try {
      const response = await fetch(
        `http://localhost:8000/mesas-disponiveis?data=${data}&horarioInicial=${horarioInicial}`
      );
      if (!response.ok) {
        throw new Error("Erro ao consultar mesas disponíveis");
      }
      const mesas: Mesa[] = await response.json();
      return mesas;
    } catch (error) {
      console.error("Erro ao consultar mesas:", error);
      return [];
    }
  }
}
