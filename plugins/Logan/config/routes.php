<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Logan',
    ['path' => '/log'],
    function (RouteBuilder $routes) {
        $routes->connect('/', ['controller' => 'logs', 'action' => 'index']);
        $routes->connect('/view/*', ['controller' => 'logs', 'action' => 'view']);
        $routes->fallbacks(DashedRoute::class);
    }
);
