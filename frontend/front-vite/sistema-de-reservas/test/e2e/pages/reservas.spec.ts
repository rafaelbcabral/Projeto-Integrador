import { test, expect } from '@playwright/test';

test('Testar a página de listar reservas', async ({ page }) => {
  // Acesse a página de listar reservas
  await page.goto('http://localhost:5173/src/pages/listar-reservas.html'); 
  await page.waitForTimeout(10000);

  // Verifique se a tabela de reservas está presente
  const table = await page.locator('table');
  await expect(table).toBeVisible();

  // Verifique se as colunas estão corretas
  const headers = await table.locator('thead th');
  await expect(headers.nth(0)).toHaveText('ID');
  await expect(headers.nth(1)).toHaveText('Cliente');
  await expect(headers.nth(2)).toHaveText('Mesa');
  await expect(headers.nth(3)).toHaveText('Data');
  await expect(headers.nth(4)).toHaveText('Hora Inicial');
  await expect(headers.nth(5)).toHaveText('Hora Término');
  await expect(headers.nth(6)).toHaveText('Funcionário');
  await expect(headers.nth(7)).toHaveText('Status');
  await expect(headers.nth(8)).toHaveText('Cancelamento');
});
test('Testar a página de realizar reserva', async ({ page }) => {
  
  await page.goto('http://localhost:5173/src/pages/realizar-reserva.html');
  
  
  const nomeField = page.locator('#nome');
  await expect(nomeField).toBeVisible();

  
  await page.fill('#nome', 'Carlos Silva');
  console.log('Campo #nome preenchido');

  
  await page.fill('#data', '2025-12-15');
  await expect(page.locator('#data')).toHaveValue('2025-12-15');
  console.log('Campo #data preenchido');

  
  await page.selectOption('#horarioInicial', '11:00');
  await expect(page.locator('#horarioInicial')).toHaveValue('11:00:00');
  console.log('Opção de horário selecionada');

  
  const mesaSelect = page.locator('#mesa');
  await mesaSelect.waitFor({ state: 'attached', timeout: 15000 });
  await expect(mesaSelect).toBeVisible();
  await expect(mesaSelect).toBeEnabled();

  await page.waitForFunction(() => {
    const selectElement = document.querySelector('#mesa') as HTMLSelectElement; 
    return selectElement && selectElement.options.length > 1; 
  }, { timeout: 15000 });

  const mesaOptionsCount = await page.locator('#mesa option').count();
  console.log(`Quantidade de opções disponíveis no campo #mesa: ${mesaOptionsCount}`);

  await page.selectOption('#mesa', '1');
  console.log('Campo #mesa está anexado, visível e uma opção foi selecionada');

  const funcionarioSelect = page.locator('#funcionario');
  await funcionarioSelect.waitFor({ state: 'attached', timeout: 15000 });
  await expect(funcionarioSelect).toBeVisible();
  await expect(funcionarioSelect).toBeEnabled();
  await page.selectOption('#funcionario', '1');
  console.log('Campo #funcionario está anexado, visível e uma opção foi selecionada');

  await page.click('button[type="submit"]');
  console.log('Formulário enviado');
});









