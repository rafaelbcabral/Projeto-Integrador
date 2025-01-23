<?php

describe("Consumo", function () {
    it("deve inicializar corretamente os atributos", function () {
        $id = 1;
        $reserva = 1;
        $item = 1;
        $quantidade = 5;
        $funcionario = 1;
        $valorTotalPorItem = 100.00;

        $consumo = new Consumo($id, $reserva, $item, $quantidade, $funcionario, $valorTotalPorItem);

        expect($consumo->id)->toBe($id);
        expect($consumo->reserva)->toBe($reserva);
        expect($consumo->item)->toBe($item);
        expect($consumo->quantidade)->toBe($quantidade);
        expect($consumo->funcionario)->toBe($funcionario);
        expect($consumo->valorTotalPorItem)->toBe($valorTotalPorItem);
    });
});
