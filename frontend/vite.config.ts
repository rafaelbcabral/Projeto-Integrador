import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        globals: true, // Habilita funções globais como describe, it, etc.
        environment: 'jsdom', // Configura o ambiente de teste
        coverage: {
            provider: 'v8', // Define o provedor de cobertura
            reportsDirectory: './coverage', // Diretório onde os relatórios serão salvos
            reporter: ['text', 'html'], // Gera relatórios no console (text) e um visual (html)
            all: true, // Inclui arquivos não testados no relatório
            exclude: ['node_modules', 'tests'], // Exclui arquivos/pastas específicos
        },
    },
});
