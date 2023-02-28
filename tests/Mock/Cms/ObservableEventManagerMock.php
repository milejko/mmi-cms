<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Mock\Cms;

use Mmi\EventManager\EventManagerInterface;
use stdClass;

class ObservableEventManagerMock implements EventManagerInterface
{
    private array $events = [];

    public function trigger(string $event, mixed $target = null, array $argv = [], object $callback = null)
    {
        $this->events[] = [
            'event' => $event,
            'target' => $target,
            'argv' => $argv,
            'callback' => $callback,
        ];
    }

    public function attach(string $event, object $callback = null, int $priority = 1): object
    {
        return new stdClass;
    }

    public function detach(mixed $listener, string $eventName, bool $force): void
    {
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getListeners(string $event): ?array
    {
        return [];
    }

    public function clearListeners(string $event): void
    {
    }

    public function getIdentifiers(): array
    {
        return [];
    }

    public function setIdentifiers(array $identifiers): void
    {
    }

    public function addIdentifiers(array $identifiers): void
    {
    }
}
