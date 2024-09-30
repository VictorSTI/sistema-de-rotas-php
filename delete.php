<?php
namespace app\helpers;
require_once "../vendor/autoload.php";
require_once "routes.php";

/**
 * Classe para tratar rota e parâmetros recebidos através da url.
 */

class Route
{
    /**
     * @var string $requisitionType Armazena o tipo de requisição (GET ou POST).
     * @var string $uri Armazena a uri para extração de rota e parâmetros.
     * @var string $controller Armazena o nome da classe controladora que será executada.
     * @var string $method Armazena o nome do método que será executado pelo controller.
     * @var string $pagename Armazena o nome da página que será exibida (utilizo para 'localização').
     * @var string $route Armazena a rota.
     * @var array $parameters Armazena parâmetros obtidos de execução de código (não obtidos de url's).
     * @var array $urlParameters Armazena parâmetros obtidos através de URL.
     */
    private $requisitionType;
    private $uri;
    private $controller = NULL;
    private $method = NULL;
    private $pageName = NULL;
    private $route;
    private $parameters;
    private $urlParameters;

    /**
     * Inicia a rota.
     */
    public function __construct()
    {
        $this->requisitionType = $_SERVER["REQUEST_METHOD"];
        $this->uri = urldecode($_SERVER["REQUEST_URI"]);
        $this->startRoute();
    }

    // GETTERS-SETTERS
    public function getController()
    {
        return $this->controller . 'Controller';
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * Separa a URI entre rota e parâmetros e posteriormente verifica se a rota é válida.
     */
    private function startRoute()
    {
        switch($this->uri)
        {
            case '/':
                $this->route = $this->uri;
                $this->checkRoute();
            break;

            default:
                if(preg_match(REGEX_ROUTE,$this->uri,$match))
                {
                    $this->route = $match[0];
                    $this->checkRoute();
                    $this->uri = preg_replace(REGEX_ROUTE,"",$this->uri);
                }
            break;
        }

        // caso a rota seja válida, os parâmetros são separados em um array associativo.
        if($this->controller != NULL)
        {
            $this->separateParameters();
        }
        else
        {
            echo "Página não encontrada. Rota inválida.";
            echo "<br><br><a href='/'>Voltar para o início</a>";
            die;
        }

        $this->uri = urldecode($_SERVER["REQUEST_URI"]);
    }

    /**
     * Verifica se a rota existe na lista de rotas.
     * @var array $str Armazena temporáriamente o nome do controller e método que serão executados e o nome da página.
     */
    private function checkRoute()
    {
        if(array_key_exists($this->route,ROUTES[$this->requisitionType]))
        {
            $str = explode('@',ROUTES[$this->requisitionType][$this->route]);
            $this->controller = array_shift($str);
            $this->method = array_shift($str);
            // $this->pageName = array_shift($str); ???
            $this->parameters['pageName'] = array_shift($str);
        }
        else
        {
            return NULL;
        }
    }

    /**
     * Separa parâmetros (se houver) em um array associativo.
     * @var string $match Armazena parâmetros como string caso seja encontrado o padrão da regex.
     * @var array $matchExploded Array indexado para armazenar temporáriamente 1 parâmetro por vez para posteriormente ser repassado para o array associativo $parameters.
     */
    private function separateParameters()
    {
        while(preg_match(REGEX_PARAMETERS,$this->uri,$match))
        {
            $matchExploded = explode("/",$match[0]);
            array_shift($matchExploded);
            $this->parameters[$matchExploded[0]] = $matchExploded[1];
            $this->uri = preg_replace(REGEX_PARAMETERS,"",$this->uri,1);
        }
    }
}