<?php

describe("Pagamento", function () {
    it("deve inicializar corretamente os atributos", function () {
        $id = 1;
        $reserva = 1;
        $valorTotal = 200.00;
        $formaPagamento = "Cartão de Crédito";
        $desconto = 20.00;
        $totalComDesconto = 180.00;
        $totalDeItens = 5;

        $pagamento = new Pagamento($id, $reserva, $valorTotal, $formaPagamento, $desconto, $totalComDesconto, $totalDeItens);

        expect($pagamento->id)->toBe($id);
        expect($pagamento->reserva)->toBe($reserva);
        expect($pagamento->valorTotal)->toBe($valorTotal);
        expect($pagamento->formaPagamento)->toBe($formaPagamento);
        expect($pagamento->desconto)->toBe($desconto);
        expect($pagamento->totalComDesconto)->toBe($totalComDesconto);
        expect($pagamento->totalDeItens)->toBe($totalDeItens);
    });
});
