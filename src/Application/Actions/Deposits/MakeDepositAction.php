<?php

declare(strict_types=1);

namespace App\Application\Actions\Deposits;

use App\Domain\Deposits\AccountRepositoryInterface;
use App\Domain\Deposits\Deposit;
use App\Domain\Deposits\DepositList;
use App\Domain\Deposits\DepositRepositoryInterface;
use App\Domain\Deposits\DepositsMadeEvent;
use App\Domain\EventPublisherInterface;
use App\Domain\Funds\FundRepositoryInterface;
use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Valitron\Validator;

class MakeDepositAction
{
    private string $uuidRegexPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';
    private AccountRepositoryInterface $accountRepository;
    private FundRepositoryInterface $fundRepository;
    private DepositRepositoryInterface $depositRepository;
    private EventPublisherInterface $eventPublisher;
    
    public function __construct(AccountRepositoryInterface $accountRepository, FundRepositoryInterface $fundRepository, DepositRepositoryInterface $depositRepository, EventPublisherInterface $eventPublisher) 
    {
        $this->accountRepository = $accountRepository;
        $this->fundRepository = $fundRepository;
        $this->depositRepository = $depositRepository;
        $this->eventPublisher = $eventPublisher;
    }

    public function __invoke(Request $request, Response $response): Response 
    {
        $body = $request->getParsedBody();

        $validator = new Validator($body);
        $validator->rule('required',['account_id', 'deposits', 'deposits.*.fund_id', 'deposits.*.amount']);
        $validator->rule('regex',['account_id', 'deposits.*.fund_id'], $this->uuidRegexPattern);
        $validator->rule('array',['deposits']);
        $validator->rule(function($field, $value) {
            return !empty($value);
        }, 'deposits', 'deposits are required');
        $validator->rule('integer',['deposits.*.amount']);
        $validator->rule('min',['deposits.*.amount'], 0);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            return $this->jsonResponse($response, 400, $errors);
        }

        // TODO: Anaemic domain model. 
        // Basic CRUD is all that is needed now, but as it develops this could move to a service
        // i.e. where are the funds coming from?

        $accountID = Uuid::fromString($body['account_id']);
        if (!$this->accountRepository->exists($accountID)) {
            $errors = ['account_id' => 'Invalid account Id'];
            return $this->jsonResponse($response, 400, $errors);
        }

        $depositCount = count($body['deposits']);
        $invalidFundIDs = [];
        $depositDate = new DateTime();
        $deposits = new DepositList();

        // TODO: could be improved for efficiency if deposit list becomes large but current requirement is for only 1 fund
        for ($i = 0; $i < $depositCount; $i++) {
            $fundID = Uuid::fromString($body['deposits'][$i]['fund_id']);
            
            if (!$this->fundRepository->exists($fundID)) {
                $invalidFundIDs[] = $fundID;
                break;
            }

            $amount = intval($body['deposits'][$i]['amount']);
            $deposit = new Deposit(Uuid::uuid4(), $accountID, $fundID, $amount, $depositDate);
            $deposits->add($deposit);
        }
        
        if (count($invalidFundIDs) > 0) {
            $errors = ['error' => 'Invalid fund submitted'];
            return $this->jsonResponse($response, 400, $errors);
        }

        $this->depositRepository->add($deposits);
        
        $event = new DepositsMadeEvent($deposits);
        $this->eventPublisher->publish($event);

        return $response;
    }

    // TODO: should probably be moved to a base class or hhelper once more actions are added
    private function jsonResponse(Response $response, int $status, array $data): Response {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}