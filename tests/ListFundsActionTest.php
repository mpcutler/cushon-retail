<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Application\Actions\Funds\ListFundsAction;
use App\Domain\Funds\Fund;
use App\Domain\Funds\FundList;
use App\Domain\Funds\FundRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

#[CoversClass(ListFundsAction::class)]
class ListFundsActionTest extends TestCase 
{
    public function testFundsReturned(): void 
    {
        $funds = new FundList();
        $funds->add(new Fund(Uuid::uuid4(), 'Super Fund'));
        $funds->add(new Fund(Uuid::uuid4(), 'Mega Fund'));
        $json = json_encode($funds->toArray());

        $repository = $this->createMock(FundRepositoryInterface::class);
        $repository->method('getAll')->willReturn($funds);

        $action = new ListFundsAction($repository);

        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest('GET', './test-funds-returned');

        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse();

        $result = $action($request, $response);
        $this->assertSame(200, $result->getStatusCode());
        $body = (string)$result->getBody();
        $this->assertSame($json, $body);
    }
    
    public function testFundsError(): void 
    {
        $repository = $this->createMock(FundRepositoryInterface::class);
        $repository->method('getAll')->willThrowException(new \Exception('error', 500));

        $action = new ListFundsAction($repository);
        $this->expectExceptionCode(500);

        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest('GET', './test-funds-error');

        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse();

        $action($request, $response);
    }
}

