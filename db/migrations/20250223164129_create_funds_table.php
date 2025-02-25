<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFundsTable extends AbstractMigration
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
        $table = $this->table('funds', $options);
        $table->addColumn('id', 'uuid', ['null' => false]);
        $table->addColumn('name', 'string')
              ->addTimestamps()
              ->create();

    }
}
