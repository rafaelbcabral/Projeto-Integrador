<?php

describe("Categoria", function () {
    it("deve inicializar corretamente os atributos", function () {
        $id = 1;
        $nome = "teste categoria";

        $categoria = new Categoria($id, $nome);

        expect($categoria->id)->toBe($id);
        expect($categoria->nome)->toBe($nome);
    });
});
