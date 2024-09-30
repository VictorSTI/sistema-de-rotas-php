<?php
namespace app\core;
require_once 'routes.php';

class Router
{
    private $uri;
    private $route;
    private $requestMethod;
    private $routeData;
    private $paramethers;

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
                $this->routeData = $this->checkRoute();
            break;

            default:
                if(preg_match(REGEX_ROUTE,$this->uri,$match))
                {
                    $this->route = $match[0];
                    $this->routeData = $this->checkRoute();
                }
                else
                {
                    $this->routeData = false;
                }
            break;
        }

        // Separa os dados da rota em um array, caso a rota seja válida.
        if($this->routeData != null)
        {
            list($controller,$method,$pageName) = explode('@',$this->routeData);
            $this->routeData = [
                'controller' => $controller . 'Controller',
                'method' => $method,
                'pageName' => $pageName
            ];
            $this->uri = preg_replace(REGEX_ROUTE,'',$this->uri);
            $this->checkParams();
        }
        else
        {
            echo 'Página não encontrada.';
            echo '<br><a href="/">Voltar ao início.</a>';
            die;
        }
    }

    /** Verifica se a rota existe no array de rotas. */
    public function checkRoute()
    {
        if(array_key_exists($this->route,ROUTES[$this->requestMethod]))
        {
            return ROUTES[$this->requestMethod][$this->route];
        }
        else
        {
            return null;
        }
    }

    /** Separa os parâmetros da uri em um array. */
    public function checkParams()
    {
        while(preg_match(REGEX_PARAMETERS,$this->uri,$match))
        {
            $params = explode('/',$match[0]);
            array_shift($params);
            $this->paramethers[$params[0]] = $params[1];
            $this->uri = preg_replace(REGEX_PARAMETERS,'',$this->uri,1);
        }
    }
}