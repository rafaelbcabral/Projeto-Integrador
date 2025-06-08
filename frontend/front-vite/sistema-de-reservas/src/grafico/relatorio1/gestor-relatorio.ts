type ReservaListar = {
  data: string;
};


export class GestorReservas {
  
  obterDatasMesAtual(): { primeiroDia: Date; ultimoDia: Date } {
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = hoje.getMonth(); // (0-11)
  
    const primeiroDia = new Date(ano, mes, 1); // primeiro dia do mês
    const ultimoDia = new Date(ano, mes + 1, 0); // último dia do mês
  
    return { primeiroDia, ultimoDia };
  }

  async obterDados(dataInicio: Date, dataFim: Date): Promise<{ labels: string[], valores: number[] }> {
    const dataInicial = this.formatarData(dataInicio); // Formata a data de início
    const dataFinal = this.formatarData(dataFim); // Formata a data de fim

    const endpoint = `http://localhost:8000/periodo?dataInicial=${dataInicial}&dataFinal=${dataFinal}`;

    try {
      const res = await fetch(endpoint);
      const data: ReservaListar[] = await res.json(); // Espera pela conversão para JSON

      if (!Array.isArray(data)) { // Verificando se a resposta é um array de objetos
        return { labels: [], valores: [] };
      }
      
      const labelsX = Array.from(new Set(data.map((reserva) => {
        const date = new Date(reserva.data); // Converte a string para Date
        return this.formatarDataParaGrafico(this.formatarData(date)); // Passa o objeto Date para o formatador
      })));
      

      // Contabiliza as reservas por data
      const valores: number[] = Array(labelsX.length).fill(0);
      data.forEach((reserva) => {
        const dataReserva = reserva.data;
        const dataFormatada = this.formatarDataParaGrafico(dataReserva);
        const index = labelsX.indexOf(dataFormatada);
        if (index !== -1) {
          valores[index] += 1;
        }
      });

      return { labels: labelsX, valores: valores };
    } catch (erro) {
      console.error("Erro ao obter dados: " + erro);
      return { labels: [], valores: [] };
    }
  }

  formatarData(data: Date): string {
    const ano = data.getFullYear();
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const dia = String(data.getDate()).padStart(2, '0');
    return `${ano}-${mes}-${dia}`;
  }

  formatarDataParaGrafico(data: string): string {
    const [, mes, dia] = data.split('-'); // Quebra a data no formato 'YYYY-MM-DD'
    return `${dia}/${mes}`; // Retorna a data no formato 'DD/MM'
  }
}
