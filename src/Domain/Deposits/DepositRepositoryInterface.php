<?php

declare(strict_types=1);

namespace App\Domain\Deposits;

interface DepositRepositoryInterface 
{
    function add(DepositList $deposits): void;
}