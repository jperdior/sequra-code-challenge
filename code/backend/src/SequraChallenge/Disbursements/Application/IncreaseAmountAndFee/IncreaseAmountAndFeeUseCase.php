<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\IncreaseAmountAndFee;

use App\SequraChallenge\Disbursements\Domain\DisbursementFinder;
use App\SequraChallenge\Disbursements\Domain\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Lock\LockingInterface;
use App\Shared\Domain\Repository\TransactionInterface;

final readonly class IncreaseAmountAndFeeUseCase
{
    public function __construct(
        private EventBus $eventBus,
        private DisbursementFinder $disbursementFinder,
        private DisbursementRepositoryInterface $repository,
        private TransactionInterface $transaction,
        private LockingInterface $locking
    ) {
    }

    public function __invoke(string $reference, float $amount, float $fee): void
    {
        $this->transaction->begin();
        try {
            $this->locking->create('disbursement-'.$reference);
            $this->locking->acquire(true);
            $disbursementReference = new DisbursementReference($reference);
            $disbursement = $this->disbursementFinder->__invoke($disbursementReference);
            /*
             * @todo query all disbursement lines for this disbursement as queues don't guarantee order and duplicate messages are possible
             */
            $disbursement->increaseAmountAndFee($amount, $fee);
            $this->repository->save($disbursement);
            $this->eventBus->publish(...$disbursement->pullDomainEvents());
            $this->transaction->commit();
        } catch (\Exception $e) {
            $this->transaction->rollback();
            throw $e;
        } finally {
            $this->locking->release();
        }
    }
}
