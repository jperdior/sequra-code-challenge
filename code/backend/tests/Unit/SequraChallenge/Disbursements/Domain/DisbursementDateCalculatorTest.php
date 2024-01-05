<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantDisbursementFrequency;
use PHPUnit\Framework\TestCase;

class DisbursementDateCalculatorTest extends TestCase
{

    private DisbursementDateCalculator $disbursementDateCalculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->disbursementDateCalculator = new DisbursementDateCalculator();
    }

    public function test_it_should_calculate_a_disbursement_date_for_a_daily_merchant(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::DAILY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-01')
        );

        $this->assertEquals(new \DateTime('2021-01-01'), $disbursementDate->value);
    }

    public function test_it_should_calculate_a_disbursement_date_for_a_weekly_merchant_same_day_of_week(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::WEEKLY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-01')
        );

        $this->assertEquals(new \DateTime('2021-01-01'), $disbursementDate->value);
    }

    public function test_it_should_calculate_a_disbursement_date_for_a_weekly_merchant_for_next_week(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::WEEKLY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-11')
        );

        $this->assertEquals(new \DateTime('2021-01-15'), $disbursementDate->value);
    }

    public function test_it_should_calculate_a_disbursement_date_for_a_weekly_merchant_for_same_week(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::WEEKLY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-05')
        );

        $this->assertEquals(new \DateTime('2021-01-08'), $disbursementDate->value);
    }

}