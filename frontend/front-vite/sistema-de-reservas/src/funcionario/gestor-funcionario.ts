import { Funcionario } from "./funcionario";

export class GestorFuncionarios {
  async listarFuncionarios(): Promise<Funcionario[]> {
    try {
      const response = await fetch("http://localhost:8000/funcionarios");
      if (!response.ok) {
        throw new Error("Erro ao consultar funcionários");
      }
      const funcionarios: Funcionario[] = await response.json();
      return funcionarios;
    } catch (error) {
      console.error("Erro ao listar funcionários:", error);
      return [];
    }
  }
}
