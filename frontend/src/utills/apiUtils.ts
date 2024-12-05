export async function enviarReserva(reserva: any) {
  const response = await fetch('http://localhost:3000/reservas', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(reserva),
  });
  return response.json();
}
