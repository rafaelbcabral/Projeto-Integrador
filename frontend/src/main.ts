import { ReservaModel } from './models/ReservaModel.js';
import { ReservaView } from './views/ReservaView.js';
import { ReservaController } from './controlles/ReservaController.js';
// import './router.js'; // fazer com rota?


// Inicializando o modelo, visão e controlador
const rootElement = document.getElementById('app');
if (rootElement) {
  const model = new ReservaModel();
  const view = new ReservaView(rootElement);
  new ReservaController(model, view);
}
