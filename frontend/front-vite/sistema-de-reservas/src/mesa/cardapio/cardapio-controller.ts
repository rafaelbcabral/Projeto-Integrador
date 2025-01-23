// // import { GestorCardapio } from "./cardapio-gestor.ts";
// import { CardapioVisao } from "./cardapio-visao.ts";
// import { ConsumoMesa, ItemCardapio } from "./cardapio.ts";

// export class CardapioController {
//   private gestorCardapio: GestorCardapio;
//   private visao: CardapioVisao;
//   private consumosPorMesa: Map<string, ConsumoMesa>;

//   constructor() {
//     this.gestorCardapio = new GestorCardapio();
//     this.visao = new CardapioVisao();
//     this.consumosPorMesa = new Map();
//     this.init();
//   }

//   private async init(): Promise<void> {
//     try {
//       const itensCardapio = await this.gestorCardapio.listarItensCardapio();
//       this.visao.renderizarCardapio(itensCardapio, this.adicionarItemParaMesa.bind(this));
//     } catch (error) {
//       console.error(error);
//       alert("Erro ao carregar o cardápio.");
//     }

//     document.getElementById("confirmarLancamento")?.addEventListener("click", async () => {
//       await this.confirmarLancamento();
//     });
//   }

//   private adicionarItemParaMesa(item: ItemCardapio): void {
//     const mesa = (document.getElementById("mesa") as HTMLSelectElement).value;
//     if (!mesa) {
//       alert("Selecione uma mesa antes de adicionar itens.");
//       return;
//     }

//     let consumoMesa: ConsumoMesa;
//     if (!this.consumosPorMesa.has(mesa)) {
//       consumoMesa = new ConsumoMesa(mesa);
//       this.consumosPorMesa.set(mesa, consumoMesa);
//     } else {
//       consumoMesa = this.consumosPorMesa.get(mesa)!;
//     }

//     consumoMesa.adicionarItem(item);
//     localStorage.setItem(`mesa_${mesa}`, JSON.stringify(consumoMesa));
//     this.visao.atualizarResumo(consumoMesa);
//   }

//   private async confirmarLancamento(): Promise<void> {
//     const mesa = (document.getElementById("mesa") as HTMLSelectElement).value;
//     if (!mesa) {
//       alert("Selecione uma mesa válida com itens lançados.");
//       return;
//     }

//     const consumoMesaData = localStorage.getItem(`mesa_${mesa}`);
//     if (!consumoMesaData) {
//       alert("Nenhum item foi lançado para esta mesa.");
//       return;
//     }

//     const consumoMesa: ConsumoMesa = JSON.parse(consumoMesaData);

//     try {
//       await this.gestorCardapio.salvarConsumoMesa(consumoMesa);
//       alert(`Lançamento para a mesa ${mesa} confirmado com sucesso!`);

//       this.consumosPorMesa.delete(mesa);
//       localStorage.removeItem(`mesa_${mesa}`);
//       this.visao.atualizarResumo(null);
//     } catch (error) {
//       console.error(error);
//       alert("Erro ao salvar consumo no backend.");
//     }
//   }
// }