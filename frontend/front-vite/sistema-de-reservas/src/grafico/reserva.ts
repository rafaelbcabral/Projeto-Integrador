import { url } from "../infra/url.ts";
import { exibirErro } from '../infra/exibir-erro.ts';
import { showToast } from '../infra/toastify.ts';

export type ReservaListar = {
  data: string;
};

export class ReservaModel {
  static async obterReservas(dataInicio: string, dataFim: string): Promise<ReservaListar[]> {
    const endpoint = `${url}/periodo?dataInicial=${dataInicio}&dataFinal=${dataFim}`;
    const resposta = await fetch(endpoint);

    if (!resposta.ok) {
      exibirErro('Erro ao buscar reservas: ', resposta.statusText);
    }

    const reservas = await resposta.json();
    if (!Array.isArray(reservas)) {
      showToast('Resposta inesperada do servidor. Esperado um array.', 'erro');
    }

    return reservas;
  }
}
