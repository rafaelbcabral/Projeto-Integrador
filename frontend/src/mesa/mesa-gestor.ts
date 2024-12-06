import { fazerRequisicao } from '../utills/api-utils.js';
import { Mesa } from './mesa-model.js';

export async function buscarMesasDisponiveis(data: string, horario: string): Promise<Mesa[]> {
  const mesas = await fazerRequisicao(`/mesas-disponiveis?data=${data}&horario=${horario}`, 'GET');
  return mesas.map((mesa: any) => new Mesa(mesa.id, mesa.status));
}
