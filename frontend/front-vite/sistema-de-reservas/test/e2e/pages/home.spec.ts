import { test, expect } from '@playwright/test';

test.describe('Página Home Admin - Testes E2E', () => {
  test('Deve carregar o título e elementos principais após login', async ({ page }) => {
    // Abrir a página de login
    await page.goto('http://localhost:5173/login'); 

    // Preencher o formulário de login
    await page.fill('input[id="usuario"]', 'joao.silva'); 
    await page.fill('input[id="senha"]', 'senha123'); 

    // Submeter o formulário
    await page.click('button[type="submit"]'); 

    // Aguardar a navegação após o login
    await expect(page).toHaveURL('http://localhost:5173/home'); 

    // Verificar se o título da página carrega corretamente
    const h2Locator = page.locator('h2');
    await expect(h2Locator).toBeVisible();
    await expect(h2Locator).toHaveText('La Saveur | Home Admin');

    // Verificar o botão de logout
    const logoutButton = page.locator('button', { hasText: 'Sair' });
    await expect(logoutButton).toBeVisible();

    // Navegar para "Cadastrar Reserva"
    const cadastrarReservaLink = page.locator('a.btn[href="/fazer-reservas"]');
    await expect(cadastrarReservaLink).toBeVisible();
    await cadastrarReservaLink.click();
    await expect(page).toHaveURL('http://localhost:5173/fazer-reservas');

    // Voltar e navegar para "Listagem de Reservas"
    await page.goBack();
    const listarReservasLink = page.locator('a.btn[href="/listar-reservas"]');
    await expect(listarReservasLink).toBeVisible();
    await listarReservasLink.click();
    await expect(page).toHaveURL('http://localhost:5173/listar-reservas');

    // Voltar e navegar para "Consumo de Mesas"
    await page.goBack();
    const consumoMesasLink = page.locator('a.btn[href="/home"]');
    await expect(consumoMesasLink).toBeVisible();
    await consumoMesasLink.click();
    await expect(page).toHaveURL('http://localhost:5173/home');
    page.reload();
    // Verificar a tabela de reservas
    const tableLocator = page.locator('table');
    await expect(tableLocator).toBeVisible();
    const tableHeaders = page.locator('table thead tr th');
    await expect(tableHeaders).toHaveCount(8); // Número de colunas na tabela

    const cancelarButton = page.locator('button:has-text("Cancelar")').first();


    await expect(cancelarButton).toBeVisible();
    
    // Escutar o evento de alerta
    page.on('dialog', async (dialog) => {
      await dialog.accept(); // Aceitar o alerta (clicar em "OK")
    });
    
    // Clicar no botão de cancelar
    await cancelarButton.click();
    
    // Verificar se o status na tabela foi alterado para "cancelada" após o reload
    const statusCancelado = page.locator('td:has-text("cancelada")').first();
    await expect(statusCancelado).toBeVisible();
    

    // Preencher o formulário de relatório
    await page.fill('input#dataInicio', '2023-01-01');
    await page.fill('input#dataFim', '2023-12-31');
    await page.click('button[type="submit"]');

    // Verificar se o gráfico está presente
    await expect(page.locator('canvas#myChart')).toBeVisible();
  });
});
