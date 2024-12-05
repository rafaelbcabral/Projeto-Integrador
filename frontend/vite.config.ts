import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: '../dist', // Defina o diretório de saída
    rollupOptions: {
      input: '/src/main.ts', // Defina explicitamente o ponto de entrada
    },
  },
  server: {
    port: 5173,
    open: true, // Abre o navegador automaticamente
  },
});
