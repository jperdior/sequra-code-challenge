<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SimpleCommandBus implements CommandBus
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messengerBusCommand)
    {
        $this->messageBus = $messengerBusCommand;
    }

    public function dispatch(Command $command): void
    {
        $this->messageBus->dispatch(message: $command);
    }
}
