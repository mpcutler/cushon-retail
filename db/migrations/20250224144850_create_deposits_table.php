<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDepositsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $options =  [
            'id' => false,
			'primary_key' => 'id',
		];
        $table = $this->table('deposits', $options);
        $table->addColumn('id', 'uuid', ['null' => false])
            ->addColumn('account_id', 'uuid', ['null' => false])
            ->addColumn('fund_id', 'uuid')
            ->addColumn('amount', 'integer') // could be a double? would such a deposit be allowed (or desirable)?
            ->addColumn('date_made', 'datetime')
            ->addTimestamps()
            ->create();
    }
}
