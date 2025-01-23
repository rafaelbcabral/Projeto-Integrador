import { Reserva } from "./criar-reserva";
import { ReservaListar } from "./listar-reservas";
import { url } from "../infra/url.ts";
import { exibirErro } from "../infra/exibir-erro.ts";

const urlreserva = `${url}/reservas`;

export class GestorReservas {
  async criarReserva(reserva: Reserva): Promise<Reserva> {


      const response = await fetch(urlreserva, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(reserva),
      });

      if (!response.ok) {
        exibirErro("Erro ao criar a reserva. ", response.status);
      }
      // Alerta de confirmação antes de redirecionar
      window.alert(
        "Reserva criada com sucesso! Você será redirecionado para a página principal."
      );

      window.location.href = "index.html";

      const reservaCriada: Reserva = await response.json();
      return reservaCriada;

  }

  async listarReservas(): Promise<ReservaListar[]> {

      const response = await fetch(urlreserva, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });

      if (!response.ok) {
        exibirErro("Erro ao listar reservas. ", response.status);
      }

      const reservas: ReservaListar[] = await response.json();
      return reservas;

  }

  async cancelarReserva(id: string): Promise<void> {

      const response = await fetch(urlreserva + `/${id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ status: "cancelado" }),
      });

      if (!response.ok) {
        throw new Error("Erro ao cancelar a reserva");
      }
      window.location.reload();

      this.listarReservas();

  }

  // Adicionar a função para inicializar visões e controladores
  inicializar() {
    import("../reserva/visao-criar-reserva").then(({ VisaoCriarReservas }) => {
      import("../funcionario/funcionario-controller").then(
        ({ ControladoraFuncionarios }) => {
          import("../funcionario/visao-funcionario").then(
            ({ VisaoFuncionarios }) => {
              import("../mesa/mesa-controller").then(({ ControladoraMesas }) => {
                import("../mesa/visao-mesa").then(({ VisaoMesas }) => {
                  const visaoMesas = new VisaoMesas();
                  const visaoFuncionarios = new VisaoFuncionarios();
                  const controladoraMesas = new ControladoraMesas(visaoMesas);
                  const controladoraFuncionarios = new ControladoraFuncionarios(
                    visaoFuncionarios
                  );
                  const visaoCriarReservas = new VisaoCriarReservas(
                    controladoraMesas,
                    controladoraFuncionarios
                  );
                  visaoCriarReservas.iniciar();
                });
              });
            }
          );
        }
      );
    });
  }
}
