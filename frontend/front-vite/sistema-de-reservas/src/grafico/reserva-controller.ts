import { ReservaModel } from "./reserva.ts";
import { ReservaView } from "./reserva-visao.ts";
import { exibirErro } from '../infra/exibir-erro.ts';

export class ReservaController {
  constructor(private view: ReservaView) {}

  async carregarReservas(dataInicio: Date, dataFim: Date): Promise<void> {
    try {
      const dataInicialFormatada = this.formatarData(dataInicio);
      const dataFinalFormatada = this.formatarData(dataFim);
  
      const reservas = await ReservaModel.obterReservas(dataInicialFormatada, dataFinalFormatada);
      console.log('Reservas carregadas:', reservas); // Logando as reservas
  
      const labels = Array.from(new Set(reservas.map((r) => this.formatarDataParaGrafico(r.data))));
      const valores = labels.map((label) => reservas.filter((r) => this.formatarDataParaGrafico(r.data) === label).length);
  
      this.view.criarGrafico(labels, valores);
    } catch (error) {
      exibirErro('Erro ao carregar reservas', error);
    }
  }
  

  private formatarData(data: Date): string {
    const ano = data.getFullYear();
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const dia = String(data.getDate()).padStart(2, '0');
    return `${ano}-${mes}-${dia}`;
  }

  private formatarDataParaGrafico(data: string): string {
    const [, mes, dia] = data.split('-');
    return `${dia}/${mes}`;
  }
}
