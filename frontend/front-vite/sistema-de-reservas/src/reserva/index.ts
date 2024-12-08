import { VisaoListarReservas } from "./visao-listar-reservas";
// import { VisaoCriarReservas } from "./visao-criar-reserva";
// import { ControladoraFuncionarios } from "../funcionario/funcionario-controller";
// import { VisaoFuncionarios } from "../funcionario/visao-funcionario";
// import { ControladoraMesas } from "../mesa/mesa-controller";
// import { VisaoMesas } from "../mesa/visao-mesa";

// // Instancia as visões
// const visaoMesas = new VisaoMesas();
// const visaoFuncionarios = new VisaoFuncionarios();

// const controladoraMesas = new ControladoraMesas(visaoMesas);
// const controladoraFuncionarios = new ControladoraFuncionarios(
//   visaoFuncionarios
// );

// const visaoCriarReservas = new VisaoCriarReservas(
//   controladoraMesas,
//   controladoraFuncionarios
// );
// visaoCriarReservas.iniciar();

const visaoListarReservas = new VisaoListarReservas();
visaoListarReservas.iniciar();
