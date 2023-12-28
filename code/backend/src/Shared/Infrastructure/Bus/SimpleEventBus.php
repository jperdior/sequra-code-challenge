<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

use function Lambdish\Phunctional\each;

class SimpleEventBus implements EventBus
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messengerBusCommand)
    {
        $this->messageBus = $messengerBusCommand;
    }

    public function publish(DomainEvent ...$events): void
    {
        each(fn (DomainEvent $event) => $this->messageBus->dispatch($event), $events);
    }
}
