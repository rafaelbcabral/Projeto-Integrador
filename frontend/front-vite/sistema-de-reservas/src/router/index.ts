import page from "page";

// Define route handlers
const index = () => {
  window.location.href = "/index.html";
};

const realizarReserva = () => {
  window.location.href = "/src/pages/realizar-reserva.html";
};

const listarReservas = () => {
  window.location.href = "/src/pages/listar-reservas.html";
};

const grafico = () => {
  window.location.href = "/src/pages/grafico.html";
};

// Define routes
page("/", index);
page("/realizar-reserva", realizarReserva);
page("/listar-reservas", listarReservas);
page("/grafico", grafico);

// Initialize page.js
page();
