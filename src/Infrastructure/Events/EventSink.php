<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use App\Domain\EventBase;
use App\Domain\EventPublisherInterface;

class EventSink implements EventPublisherInterface
{
    public function publish(EventBase $event): void
    {
    }
}