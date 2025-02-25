<?php

declare(strict_types=1);

namespace App\Domain\Funds;

use Ramsey\Uuid\UuidInterface;

class Fund 
{
    public readonly UuidInterface $id;
    public readonly string $name;

    public function __construct(UuidInterface $id, string $name) 
    {
        $this->id = $id;
        $this->name = $name;
    }
}
