<?php

declare(strict_types=1);

use App\Domain\EventPublisherInterface;
use App\Infrastructure\Events\EventSink;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // TODO: should be logging
    $containerBuilder->addDefinitions([
        EventPublisherInterface::class => \DI\autowire(EventSink::class),
    ]);
};