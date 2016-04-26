<?php

require_once dirname(__DIR__) . '/app_config.php';
require_once $config['base_dir'] . '/lib/vendor/autoload.php';

/*
    DB/CACHE CONNECTIONS, SESSION SETTINGS, ETC CAN BE SET AND DONE HERE
*/

session_start();

$RSlim = new \RSlim\RSlim($config);
/*
 $RSlim->register($request_method, $route, $controller, $return_type);
*/
$RSlim->register("get", '/', 'app/main');
$RSlim->register("get", "/hello", "app/hello");
$RSlim->register("post", "/hello", "app/hello.post");
$RSlim->register("get", "/hello/{name}", "app/hello_name", "json");
$RSlim->run();