# RSlim

Basically Slim but based on convention over configuration. Do not install until the version 1.0



## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install RSlim.

```bash
$ composer require --prefer-dist reformo/rslim "^1.0"
```

This will install RSlim and all required dependencies. RSlim requires PHP 5.5.0 or newer. slim/slim and twig/twig packages..

## Usage
```

$config =[
    'base_dir'  => dirname(dirname(__DIR__)),
    'base_url'  => 'http://www.reformo.dev',
    'app_name'  => basename(__DIR__),
    'app'=>[
        'debug'     => 1,
        'timezone'  => 'Europe/Istanbul'
    ]
];

$RSlim = new \RSlim\RSlim($config);

/*
 $RSlim->register($request_method, $route, $controller, $return_type);
*/

$RSlim->register("get", '/', 'app/main');
$RSlim->register("get", "/hello", "app/hello");
$RSlim->register("post", "/hello", "app/hello.post");
$RSlim->register("get", "/hello/{name}", "app/hello_name", "json");
$RSlim->run();

```

## Contribute
* Open issue if found bugs or sent pull request.
* Feel free to ask if have any questions.
