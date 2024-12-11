// playwright.config.ts
import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: 'test/e2e',  // Pasta onde os testes E2E estão
  use: {
    browserName: 'chromium', // Usando Chromium como exemplo
    headless: true, // Para rodar em modo headless
    screenshot: 'only-on-failure', // Tirar screenshot apenas quando falhar
  },
});
