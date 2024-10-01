<?php
define('ROUTES',
[
    'GET' =>
    [
        '/' => 'Home@viewHomePage',
        '/usuario/home' => 'Usuario@usuarioHome'
    ],
    'POST' =>
    [
        '/' => 'Home@viewHomePage'
    ]
]);