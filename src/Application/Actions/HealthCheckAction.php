<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HealthCheckAction
{
    public function __invoke(Request $request, Response $response): Response 
    {
        $response->getBody()->write(date('Y-m-d H:i:s'));
        return $response;
    }
}