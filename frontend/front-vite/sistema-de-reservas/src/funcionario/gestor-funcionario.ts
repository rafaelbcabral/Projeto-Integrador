import { Funcionario } from "./funcionario";
import { url } from "../infra/url.ts";

export class GestorFuncionarios {
  async listarFuncionarios(): Promise<Funcionario[]> {
    try {
      const response = await fetch(`${url}/funcionarios`, {credentials: 'include'});;
      if (!response.ok) {
        throw new Error("Erro ao consultar funcionários");
      }
      const funcionarios: Funcionario[] = await response.json();
      return funcionarios;
    } catch (error) {
      throw new Error("Erro ao listar funcionários");
    }
  }
  
}
