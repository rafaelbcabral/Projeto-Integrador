import { VisaoCriarReservas } from "./visao-criar-reserva";
import { ControladoraFuncionarios } from "../funcionario/funcionario-controller";
import { ControladoraMesas } from "../mesa/mesa-controller.ts";
import { VisaoFuncionarios } from "../funcionario/visao-funcionario";
import { VisaoMesas } from "../mesa/visao-mesa.ts";

// Inicializa as controladoras
const visaoMesas = new VisaoMesas();
const controladoraMesas = new ControladoraMesas(visaoMesas);

// Inicializa a visão de funcionários e passamos para a controladora
const visaoFuncionarios = new VisaoFuncionarios();
const controladoraFuncionarios = new ControladoraFuncionarios(visaoFuncionarios);

// Inicializa a visão de criar reservas
const visaoCriarReservas = new VisaoCriarReservas(controladoraMesas, controladoraFuncionarios);
visaoCriarReservas.iniciar();
