<?php

namespace RSlim;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class RSlim{

    public $config = [
        'base_dir'  => null,
        'base_url'  => null,
        'app_name'  => 'www'
    ];
    public $container = null;
    public $app = null;
    private $twig = null;

    public function __construct($config)
    {
        $this->config = array_merge($this->config, $config);
        ini_set("default_charset", "utf-8");
        ini_set('date.timezone', $config['app']['timezone']);
        $configuration = [
            'settings' => [
                'displayErrorDetails' => $config['app']['debug'],
            ],
        ];
        $this->container    = new \Slim\Container($configuration);
        $this->app          = new \Slim\App($this->container);
        $loader     = new \Twig_Loader_Filesystem($this->config['base_dir']);
        $this->twig = new \Twig_Environment($loader, [
            'cache'         => '/tmp',
            'debug'         => $config['app']['debug'],
            'auto_reload'   => 1
        ]);
        $this->twig->addGlobal('runtime_config', $config);
    }

    public function run_route($request, $response, $route, $action='main', $return_type='html', $args = [])
    {
        $controller = $this->config['base_dir'] . '/apps/' . $this->config['app_name'] .'/controllers/'. $route . '.' . $action . '.php';
        $template   = '/apps/' . $this->config['app_name'] . '/templates/' . $route . '/' . $action . '.html';

        if(file_exists($controller)){

            require_once($controller);

            if( $return_type == 'json' ){
                $status = 500;
                $function_output = app_content($request, $args);
                if(!is_array( $function_output) ){
                    $function_output = ["status" => 500, "error" => "Internal Server Error"];
                }
                else{
                    if(!isset($function_output['status'])){
                        $function_output['status']=200;
                    }
                    $status = (int) $function_output['status'];
                }
                $response->getBody()->write( json_encode( $function_output ) );
                $newResponse = $response->withHeader('Content-Type', 'application/json;charset=utf-8')->withHeader('X-Powered-By',"reformo/rslim")->withStatus($status);
            }
            else{
                $function_output = app_content($request, $args);
                if(!isset($function_output['data'])){
                    $function_output['data']=[];
                }
                $function_output['app_content']=$this->twig->render($template,$function_output['data']);
                $main_template_name = 'default';
                if( isset( $function_output['app_theme'] ) ){
                    $main_template_name = $function_output['app_theme'];
                }
                $main_template =  '/apps/'.$this->config['app_name'] . '/templates/_' . $main_template_name . '.html';
                $app_content =  $this->twig->render( $main_template, $function_output);
                $newResponse=$response->withHeader('X-Powered-By',"reformo/rslim");
                $newResponse->write($app_content);
            }
            return $newResponse;
        }
        return false;
    }

    public function register($request_method, $pattern, $controller, $return_type='html')
    {
        $this->app->map([strtoupper($request_method)], $pattern, function (Request $req, Response $res, $args ){
            list($route,$action) = explode("/",$args['controller']);
            return $args['RSlim']->run_route($req, $res, $route, $action, $args['return_type'], $args);
        })->setArguments(['controller'=>$controller, 'return_type'=>$return_type, 'RSlim'=>$this]);
    }

    public function run()
    {
        $this->app->run();
    }
}