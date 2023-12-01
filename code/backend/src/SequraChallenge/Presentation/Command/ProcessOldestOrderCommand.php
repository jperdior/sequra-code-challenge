<?php

declare(strict_types=1);

namespace App\SequraChallenge\Presentation\Command;

use App\SequraChallenge\Application\Command\ProcessOldestPurchaseMessage;
use App\SequraChallenge\Infrastructure\Messenger\SimpleCommandBus;
use App\SequraChallenge\Infrastructure\Messenger\SimpleQueryBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Console\Input\InputArgument;
use ApiPlatform\Symfony\Validator\Exception\ValidationException;

#[AsCommand(name: 'app:process-oldest-purchase', description: 'Processes the oldest purchase in the queues')]
class ProcessOldestOrderCommand extends Command
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SimpleQueryBus $queryBus,
        private readonly SimpleCommandBus $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Processes the oldest purchase in the queues');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->commandBus->dispatch(new ProcessOldestPurchaseMessage());

        return Command::SUCCESS;
    }
}
