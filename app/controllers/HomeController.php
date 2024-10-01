<?php
namespace app\controllers;
class HomeController extends BaseController
{
    public function viewHomePage()
    {
        $this->parameters['pageName'] = 'Página Inicial';
        $this->render('home');
    }
}