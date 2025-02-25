<?php

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

require __DIR__ . '/../boot/env.php';

$dependencies = require __DIR__ . '/../boot/dependencies.php';
$dependencies($containerBuilder);

$repositories = require __DIR__ . '/../boot/repositories.php';
$repositories($containerBuilder);

$container = $containerBuilder->build();
$app = Bridge::create($container);

$app->addBodyParsingMiddleware();

$routes = require __DIR__ . '/../boot/routes.php';
$routes($app);

$app->run();
