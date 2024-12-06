export class Mesa {
  id: number;
  status: string; // 'disponível', 'reservada'

  constructor(id: number, status: string) {
    this.id = id;
    this.status = status;
  }
}
