<?php

declare(strict_types=1);

namespace App\Domain;

interface EventPublisherInterface
{
    function publish(EventBase $event): void;
}
