<?php

declare(strict_types=1);

namespace App\Domain\Deposits;

use App\Domain\ListBase;

class DepositList extends ListBase
{
    public function add(Deposit $deposit): void 
    {
        $this->list[] = $deposit;
    }
}