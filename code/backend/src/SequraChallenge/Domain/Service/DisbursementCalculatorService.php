<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Service;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Enum\DisbursementFrequencyEnum;
use App\SequraChallenge\Domain\Entity\Factory\DisbursementFactory;
use App\SequraChallenge\Domain\Entity\Factory\DisbursementLineFactory;
use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Domain\Repository\DisbursementRepositoryInterface;

class DisbursementCalculatorService
{

    private const SMALL_ORDER_PERCENTAGE = 1.00;
    private const MEDIUM_ORDER_PERCENTAGE = 0.95;
    private const LARGE_ORDER_PERCENTAGE = 0.85;


    public function __construct(
        private readonly DisbursementRepositoryInterface $disbursementRepository,
        private readonly DisbursementLineRepositoryInterface $disbursementLineRepository,
        private readonly DisbursementFactory $disbursementFactory,
        private readonly DisbursementLineFactory $disbursementLineFactory,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function calculateDisbursement(Purchase $purchase): Disbursement
    {
        $disbursement = $this->getDisbursement($purchase->getMerchant(), $purchase);
        $disbursementLine = $this->calculateDisbursementLine($purchase);
        $disbursement->setFees($disbursement->getFees() + $disbursementLine->getFeeAmount());
        $disbursement->setAmount($disbursement->getAmount() + $disbursementLine->getAmount() - $disbursementLine->getFeeAmount());
        $this->disbursementRepository->save($disbursement);
        $disbursementLine->setDisbursement($disbursement);
        $this->disbursementLineRepository->save($disbursementLine);
        return $disbursement;
    }

    private function calculateDisbursementLine(Purchase $purchase): DisbursementLine
    {
        $disbursementLine = $this->disbursementLineFactory->create(
            purchaseId: $purchase->getId(),
            purchaseAmount: $purchase->getAmount()
        );
        $disbursementLine->setFeePercentage($this->calculateFeePercentage($purchase));
        $disbursementLine->setFeeAmount(round($purchase->getAmount() * $disbursementLine->getFeePercentage()/100,2));
        $disbursementLine->setAmount($purchase->getAmount() - $disbursementLine->getFeeAmount());
        return $disbursementLine;
    }

    private function calculateFeePercentage(Purchase $purchase): float
    {
        $feePercentage = 0;
        if ($purchase->getAmount() < 50) {
            $feePercentage = self::SMALL_ORDER_PERCENTAGE;
        } elseif ($purchase->getAmount() >= 50 && $purchase->getAmount() < 300) {
            $feePercentage = self::MEDIUM_ORDER_PERCENTAGE;
        } elseif ($purchase->getAmount() >= 300) {
            $feePercentage = self::LARGE_ORDER_PERCENTAGE;
        }
        return $feePercentage;
    }

    /**
     * @throws \Exception
     */
    private function getDisbursement(Merchant $merchant, Purchase $purchase): Disbursement
    {
        $disbursementDate = $this->getDisbursementDate($merchant, $purchase);
        $disbursement = $this->disbursementRepository->getByMerchantAndDisbursedDate($merchant, $disbursementDate);
        if (null === $disbursement) {
            $disbursement = $this->createDisbursement($merchant, $purchase);
        }
        return $disbursement;
    }

    private function createDisbursement(Merchant $merchant, Purchase $purchase): Disbursement
    {
        $disbursement = $this->disbursementFactory->create(
            merchant: $merchant,
            disbursementDate: $purchase->getCreatedAt()
        );
        $this->checkMinimumMonthlyFee($disbursement, $purchase);
        return $disbursement;
    }

    private function checkMinimumMonthlyFee(Disbursement $disbursement, Purchase $purchase): void
    {

        $lastMonthFees = $this->getLastMonthDisbursementFees($disbursement->getMerchant(), $purchase->getCreatedAt());
        if (
            $this->isFirstDisbursementOfTheMonth($disbursement) &&
            $lastMonthFees < $disbursement->getMerchant()->getMinimumMonthlyFee()
        ) {
            $monthlyFee = $disbursement->getMerchant()->getMinimumMonthlyFee() - $lastMonthFees;
            $disbursement->setMonthlyFee($monthlyFee);
        }
    }

    /**
     * @throws \Exception
     */
    private function getDisbursementDate(Merchant $merchant, Purchase $purchase): \DateTime
    {
        switch ($merchant->getDisbursementFrequency()) {
            case DisbursementFrequencyEnum::DAILY->value:
                return $purchase->getCreatedAt();
            case DisbursementFrequencyEnum::WEEKLY->value:
                $liveOn = $merchant->getLiveOn();
                $dayOfWeek = $liveOn->format('N');
                $purchaseDayOfWeek = $purchase->getCreatedAt()->format('N');
                if ($dayOfWeek < $purchaseDayOfWeek) {
                    $daysDifference = $purchaseDayOfWeek - $dayOfWeek;
                    $disbursementDate = $purchase->getCreatedAt()->modify('-' . $daysDifference . ' days');
                } elseif ($dayOfWeek > $purchaseDayOfWeek) {
                    $daysDifference = $dayOfWeek - $purchaseDayOfWeek;
                    $disbursementDate = $purchase->getCreatedAt()->modify('+' . $daysDifference . ' days');
                } else {
                    $disbursementDate = $purchase->getCreatedAt();
                }
                return $disbursementDate;
            default:
                throw new \Exception('Invalid disbursement frequency');
        }
    }

    private function isFirstDisbursementOfTheMonth(Disbursement $disbursement): bool
    {
        $firstDisbursementOfTheMonth = $this->disbursementRepository->getFirstOfMonth($disbursement->getMerchant(), $disbursement->getCreatedAt());
        return null === $firstDisbursementOfTheMonth;
    }

    private function getLastMonthDisbursementFees(Merchant $merchant, \DateTime $date): float
    {
        return $this->disbursementRepository->getSumOfLastMonthFees($merchant, $date);
    }


}