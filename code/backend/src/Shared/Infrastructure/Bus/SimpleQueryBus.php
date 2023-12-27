<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SimpleQueryBus implements QueryBus
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messengerBusQuery)
    {
        $this->messageBus = $messengerBusQuery;
    }

    public function ask(Query $query): ?Response
    {
        $envelope = $this->messageBus->dispatch(message: $query);

        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(stampFqcn: HandledStamp::class);

        return $stamp->getResult();
    }
}
