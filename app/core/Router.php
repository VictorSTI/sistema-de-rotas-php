<?php
namespace app\core;
require_once 'routes.php';

class Router
{
    private $uri;
    private $route;
    private $requestMethod;
    private $controller;
    private $method;
    private $parameters = [];
    private $flag;

    public function getUri()
    {
        return $this->uri;
    }
    
    public function getRoute()
    {
        return $this->route;
    }
    
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }
    
    public function getController()
    {
        return $this->controller;
    }

    public function getMethod()
    {
        return $this->method;
    }
    
    public function getParameters()
    {
        return $this->parameters;
    }

    public function __construct()
    {
        $this->uri = urldecode($_SERVER['REQUEST_URI']);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->startRoute();
    }

    /** Inicia a rota.
     * Verifica se a uri está em um padrão válido.
     * Verifica se a uri contém parâmetros em um padrão válido. */
    public function startRoute()
    {
        switch($this->uri)
        {
            case '/':
                $this->route = $this->uri;
                $this->flag = $this->checkRoute();
            break;

            default:
                if(preg_match(REGEX_ROUTE,$this->uri,$match))
                {
                    $this->route = $match[0];
                    $this->flag = $this->checkRoute();
                }
                else
                {
                    $this->flag = false;
                }
            break;
        }

        // Se a rota é válida (true), verifica se existem parâmetros.
        if($this->flag === true)
        {
            unset($this->flag);
            $this->checkParams();
        }
        else
        {
            echo 'Página não encontrada.';
            echo '<br><a href="/">Voltar ao início.</a>';
            die;
        }
    }

    /** Verifica se a rota existe no array de rotas.
     * Define controller e method. */
    public function checkRoute()
    {
        if(array_key_exists($this->route,ROUTES[$this->requestMethod]))
        {
            $array = explode('@',ROUTES[$this->requestMethod][$this->route]);
            $this->controller = $array[0] . 'Controller';
            $this->method = $array[1];
            return true;
        }
        else
        {
            return false;
        }
    }

    /** Separa os parâmetros da uri em um array. */
    public function checkParams()
    {
        // remove a rota da uri.
        $this->uri = preg_replace(REGEX_ROUTE,'',$this->uri);
        // verifica se existem parâmetros.
        while(preg_match(REGEX_PARAMETERS,$this->uri,$match))
        {
            $params = explode('/',$match[0]);
            array_shift($params);
            $this->parameters[$params[0]] = $params[1];
            $this->uri = preg_replace(REGEX_PARAMETERS,'',$this->uri,1);
        }
    }
}