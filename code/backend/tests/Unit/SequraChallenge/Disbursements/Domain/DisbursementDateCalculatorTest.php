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

    public function testItShouldCalculateADisbursementDateForADailyMerchant(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::DAILY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-01')
        );

        $this->assertEquals(new \DateTime('2021-01-01'), $disbursementDate->value);
    }

    public function testItShouldCalculateADisbursementDateForAWeeklyMerchantSameDayOfWeek(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::WEEKLY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-01')
        );

        $this->assertEquals(new \DateTime('2021-01-01'), $disbursementDate->value);
    }

    public function testItShouldCalculateADisbursementDateForAWeeklyMerchantForNextWeek(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::WEEKLY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-11')
        );

        $this->assertEquals(new \DateTime('2021-01-15'), $disbursementDate->value);
    }

    public function testItShouldCalculateADisbursementDateForAWeeklyMerchantForSameWeek(): void
    {
        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: MerchantDisbursementFrequency::WEEKLY,
            merchantLiveOnDate: new \DateTime('2021-01-01'),
            purchaseCreatedAt: new \DateTime('2021-01-05')
        );

        $this->assertEquals(new \DateTime('2021-01-08'), $disbursementDate->value);
    }
}
