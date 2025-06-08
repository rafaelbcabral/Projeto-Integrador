
import { ItemCarrinho } from "./itemCarrinho";
import { Mesa } from "./mesa";

export interface ConsumoModel {
  mesas: Mesa[];
  itensCarrinho: ItemCarrinho[];
}
