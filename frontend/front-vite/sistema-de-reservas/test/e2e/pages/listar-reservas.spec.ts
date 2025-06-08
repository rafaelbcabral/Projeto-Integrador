import { test, expect } from '@playwright/test';

test.describe('Página de Reservas', () => {
  
  test('Verificar se a página de reservas carrega corretamente', async ({ page }) => {
    // Acessar a página de reservas
    await page.goto('http://localhost:5173/login');

    // Preencher o formulário de login
    await page.fill('input[id="usuario"]', 'joao.silva');
    await page.fill('input[id="senha"]', 'senha123');

    // Submeter o formulário
    await page.click('button[type="submit"]:visible');

    await expect(page).toHaveURL('http://localhost:5173/home');
    
    await page.goto('http://localhost:5173/listar-reservas');

    
    await page.reload();
    await page.locator('h1.text-3xl').waitFor({ state: 'attached', timeout: 10000 });
    await expect(page.locator('h1.text-3xl')).toHaveText('Sistema de Reservas');
    

    
    const botaoCadastrarReserva = page.locator('a:has-text("Cadastrar Reserva")');
    const botaoMenuInicial = page.locator('a.btn.bg-gray-500'); 
    const botaoVerReservas = page.locator('a.btn.bg-green-700');
    
    await expect(botaoCadastrarReserva).toBeVisible();
    await expect(botaoMenuInicial).toBeVisible();
    await expect(botaoVerReservas).toBeVisible();

    
    await page.reload();
    const tabela = page.locator('table');
    await expect(tabela).toBeVisible();

    
    const colunas = [
      'Cliente',
      'Mesa',
      'Data',
      'Hora Inicial',
      'Hora Término',
      'Funcionário',
      'Status',
      'Cancelamento'
    ];

    for (const coluna of colunas) {
      const colunaLocator = page.locator(`th:text("${coluna}")`);
       await expect(colunaLocator).toBeVisible();
    }
  });

  test('Verificar se a tabela de reservas contém dados', async ({ page }) => {
    // Acessar a página de reservas

    await page.goto('http://localhost:5173/login'); 

    // Preencher o formulário de login
    await page.fill('input[id="usuario"]', 'joao.silva'); 
    await page.fill('input[id="senha"]', 'senha123'); 

    // Submeter o formulário
    await page.click('button[type="submit"]:visible');  

    await expect(page).toHaveURL('http://localhost:5173/home');  

    await page.goto('http://localhost:5173/listar-reservas'); 


  const tabela = page.locator('tbody');
  await tabela.waitFor({ state: 'visible', timeout: 10000 });  

  // Contar as linhas da tabela
  const linhasTabela = page.locator('tbody tr');
  const numeroDeLinhas = await linhasTabela.count();

  // Verificar se o número de linhas é maior que 0
  await expect(numeroDeLinhas).toBeGreaterThan(0);
  // console.log(numeroDeLinhas)
  });

  test('Verificar a funcionalidade dos botões de navegação', async ({ page }) => {
    // Acessar a página de reservas
    await page.goto('http://localhost:5173/login'); 

    // Preencher o formulário de login
    await page.fill('input[id="usuario"]', 'joao.silva'); 
    await page.fill('input[id="senha"]', 'senha123'); 

    // Submeter o formulário
    await page.click('button[type="submit"]:visible');  

    await expect(page).toHaveURL('http://localhost:5173/home');
    
    await page.goto('http://localhost:5173/listar-reservas'); 

    // Testar o botão "Cadastrar Reserva"
    const botaoCadastrarReserva = page.locator('a:has-text("Cadastrar Reserva")');
    await botaoCadastrarReserva.click();
    await expect(page).toHaveURL('http://localhost:5173/fazer-reservas'); 

    // Voltar para a página de reservas
    await page.goBack();
    
    // Testar o botão "Menu Inicial"
    const botaoMenuInicial = page.locator('a.btn.bg-gray-500'); 
    await botaoMenuInicial.click();
    await expect(page).toHaveURL('http://localhost:5173/home'); 

    // Voltar para a página de reservas
    await page.goBack();

    // Testar o botão "Ver Reservas"
    const botaoVerReservas = page.locator('a.btn.bg-green-700');
    await botaoVerReservas.click();
    await expect(page).toHaveURL('http://localhost:5173/listar-reservas'); 
  });

});
