<?php
define('ROUTES',
[
    'GET' =>
    [
        '/' => 'Home@home@Página Inicial',
        '/usuario/home' => 'Usuario@usuarioHome@Página inicial do usuário'
    ],
    'POST' =>
    [
        '/' => 'Home@home@Página Inicial'
    ]
]);