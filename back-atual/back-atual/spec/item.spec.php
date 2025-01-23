<?php

describe("Consumo", function () {
    it("deve inicializar corretamente os atributos", function () {
        $id = 1;
        $codigo = "cd1";
        $descricao = "Item 1";
        $preco = 5.00;
        $categoria = 1;


        $item = new Item($id, $codigo, $descricao, $preco, $categoria);

        expect($item->id)->toBe($id);
        expect($item->codigo)->toBe($codigo);
        expect($item->descricao)->toBe($descricao);
        expect($item->preco)->toBe($preco);
        expect($item->categoria)->toBe($categoria);
    });
});
