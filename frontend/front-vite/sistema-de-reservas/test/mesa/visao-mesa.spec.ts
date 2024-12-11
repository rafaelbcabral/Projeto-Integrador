import { vi, test, expect } from 'vitest';
import { VisaoMesas } from '../../src/mesa/visao-mesa';
import { Mesa } from '../../src/mesa/mesa';

// Mock do document
globalThis.document = {
  getElementById: vi.fn().mockReturnValue({
    innerHTML: '',
    appendChild: vi.fn()
  }),
  createElement: vi.fn().mockImplementation((tagName) => {
    return {
      tagName,
      value: '',
      textContent: '',
      appendChild: vi.fn()
    };
  }),
  URL: 'http://mocked-url.com',
  location: {
    href: 'http://mocked-url.com'
  },
  title: 'Mocked Title',
} as unknown as Document;


test('Deve exibir mesas corretamente', () => {
  const visaoMesas = new VisaoMesas();
  const mesas: Mesa[] = [{ id: '1', capacidade: 4 }];
  
  visaoMesas.exibirMesas(mesas);

  expect(document.getElementById).toHaveBeenCalled();
  expect(document.createElement).toHaveBeenCalled();
});
