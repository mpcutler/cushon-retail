<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class FundSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $id = 'a10b5e72-3c6c-49b6-bc5d-a8525f20ae0a';
        $data = [
            [
                'id' => $id,
                'name' => 'Cushon Equities Fund',
            ],
        ];

        $table = $this->table('funds');
        $table->insert($data)
              ->saveData();
    }
}
