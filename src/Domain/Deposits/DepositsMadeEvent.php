<?php

declare(strict_types=1);

namespace App\Domain\Deposits;

use App\Domain\EventBase;

class DepositsMadeEvent extends EventBase
{
    public readonly DepositList $deposits;

    public function __construct(DepositList $deposits) 
    {
        $this->deposits = $deposits;
    }
}