import { GraficoController } from "./relatorio-controller";
import { GraficoView } from "./relatorio-view";

// Instanciando GraficoController fora do graficoView
const graficoController = new GraficoController();
const graficoView = new GraficoView(); // Passa o graficoController instanciado para o GraficoView
