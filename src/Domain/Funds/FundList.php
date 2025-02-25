<?php

declare(strict_types=1);

namespace App\Domain\Funds;

use App\Domain\ListBase;

class FundList extends ListBase
{
    public function add(Fund $fund): void 
    {
        $this->list[] = $fund;
    }
}