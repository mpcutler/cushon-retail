<?php

declare(strict_types=1);

namespace App\Domain\Deposits;

use Ramsey\Uuid\UuidInterface;

interface AccountRepositoryInterface
{
    function exists(UuidInterface $accoutID): bool;
}
