<?php

declare(strict_types=1);

namespace App\Domain;

use DateTime;

abstract class EventBase 
{
    public readonly DateTime $eventDate;

    public function __construct()
    {
        $this->eventDate = new DateTime();
    }
}