<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\UpdateAmountAndFee;

use App\SequraChallenge\Disbursements\Domain\DisbursementFinder;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Domain\Repository\TransactionInterface;
use App\Shared\Domain\Lock\LockingInterface;

final readonly class UpdateAmountAndFeeUseCase
{

    public function __construct(
        private DisbursementFinder              $disbursementFinder,
        private DisbursementRepositoryInterface $repository,
        private TransactionInterface                   $transaction,
        private LockingInterface                       $locking
    ) {
    }

    public function __invoke(string $reference, float $amount, float $fee): void
    {

        $this->transaction->begin();
        try{
            $this->locking->create('disbursement-' . $reference);
            $this->locking->acquire(true);
            $disbursementReference = new DisbursementReference($reference);
            $disbursement = $this->disbursementFinder->__invoke($disbursementReference);
            $disbursement->addAmount($amount);
            $disbursement->addFee($fee);
            // @todo: calculate monthly fee
            $this->repository->save($disbursement);
            $this->transaction->commit();
        } catch(\Exception $e){
            $this->transaction->rollback();
            throw $e;
        }
        finally{
            $this->locking->release();
        }
    }
}