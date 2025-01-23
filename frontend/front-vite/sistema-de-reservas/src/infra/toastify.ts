// src/toast.ts
import Toastify from 'toastify-js';

type ToastOptions = {
  text: string;
  duration: number;
  gravity: 'top' | 'bottom';
  position: 'left' | 'center' | 'right';
  style: {
    background: string;
  };
  close: boolean;
  stopOnFocus: boolean;
};

const showToast = (message: string, type: 'sucesso' | 'erro' | 'info' = 'info') => {
  const cores = {
    sucesso: 'linear-gradient(to right, #4caf50, #8bc34a)',
    erro: 'linear-gradient(to right, #f44336, #e57373)',
    info: 'linear-gradient(to right, #2196f3, #64b5f6)',
  };

  const toastOptions: ToastOptions = {
    text: message, // Aqui está a correção: passando 'message' para 'text'
    duration: 4000, // Duração do toast
    gravity: 'top', // 'top' ou 'bottom'
    position: 'right', // 'left', 'center', 'right'
    style: {
      background: cores[type], // Cor do fundo
    },
    close: true, // Exibe o botão de fechar
    stopOnFocus: true, // Pausa ao passar o mouse
  };

  // Exibe o toast com as configurações
  Toastify(toastOptions).showToast();
};

export { showToast };
