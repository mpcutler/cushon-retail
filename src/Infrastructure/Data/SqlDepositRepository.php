<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

use App\Domain\Deposits\Deposit;
use App\Domain\Deposits\DepositList;
use App\Domain\Deposits\DepositRepositoryInterface;
use Exception;
use ORM;

class SqlDepositRepository implements DepositRepositoryInterface 
{
    public function add(DepositList $deposits): void
    {
        ORM::get_db()->beginTransaction();

        try {

            /** @var Deposit $deposit */
            foreach($deposits as $deposit) {
                $record = ORM::forTable('deposits')->create();
                $record->id = $deposit->id->toString();
                $record->account_id = $deposit->accountID->toString();
                $record->fund_id = $deposit->fundID->toString();
                $record->amount = $deposit->amount;
                $record->date_made = $deposit->dateMade->format('Y-m-d H:i:s');
                $record->save();
            }

            ORM::get_db()->commit();
        } catch (Exception $exception) {
            ORM::get_db()->rollBack();
            throw $exception;
        }        
    }
}