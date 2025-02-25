<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

use App\Domain\Funds\Fund;
use App\Domain\Funds\FundList;
use App\Domain\Funds\FundRepositoryInterface;
use ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SqlFundRepository implements FundRepositoryInterface 
{
    public function getAll(): FundList
    {
        $list = new FundList();

        $data = ORM::forTable('funds')->findMany();

        foreach($data as $record) {
            $id = Uuid::fromString($record->id);
            $fund = new Fund($id, $record->name);
            $list->add($fund);
        }

        return $list;
    }

    public function exists(UuidInterface $id): bool
    {
        $count = ORM::forTable('funds')->where('id', $id->toString())->count();
        return $count == 1;
    }
}