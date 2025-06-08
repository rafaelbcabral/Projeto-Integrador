import { test, expect } from '@playwright/test';


test.describe('Teste de Login', () => {
  test('Deve realizar o login corretamente', async ({ page }) => {
    
    await page.goto('http://localhost:5173/login');
    
    const titulo = await page.locator('h1:text("Faça o seu login!")');
    await expect(titulo).toHaveText('Faça o seu login!');
    

    // Preencher o campo de usuário
    await page.fill('input#usuario', 'joao.silva');

    // Preencher o campo de senha
    await page.fill('input#senha', 'senha123');

    // Clicar no botão de login
    await page.click('button[type="submit"]');

    await page.goto('http://localhost:5173/home');
  });

  test('Deve exibir mensagem de erro para login inválido', async ({ page }) => {
    // Acessar a página de login
    await page.goto('http://localhost:5173');


    await page.fill('input#usuario', 'usuario_invalido');


    await page.fill('input#senha', 'senha_invalida');


    await page.click('button[type="submit"]');

    const erroMensagem = page.locator('.toastify');
    await expect(erroMensagem).toContainText('Erro ao fazer login: Erro desconhecido');
    await page.goto('http://localhost:5173/login');
  });
});
