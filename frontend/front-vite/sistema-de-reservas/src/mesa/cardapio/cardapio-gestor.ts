// import { url } from '../../infra/url';


// export class GestorCardapio {
//   async listarItensCardapio(): Promise<ItemCardapio[]> {
//     const response = await fetch(`${url}/cardapio`);
//     if (!response.ok) {
//       throw new Error("Erro ao consultar o cardápio");
//     }
//     const itens = await response.json();
//     return itens.map(
//       (item: any) => new ItemCardapio(item.codigo, item.descricao, item.preco, item.categoria)
//     );
//   }

//   async salvarConsumoMesa(consumoMesa: ConsumoMesa): Promise<any> {
//     const response = await fetch(`${url}/consumos`, {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//       },
//       body: JSON.stringify(consumoMesa),
//     });

//     if (!response.ok) {
//       throw new Error("Erro ao salvar consumo da mesa");
//     }

//     return await response.json();
//   }
// }