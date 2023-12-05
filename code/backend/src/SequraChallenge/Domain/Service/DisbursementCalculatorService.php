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
use App\SequraChallenge\Domain\Exception\ConcurrentException;
use App\SequraChallenge\Domain\Exception\InvalidDisbursementFrequencyException;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Domain\Repository\DisbursementRepositoryInterface;

class DisbursementCalculatorService
{
    public const SMALL_ORDER_PERCENTAGE = 1.00;
    public const MEDIUM_ORDER_PERCENTAGE = 0.95;
    public const LARGE_ORDER_PERCENTAGE = 0.85;

    public function __construct(
        private readonly DisbursementRepositoryInterface $disbursementRepository,
        private readonly DisbursementLineRepositoryInterface $disbursementLineRepository,
        private readonly DisbursementFactory $disbursementFactory,
        private readonly DisbursementLineFactory $disbursementLineFactory,
    ) {
    }


    /**
     * @throws InvalidDisbursementFrequencyException
     * @throws ConcurrentException
     */
    public function calculateDisbursement(Purchase $purchase): Disbursement
    {
        $disbursement = $this->getDisbursement(purchase: $purchase);
        $this->disbursementRepository->save($disbursement);
        $disbursementLine = $this->calculateDisbursementLine(purchase: $purchase, disbursement: $disbursement);
        $fees = $this->disbursementLineRepository->getFeeAmountSumByDisbursement($disbursement) + $disbursementLine->getFeeAmount();
        $disbursement->setFees($fees);
        $disbursement->setAmount(
            $this->disbursementLineRepository->getAmountSumByDisbursement($disbursement) +
            $disbursementLine->getAmount()
        );
        $this->disbursementLineRepository->save($disbursementLine);
        return $disbursement;
    }

    private function calculateDisbursementLine(Purchase $purchase, Disbursement $disbursement): DisbursementLine
    {
        $disbursementLine = $this->disbursementLineFactory->create(
            purchase: $purchase
        );
        $disbursementLine->setDisbursement($disbursement);
        $disbursementLine->setFeePercentage($this->calculateFeePercentage($purchase));
        $disbursementLine->setFeeAmount(round($purchase->getAmount() * $disbursementLine->getFeePercentage() / 100, 2));
        $disbursementLine->setAmount(round($purchase->getAmount() - $disbursementLine->getFeeAmount(),2));

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
     * @throws ConcurrentException
     * @throws InvalidDisbursementFrequencyException
     */
    private function getDisbursement(Purchase $purchase): Disbursement
    {
        $merchant = $purchase->getMerchant();
        $disbursementDate = $this->getDisbursementDate(
            merchant: $merchant,
            purchase: $purchase
        );
        try {
            $disbursement = $this->disbursementRepository->getByMerchantAndDisbursedDate(
                merchant: $merchant,
                disbursementDate: $disbursementDate
            );
            if (null === $disbursement) {
                $disbursement = $this->disbursementFactory->create(
                    merchant: $merchant,
                    disbursementDate: $purchase->getCreatedAt()
                );
                $this->checkPreviousMonthMinimumFeeAchieved(
                    disbursement: $disbursement,
                    purchase: $purchase
                );
            }
        }
        catch (\Exception $e) {
            throw new ConcurrentException($e->getMessage());
        }

        return $disbursement;
    }

    private function checkPreviousMonthMinimumFeeAchieved(Disbursement $disbursement, Purchase $purchase): void
    {
        $merchant = $disbursement->getMerchant();
        $lastDayOfPreviousMonth = clone $purchase->getCreatedAt();
        $lastDayOfPreviousMonth->modify('first day of this month');
        $lastDayOfPreviousMonth->modify('last day of previous month');
        $lastDayOfPreviousMonth->setTime(23, 59, 59);

        if ($merchant->getLiveOn() > $lastDayOfPreviousMonth) {
            return;
        }

        $lastMonthFees = $this->getLastMonthDisbursementFees($disbursement->getMerchant(), $purchase->getCreatedAt());
        if (
            $this->isFirstDisbursementOfTheMonth($disbursement)
            && $lastMonthFees < $disbursement->getMerchant()->getMinimumMonthlyFee()
        ) {
            $monthlyFee = $disbursement->getMerchant()->getMinimumMonthlyFee() - $lastMonthFees;
            $disbursement->setMonthlyFee($monthlyFee);
        }

    }

    /**
     * @throws InvalidDisbursementFrequencyException
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
                    $disbursementDate = $purchase->getCreatedAt()->modify('-'.$daysDifference.' days')->modify('+1 week');
                } elseif ($dayOfWeek > $purchaseDayOfWeek) {
                    $daysDifference = $dayOfWeek - $purchaseDayOfWeek;
                    $disbursementDate = $purchase->getCreatedAt()->modify('+'.$daysDifference.' days');
                } else {
                    $disbursementDate = $purchase->getCreatedAt();
                }

                return $disbursementDate;
            default:
                throw new InvalidDisbursementFrequencyException();
        }
    }

    private function isFirstDisbursementOfTheMonth(Disbursement $disbursement): bool
    {
        $disbursementsThisMonth = $this->disbursementRepository->countDisbursementsOfMonth(
            merchant: $disbursement->getMerchant(),
            dateTime: $disbursement->getDisbursedAt()
        );

        return $disbursementsThisMonth === 0;
    }

    private function getLastMonthDisbursementFees(Merchant $merchant, \DateTime $date): float
    {
        return $this->disbursementRepository->getSumOfLastMonthFees($merchant, $date);
    }
}
