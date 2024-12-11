<?php

function conectar(): PDO
{
    return new PDO(
        'mysql:dbname=dbreserva;host=localhost;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}
