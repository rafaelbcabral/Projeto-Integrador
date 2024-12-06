const BASE_URL = 'http://localhost:8000';

export async function fazerRequisicao(
  caminho: string,
  metodo: 'GET' | 'POST' | 'PUT' | 'DELETE',
  corpo?: any
): Promise<any> {
  try {
    const resposta = await fetch(`${BASE_URL}${caminho}`, {
      method: metodo,
      headers: {
        'Content-Type': 'application/json',
      },
      body: corpo ? JSON.stringify(corpo) : null,
    });

    if (!resposta.ok) {
      throw new Error(`Erro: ${resposta.status} - ${resposta.statusText}`);
    }

    return await resposta.json();
  } catch (erro) {
    console.error('Erro ao fazer requisição:', erro);
    throw erro;
  }
}
