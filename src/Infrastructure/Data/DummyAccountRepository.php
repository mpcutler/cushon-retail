<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

use App\Domain\Deposits\AccountRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class DummyAccountRepository implements AccountRepositoryInterface
{
    public function exists(UuidInterface $accoutID): bool
    {
        return true;
    }
}