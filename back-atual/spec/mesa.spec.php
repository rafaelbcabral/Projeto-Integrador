<?php

describe("Mesa", function () {
    it("deve inicializar corretamente os atributos", function () {
        $id = 1;
        $numeroDaMesa = 10;
        $disponivel = true;

        $mesa = new Mesa($id, $numeroDaMesa, $disponivel);

        expect($mesa->id)->toBe($id);
        expect($mesa->numeroDaMesa)->toBe($numeroDaMesa);
        expect($mesa->disponivel)->toBe($disponivel);
    });
});
