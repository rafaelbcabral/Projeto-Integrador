// vite.config.ts
import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'dist', // Configuração do diretório de saída
    target: 'esnext',
  },
  server: {
    open: true, // Abre o navegador automaticamente
  },
  
});

