<?php

function conectar(): PDO
{
    return new PDO(
        'mysql:dbname=dbreservas;host=localhost;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}
