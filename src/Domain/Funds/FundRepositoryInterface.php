<?php

declare(strict_types=1);

namespace App\Domain\Funds;

use Ramsey\Uuid\UuidInterface;

interface FundRepositoryInterface 
{
    function getAll(): FundList;

    function exists(UuidInterface $id): bool;
}