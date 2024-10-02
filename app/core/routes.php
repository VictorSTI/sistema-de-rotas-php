<?php
define('ROUTES',
[
    'GET' =>
    [
        '/' => 'Home@viewHomePage@Página Inicial',
        '/usuario/home' => 'Usuario@usuarioHome@Página Inicial do Usuário'
    ],
    'POST' =>
    [
        '/' => 'Home@viewHomePage@Página Inicial'
    ]
]);