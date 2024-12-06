import { fazerRequisicao } from '../utills/api-utils.js';
import { Relatorio } from './relatorios-model.js';

export async function gerarRelatorioReservas(inicio: string, fim: string): Promise<Relatorio[]> {
  const relatorio = await fazerRequisicao(`/relatorios?inicio=${inicio}&fim=${fim}`, 'GET');
  return relatorio.map((rel: any) => new Relatorio(rel.data, rel.qtdReservas));
}
