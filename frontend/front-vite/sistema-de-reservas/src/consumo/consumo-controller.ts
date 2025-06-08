import { ConsumoGestor } from './consumo-gestor';
import { ConsumoView } from './consumo-view';
import { ItemCarrinho } from './itemCarrinho';


export class ConsumoController {
   gestor: ConsumoGestor;
   view: ConsumoView;
 
  constructor(gestor: ConsumoGestor, view: ConsumoView) {
    this.gestor = gestor;
    this.view = view;
  }

  async carregarItensCarrinho(): Promise<ItemCarrinho[]> {
    try {
      const itens = await this.gestor.listarItensCarrinho();
      this.view.mostrarItensCarrinho(itens); // Atualiza a view
      return itens; // Retorna os itens para quem chamar
    } catch (error: any) {
      this.view.mostrarErro(error.message);
      throw error; // Lança o erro para ser tratado pelo chamador
    }
  }

  async carregarMesas() {
    try {
      const reservas = await this.gestor.obterReservas();  // Obter as reservas da API
      const mesas = reservas.map((reserva: any) => reserva.mesa);  // Extrair as mesas das reservas
      this.view.exibirMesas(mesas);  // Passar as mesas para a view exibir
    } catch (error) {
      console.error('Erro ao carregar mesas:', error);
    }
  }

  async carregarItensDoServidor(): Promise<ItemCarrinho[]> {
    try {
      console.log("Carregando itens do servidor...");
      const itens = await this.gestor.listarItensDoServidor();
      console.log(itens)
      return itens;
    } catch (error: any) {
      this.view.mostrarErro(error.message);
      throw error;
    }
  }

  async finalizarCarrinho(mesaId: string): Promise<void> {
    try {
      const funcionarioId = String(sessionStorage.getItem('idFuncionario'));

      // Envia o carrinho para o servidor, passando os IDs necessários
      console.log(mesaId, funcionarioId)
      await this.gestor.enviarCarrinhoParaServidor(mesaId, funcionarioId);
      this.view.mostrarMensagem('Carrinho enviado com sucesso!');
    } catch (error: any) {
      this.view.mostrarErro(error.message);
    }
  }
  
  async finalizarCompra(mesaId: string) {
    try {
      console.log(mesaId)
      await this.finalizarCarrinho(mesaId);
      // Aqui você pode adicionar alguma lógica de finalização da compra, caso necessário
    } catch (error) {
      this.view.mostrarErro('Erro ao finalizar compra.');
    }
  }
  async adicionarItemAoCarrinho(item: ItemCarrinho): Promise<ItemCarrinho[]> {
    try {
      // Adiciona o item ao carrinho e retorna a lista atualizada de itens
      const itensAtualizados = await this.gestor.adicionarItemCarrinho(item);
      console.log("item adicionado no carrinho" + itensAtualizados);
      return itensAtualizados;
    } catch (error: any) {
      throw error; // Lança o erro para ser tratado onde for chamado
    }
  }
}

