<?php

declare(strict_types=1);

namespace App\Domain\Deposits;

use DateTime;
use Ramsey\Uuid\UuidInterface;

class Deposit 
{
    public readonly UuidInterface $id;
    public readonly UuidInterface $accountID;
    public readonly UuidInterface $fundID;
    public readonly int $amount; // TODO: Should this be a float? Are non whole-pund deposits allowed?
    public readonly DateTime $dateMade;

    public function __construct(UuidInterface $id, UuidInterface $accountID, UuidInterface $fundID, int $amount, DateTime $dateMade)
    {
        $this->id = $id;
        $this->accountID = $accountID;
        $this->fundID = $fundID;
        $this->amount = $amount;
        $this->dateMade = $dateMade;
    }
}
