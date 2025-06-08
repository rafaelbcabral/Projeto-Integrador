import { Reserva } from "./criar-reserva";
import { ReservaListar } from "./listar-reservas";
import { url } from "../infra/url.ts";

const urlreserva = `${url}/reservas`;

export class GestorReservas {
  async criarReserva(reserva: Reserva): Promise<Reserva> {
    // Recuperando o ID do funcionário do sessionStorage
    const funcionarioId = sessionStorage.getItem("user_id");

    if (!funcionarioId) {
      throw new Error("Funcionário não está logado.");
    }
    
    // Associa o ID do funcionário à reserva
    reserva.funcionario = Number(funcionarioId);
    if (reserva.inicio.length === 5) {
      reserva.inicio += ":00"; // Adiciona segundos se necessário
    }
    console.log(reserva);
    const response = await fetch(urlreserva, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      // credentials: 'include',
      body: JSON.stringify(reserva),
    });

    if (!response.ok) {
      throw new Error(`Erro ao criar a reserva. Status: ${response.status}`);
    }

    window.location.href = "/home";

    const reservaCriada: Reserva = await response.json();
    return reservaCriada;
}

  async listarReservas(): Promise<ReservaListar[]> {
    try {
      const response = await fetch(urlreserva, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
        // credentials: 'include'
      });

      if (!response.ok) {
        throw new Error(`Erro ao listar reservas. Status: ${response.status}`);
      }

      const reservas: ReservaListar[] = await response.json();
      return reservas;
    } catch (error) {
      return []; // Retorna um array vazio em caso de erro
    }
  }

  async cancelarReserva(id: string): Promise<void> {
      const response = await fetch(urlreserva + `/${id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        // credentials: 'include',
        body: JSON.stringify({ status: "cancelado" }),
      });

      if (!response.ok) {
        throw new Error("Erro ao cancelar a reserva");
      }

      window.location.reload();
      this.listarReservas();
    } 
  }

