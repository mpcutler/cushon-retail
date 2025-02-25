<?php

declare(strict_types=1);

namespace App\Application\Actions\Funds;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Funds\FundRepositoryInterface;
use Exception;

class ListFundsAction
{
    protected FundRepositoryInterface $repository;

    public function __construct(FundRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request, Response $response): Response 
    {
        $funds = $this->repository->getAll();
        $payload = json_encode($funds->toArray());
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}