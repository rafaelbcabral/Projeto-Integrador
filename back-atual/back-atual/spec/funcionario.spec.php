<?php

describe("Funcionario", function () {
    it("deve inicializar corretamente os atributos", function () {
        $id = 1;
        $nome = "Carlos Silva";
        $usuario = "carloss";
        $senha = "senhaSegura";

        $funcionario = new Funcionario($id, $nome, $usuario, $senha);

        expect($funcionario->id)->toBe($id);
        expect($funcionario->nome)->toBe($nome);
        expect($funcionario->usuario)->toBe($usuario);
        expect($funcionario->senha)->toBe($senha);
    });
});
