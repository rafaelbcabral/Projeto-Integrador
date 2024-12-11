import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    globals: true,
    environment: 'jsdom',
    coverage: {
      provider: 'v8',  // Usando V8 como provider de cobertura
      reporter: ['text', 'json', 'html'],
      reportsDirectory: './coverage',
    },
    exclude: ['**/e2e/**'],
  },
});
