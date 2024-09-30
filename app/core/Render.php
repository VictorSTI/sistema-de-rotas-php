<?php
namespace app\core;
class Render
{
    public static function Renderize()
    {
        require_once '../views/header.php';
        require_once '../views/' . $view . '.php';
        require_once '../views/footer.php';
    }
}