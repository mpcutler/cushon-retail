<?php

declare(strict_types=1);

use App\Domain\Deposits\AccountRepositoryInterface;
use App\Domain\Deposits\DepositRepositoryInterface;
use App\Domain\Funds\FundRepositoryInterface;
use App\Infrastructure\Data\DummyAccountRepository;
use App\Infrastructure\Data\SqlDepositRepository;
use App\Infrastructure\Data\SqlFundRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $connection = $_ENV['DB_CONNECTION'];
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $database = $_ENV['DB_DATABASE'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];

    $dsn = "{$connection}:";

    // TODO: refactor into db connector implmentations (to get rid special case code for sqlite) 
    if ($host) {
        $dsn .= "host={$host}";

        if ($port) {
            $dsn .= ";port={$port}";
        }

        $dsn .= ";dbname={$database}";
    } else {
        if ($connection == "sqlite") {
            $root = __DIR__ . "/..";
            $database = "{$root}/{$database}";
        }
        $dsn .= "{$database}";
    }

    ORM::configure($dsn);

    if ($username) { 
        ORM::configure('username', $username);
    }

    if ($password) { 
        ORM::configure('password', $password);
    }

    $containerBuilder->addDefinitions([
        FundRepositoryInterface::class => \DI\autowire(SqlFundRepository::class),
        AccountRepositoryInterface::class => \DI\autowire(DummyAccountRepository::class),
        DepositRepositoryInterface::class => \DI\autowire(SqlDepositRepository::class),
    ]);
};
