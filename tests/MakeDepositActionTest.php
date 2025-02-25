<?php

declare(strict_types=1);

namespace Tests;

use App\Application\Actions\Deposits\MakeDepositAction;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Application\Actions\Funds\ListFundsAction;
use App\Domain\Deposits\AccountRepositoryInterface;
use App\Domain\Deposits\DepositRepositoryInterface;
use App\Domain\EventPublisherInterface;
use App\Domain\Funds\Fund;
use App\Domain\Funds\FundList;
use App\Domain\Funds\FundRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;

#[CoversClass(MakeDepositAction::class)]
class MakeDepositActionTest extends TestCase 
{
    public function testInvalidAccount(): void 
    {
        $accountsRepository = $this->createMock(AccountRepositoryInterface::class);
        $accountsRepository->method('exists')->willReturn(false);
        $fundsRepository = $this->createMock(FundRepositoryInterface::class);
        $depositRepository = $this->createMock(DepositRepositoryInterface::class);
        $eventPublisher = $this->createMock(EventPublisherInterface::class);

        $action = new MakeDepositAction($accountsRepository, $fundsRepository, $depositRepository, $eventPublisher);

        $request = $this->getRequest('./test-deposit-invalid-account');

        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse();

        $result = $action($request, $response);

        $this->assertSame(400, $result->getStatusCode());
        $body = (string)$result->getBody();
        $this->assertStringContainsStringIgnoringCase('Invalid account', $body);
    }

    public function testInvalidFund(): void 
    {
        $accountsRepository = $this->createMock(AccountRepositoryInterface::class);
        $accountsRepository->method('exists')->willReturn(true);
        $fundsRepository = $this->createMock(FundRepositoryInterface::class);
        $fundsRepository->method('exists')->willReturn(false);
        $depositRepository = $this->createMock(DepositRepositoryInterface::class);
        $eventPublisher = $this->createMock(EventPublisherInterface::class);

        $action = new MakeDepositAction($accountsRepository, $fundsRepository, $depositRepository, $eventPublisher);

        $request = $this->getRequest('./test-deposit-invalid-fund');
        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse();

        $result = $action($request, $response);

        $this->assertSame(400, $result->getStatusCode());
        $body = (string)$result->getBody();
        $this->assertStringContainsStringIgnoringCase('Invalid fund', $body);
    }

    public function testDepositsMade(): void 
    {
        $accountsRepository = $this->createMock(AccountRepositoryInterface::class);
        $accountsRepository->method('exists')->willReturn(true);
        $fundsRepository = $this->createMock(FundRepositoryInterface::class);
        $fundsRepository->method('exists')->willReturn(true);
        $depositRepository = $this->createMock(DepositRepositoryInterface::class);
        $depositRepository->expects(self::once())->method('add');
        $eventPublisher = $this->createMock(EventPublisherInterface::class);
        $eventPublisher->expects(self::once())->method('publish');

        $action = new MakeDepositAction($accountsRepository, $fundsRepository, $depositRepository, $eventPublisher);

        $request = $this->getRequest('./test-deposit-invalid-fund');

        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse();

        $result = $action($request, $response);
        $x = (string)$result->getBody();
        echo $x;

        $this->assertSame(200, $result->getStatusCode());
    }

    private function getRequest($path): Request
    {
        $data = [
            "account_id" => "8974ed1c-cbec-4899-abf9-8d496534d71a", 
            "deposits" => [
                  [
                     "fund_id" => "a10b5e72-3c6c-49b6-bc5d-a8525f20ae0a", 
                     "amount" => "25000" 
                  ] 
               ] 
        ];

        $body = json_encode($data);

        $uriFactory = new UriFactory();
        $uri = $uriFactory->createUri('./test-deposit-invalid-fund');
        $headerList = ['Content-Type' => 'application/json'];
        $headers =new Headers($headerList);
        $cookies = [];
        $serverParams = [];
        $streamFactory = new StreamFactory();
        $bodyStream = $streamFactory->createStream($body);
        $files = [];

        $request = new Request('POST',$uri, $headers, $cookies, $serverParams, $bodyStream, $files);
        $request = $request->withParsedBody($data);
        return $request;
    }

}

