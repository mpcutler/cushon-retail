<?php

declare(strict_types=1);

use Slim\App;
use Slim\Psr7\Factory\StreamFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\HealthCheckAction;
use App\Application\Actions\Funds\ListFundsAction;
use App\Application\Actions\Deposits\MakeDepositAction;

return function (App $app) {
    $app->get('/', HealthCheckAction::class);
    $app->get('/funds', ListFundsAction::class);
    $app->post('/deposit', MakeDepositAction::class);

    $app->get('/favicon.ico', function (Request $request, Response $response,) {
        $streamFactory = new StreamFactory();
        return $response->withBody(
            $streamFactory->createStreamFromFile(__DIR__ . '/favicon.ico')
        );
    });
};