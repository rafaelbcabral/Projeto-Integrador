import { Reserva } from "./criar-reserva";
import { ReservaListar } from "./listar-reservas";

export class GestorReservas {
  async criarReserva(reserva: Reserva): Promise<Reserva> {
    console.log(reserva);

    try {
      const response = await fetch("http://localhost:8000/reservas", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(reserva),
      });

      if (!response.ok) {
        throw new Error("Erro ao criar a reserva");
      }

      const reservaCriada: Reserva = await response.json();
      return reservaCriada;
      console.log(reservaCriada);
    } catch (error) {
      console.error("Erro ao criar reserva:", error);
      throw error;
    }
  }

  async listarReservas(): Promise<ReservaListar[]> {
    try {
      const response = await fetch("http://localhost:8000/reservas", {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });

      if (!response.ok) {
        throw new Error("Erro ao listar as reservas");
      }

      const reservas: ReservaListar[] = await response.json();
      return reservas;
      console.log(reservas);
    } catch (error) {
      console.error("Erro ao listar reservas:", error);
      throw error;
    }
  }

  // Função para cancelar uma reserva (usando PUT para alterar o status da reserva)
  async cancelarReserva(id: string): Promise<void> {
    try {
      const response = await fetch(`http://localhost:8000/reservas/${id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ status: "cancelado" }), // Define o status como 'cancelado'
      });

      if (!response.ok) {
        throw new Error("Erro ao cancelar a reserva");
      }
      window.location.reload();

      // Atualiza a lista de reservas após a operação de cancelamento
      this.listarReservas();
    } catch (error) {
      console.error("Erro ao cancelar reserva:", error);
    }
  }
}
