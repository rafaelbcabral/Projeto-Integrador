// export class CardapioVisao {
//   renderizarCardapio(itens: ItemCardapio[], adicionarItemCallback: (item: ItemCardapio) => void): void {
//     const container = document.getElementById("cardapioContainer");
//     if (!container) return;

//     container.innerHTML = "";

//     itens.forEach((item) => {
//       const itemElemento = document.createElement("div");
//       itemElemento.classList.add("p-4", "border", "rounded", "shadow", "flex", "justify-between", "items-center");

//       itemElemento.innerHTML = `
//         <div>
//           <p class=\"font-bold\">${item.descricao}</p>
//           <p>Categoria: ${item.categoria}</p>
//           <p>Preço: R$ ${item.preco.toFixed(2)}</p>
//         </div>
//         <button class=\"bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600\">Adicionar</button>
//       `;

//       itemElemento.querySelector("button")?.addEventListener("click", () => {
//         adicionarItemCallback(item);
//       });

//       container.appendChild(itemElemento);
//     });
//   }

//   atualizarResumo(consumoMesa: ConsumoMesa | null): void {
//     if (!consumoMesa) {
//       console.log("Nenhum item selecionado.");
//       return;
//     }
//     console.log(`Resumo da Mesa ${consumoMesa.mesa}:`, consumoMesa.itens);
//     // Você pode implementar exibição visual aqui caso necessário
//   }
// }