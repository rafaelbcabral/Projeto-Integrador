<?php

interface IRepository
{
    public function fazerReserva(Reserva $reserva);
    public function listarReservas(): array;
    public function cancelarReserva($id);
    public function verificarDisponibilidade(): bool;
}
