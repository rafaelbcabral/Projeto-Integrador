import { Mesa } from './mesa';
import { ItemCarrinho } from './itemCarrinho';
export class ConsumoView {
    // Declarar o tipo corretamente para o select
    selectMesa: HTMLSelectElement | null;
  
    constructor() {
      // Agora a propriedade é corretamente tipada
      this.selectMesa = document.getElementById('mesa') as HTMLSelectElement | null;
    }
  
    exibirMesas(mesas: number[]) {
      if (this.selectMesa) {
        // Limpa as opções existentes
        this.selectMesa.innerHTML = '';
  
        // Adiciona uma opção de placeholder
        const option = document.createElement('option');
        option.text = 'Selecione uma Mesa';
        this.selectMesa.appendChild(option);
  
        // Adiciona as mesas disponíveis
        mesas.forEach(mesa => {
          const option = document.createElement('option');
          option.value = String(mesa);  // Valor da opção é a mesa
          option.text = `Mesa ${mesa}`;  // Texto da opção é "Mesa X"
          this.selectMesa?.appendChild(option);
        });
      } else {
        console.error("Elemento 'mesa' não encontrado.");
      }
    }
  
  

  mostrarMesasDisponiveis(mesas: Mesa[]): void {
    const mesaSelect = document.getElementById('mesa') as HTMLSelectElement;
    mesaSelect.innerHTML = ''; // Limpa as opções anteriores

    mesas.forEach((mesa) => {
      const option = document.createElement('option');
      option.value = mesa.id;
      option.textContent = `Mesa ${mesa.id}`;
      mesaSelect.appendChild(option);
    });
  }

  mostrarItensCarrinho(itens: ItemCarrinho[]): void {
    const carrinhoContainer = document.getElementById('carrinhoContainer')!;
    carrinhoContainer.innerHTML = ''; // Limpa os itens anteriores
  
    itens.forEach((item) => {
      const itemDiv = document.createElement('div');
      itemDiv.classList.add('flex', 'justify-between');
  
      const itemContent = document.createElement('span');
      itemContent.textContent = `${item.descricao}x ${item.categoria} - R$${item.preco}`;
      itemDiv.appendChild(itemContent);
  
      carrinhoContainer.appendChild(itemDiv);
    });
  }
  

  mostrarItensDoServidor(itens: ItemCarrinho[]): void {
    const itensServidorContainer = document.getElementById('itensServidorContainer')!;
  
    // Limpa os itens anteriores
    const tbody = itensServidorContainer.querySelector('tbody')!;
    tbody.innerHTML = '';
    itens.forEach((item) => {
      const tr = document.createElement('tr');
      tr.classList.add('text-center');
    
      const tdDescricao = document.createElement('td');
      tdDescricao.classList.add('px-4', 'py-2', 'border');
      tdDescricao.textContent = item.descricao;
      tr.appendChild(tdDescricao);
    
      const tdCategoria = document.createElement('td');
      tdCategoria.classList.add('px-4', 'py-2', 'border');
      tdCategoria.textContent = item.categoria;
      tr.appendChild(tdCategoria);
    
      const tdPreco = document.createElement('td');
      tdPreco.classList.add('px-4', 'py-2', 'border');
      tdPreco.textContent = `R$${item.preco}`;
      tr.appendChild(tdPreco);
    
      const tdAcoes = document.createElement('td');
      tdAcoes.classList.add('px-4', 'py-2', 'border');
    
      const addButton = document.createElement('button');
      addButton.textContent = 'Adicionar ao Carrinho';
      addButton.classList.add('bg-green-500', 'hover:bg-green-600', 'text-white', 'px-4', 'py-1', 'rounded');
    
      // Associando o objeto `item` ao botão usando um data-attribute
      addButton.dataset.item = JSON.stringify(item); // Salvando o objeto como uma string JSON
    
      addButton.addEventListener('click', (event) => {
        const button = event.currentTarget as HTMLButtonElement;
      
        // Recupera o objeto do item a partir do atributo data-item
        const itemFromButton = JSON.parse(button.dataset.item!); 
      
        // Dispara um evento personalizado com o item
        const customEvent = new CustomEvent('adicionarItem', { 
          detail: itemFromButton 
        });
      
        // Dispara o evento no elemento app ou no document
        document.dispatchEvent(customEvent);
      
        console.log('Evento disparado com o item:', itemFromButton);
      });
      
      tdAcoes.appendChild(addButton);
      tr.appendChild(tdAcoes);
      document.querySelector('tbody')?.appendChild(tr); // Supondo que você tenha uma tabela com <tbody>
    
      tdAcoes.appendChild(addButton);
      tr.appendChild(tdAcoes);
      document.querySelector('tbody')?.appendChild(tr); // Supondo que você tenha uma tabela com <tbody>
    });
    
  }
  
  

  mostrarErro(mensagem: string): void {
    console.error(`Erro: ${mensagem}`);
  }

  mostrarMensagem(mensagem: string): void {
    console.log(mensagem);
  }

  
}
