<?php
namespace app\controllers;
use app\core\Render;

class UsuarioController extends BaseController
{
    public function usuarioHome()
    {
        $this->parameters['pageName'] = 'Página inicial do usuário';
        $this->parameters = array_merge($this->parameters,['endereco' => 'Rua Ebrain Cobrain, 243']);
        $this->render('usuario');
    }
}