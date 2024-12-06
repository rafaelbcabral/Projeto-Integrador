import { fazerRequisicao } from '../utills/api-utils.js';
import { Funcionario } from './funcionario-model.js';

export async function buscarFuncionarios(): Promise<Funcionario[]> {

  const response = await fetch("http://localhost:8000/funcionarios");

  const funcionarios = await fazerRequisicao('/funcionarios', 'GET');
  return funcionarios.map((func: any) => new Funcionario(func.id, func.nome));
}
