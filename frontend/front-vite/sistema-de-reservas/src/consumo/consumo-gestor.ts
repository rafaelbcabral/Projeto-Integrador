import { url } from "../infra/url";
import { ItemCarrinho } from "./itemCarrinho";

export class ConsumoGestor {
  private apiBaseUrl = url; // URL base da API
  private localStorageKey = 'carrinho';

  private salvarCarrinho(itens: ItemCarrinho[]): void {
    localStorage.setItem(this.localStorageKey, JSON.stringify(itens));
  }

  private obterCarrinho(): ItemCarrinho[] {
    const itens = localStorage.getItem(this.localStorageKey);
    return itens ? JSON.parse(itens) : [];
  }

  async obterMesasReservadas(data: string, horario: string): Promise<number[]> {
    try {
      const response = await fetch(`${this.apiBaseUrl}/consumo/reservaId?data=${data}&horario=${horario}`, {
        credentials: 'include',
      });
  
      if (!response.ok) {
        throw new Error('Erro ao obter mesas reservadas');
      }
  
      const reservas = await response.json();
      return reservas.map((reserva: any) => reserva.mesa); // Retorna apenas as mesas reservadas
    } catch (error) {
      console.error("Erro ao obter mesas reservadas:", error);
      throw error;
    }
  }
  async adicionarItemCarrinho(item: ItemCarrinho): Promise<ItemCarrinho[]> {
    const carrinho = this.obterCarrinho();
    const itemExistente = carrinho.find(i => i.id === item.id);

    if (itemExistente) {
      // Atualizar a quantidade do item existente
      itemExistente.quantidade += item.quantidade;
    } else {
      // Adicionar novo item ao carrinho
      carrinho.push(item);
    }

    this.salvarCarrinho(carrinho); // Salva o carrinho atualizado no LocalStorage
    return carrinho;
  }

  async listarItensCarrinho(): Promise<ItemCarrinho[]> {
    return this.obterCarrinho(); // Retorna os itens armazenados localmente
  }

  async listarItensDoServidor(): Promise<ItemCarrinho[]> {
    const response = await fetch(`${this.apiBaseUrl}/itens`, {credentials: 'include'});
    if (!response.ok) {
      throw new Error('Erro ao listar itens do servidor');
    }
    return await response.json();
  }

  async enviarCarrinhoParaServidor(mesaId: string, funcionarioId: string): Promise<void> {
    const carrinho = this.obterCarrinho();  // Pega os itens do carrinho
    console.log("teste")
    const itens = carrinho.map(item => ({
      id: item.id, // array de objetos no ID
      quantidade: item.quantidade,  // Quantidade do item
    }));
  
    const payload = {
      reserva: mesaId,  // Passa a mesaId como reserva
      funcionario: funcionarioId,  // ID do funcionário
      itens,  // Array de itens
    };
  
    // Verifique se o payload está sendo gerado corretamente
    console.log("Payload para envio:", JSON.stringify(payload));
  
    const response = await fetch(`${this.apiBaseUrl}/consumos`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload)  // Envia o objeto com 'reserva', 'funcionario' e 'itens'
    });
  
    console.log("Resposta da requisição:", response);
  
  
  
  
  
    if (!response.ok) {
      throw new Error('Erro ao enviar carrinho para o servidor');
    }
  
    this.salvarCarrinho([]);  // Limpa o carrinho local após envio bem-sucedido
  }

  async obterReservas(): Promise<any[]> {
    const response = await fetch('http://localhost:8000/reservas');  // Altere a URL conforme a sua API
    if (response.ok) {
      return await response.json();  // Retorna os dados das reservas
    } else {
      throw new Error('Falha ao obter as reservas da API');
    }
  }

}
