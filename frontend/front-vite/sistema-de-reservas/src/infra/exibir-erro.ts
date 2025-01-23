import { showToast } from './toastify.ts';

export function exibirErro(mensagem: string, error: any): void {
  const mensagemErro = error instanceof Error ? error.message : 'Erro desconhecido';
  showToast(`${mensagem}: ${mensagemErro}`, 'erro');
}
