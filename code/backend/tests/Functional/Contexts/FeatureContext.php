<?php

namespace App\Tests\Functional\Contexts;

use App\SequraChallenge\DisbursementLines\Domain\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Disbursements\Domain\DisbursementRepositoryInterface;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeAmount;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeId;
use App\SequraChallenge\MerchantMonthlyFees\Domain\MerchantMonthlyFeeRepositoryInterface;
use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantDisbursementFrequency;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantLiveOn;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantMinimumMonthlyFee;
use App\SequraChallenge\Merchants\Domain\Repository\MerchantRepositoryInterface;
use App\SequraChallenge\Purchases\Domain\Events\PurchaseCreatedEvent;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Event\EventBus;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Defines application features from the specific context.
 */
readonly class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(
        private EventBus $eventBus,
        private MerchantRepositoryInterface $merchantRepository,
        private DisbursementRepositoryInterface $disbursementRepository,
        private DisbursementLineRepositoryInterface $disbursementLineRepository,
        private MerchantMonthlyFeeRepositoryInterface $merchantMonthlyFeeRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /** @BeforeScenario */
    public function cleanDatabase(BeforeScenarioScope $scope)
    {
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE disbursement_line');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE disbursement');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE merchant');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE merchant_monthly_fee');
    }

    /**
     * @Given there's these merchants in the database:
     */
    public function theresTheseMerchantsInTheDatabase(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->merchantRepository->save(
                new Merchant(
                    new MerchantReference($row['reference']),
                    new MerchantLiveOn(new \DateTime($row['liveOn'])),
                    new MerchantDisbursementFrequency($row['disbursementFrequency']),
                    new MerchantMinimumMonthlyFee((float) $row['minimumMonthlyFee'])
                )
            );
        }
    }

    /**
     * @When I receive a :size purchase event with body:
     */
    public function iReceiveAPurchaseEventWithBody(string $size, PyStringNode $body)
    {
        $event = PurchaseCreatedEvent::fromPrimitives(
            $size.'-purchase',
            json_decode($body, true),
            'eventId',
            'occurredOn'
        );

        $this->eventBus->publish($event);
    }

    /**
     * @Then A disbursement with merchantReference :merchantReference to disburse on :disbursedAt containing fee :fee and amount :amount should be created
     */
    public function aDisbursementToDisburseOnContainingFeeAndAmountShouldBeCreated(string $merchantReference, string $disbursedAt, float $fee, float $amount): void
    {
        $disbursement = $this->disbursementRepository->getByMerchantAndDisbursedDate(
            new MerchantReference($merchantReference), new DisbursementDisbursedAt(new \DateTime($disbursedAt)));

        if (null === $disbursement) {
            throw new \Exception('Disbursement not found');
        }

        if ($disbursement->fee()->value !== $fee) {
            throw new \Exception('Fee does not match: Expected '.$fee.' but got '.$disbursement->fee()->value);
        }

        if ($disbursement->amount()->value !== $amount) {
            throw new \Exception('Amount does not match: Expected '.$amount.' but got '.$disbursement->amount()->value);
        }
    }

    /**
     * @Then A disbursement line with purchaseId :purchaseId with fee :lineFee, amount :lineAmount and fee percentage :feePercentage should be created
     */
    public function aDisbursementLineWithFeeAmountAndFeePercentageShouldBeCreated(string $purchaseId, float $lineFee, float $lineAmount, int $feePercentage): void
    {
        $disbursementLine = $this->disbursementLineRepository->findByPurchaseId($purchaseId);

        if (null === $disbursementLine) {
            throw new \Exception('Disbursement line not found');
        }

        if ($disbursementLine->feeAmount->value !== $lineFee) {
            throw new \Exception('Fee does not match: Expected '.$lineFee.' but got '.$disbursementLine->feeAmount->value);
        }

        if ($disbursementLine->amount->value !== $lineAmount) {
            throw new \Exception('Amount does not match: Expected '.$lineAmount.' but got '.$disbursementLine->amount->value);
        }

        if ($disbursementLine->feePercentage->value !== $feePercentage) {
            throw new \Exception('Fee percentage does not match: Expected '.$feePercentage.' but got '.$disbursementLine->feePercentage->value);
        }
    }

    /**
     * @When I receive these purchase created events:
     */
    public function iReceiveThesePurchaseCreatedEvents(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $body['merchantReference'] = $row['merchantReference'];
            $body['amount'] = floatval($row['amount']);
            $body['createdAt'] = $row['createdAt'];
            $event = PurchaseCreatedEvent::fromPrimitives(
                $row['purchaseId'],
                $body,
                'eventId',
                'occurredOn'
            );

            $this->eventBus->publish($event);
        }
    }

    /**
     * @Given there's a merchant monthly fee in the database with reference :merchantReference, date :month and fee amount :feeAmount
     */
    public function theresAMerchantMonthlyFeeInTheDatabaseWithReferenceDateAndFeeAmount(string $merchantReference, string $month, float $feeAmount)
    {
        $merchantMonthlyFee = new MerchantMonthlyFee(
            new MerchantMonthlyFeeId(MerchantMonthlyFeeId::random()->value),
            new MerchantReference($merchantReference),
            new MerchantMonthlyFeeFirstDayOfMonth(new \DateTime($month)),
            new MerchantMonthlyFeeAmount($feeAmount)
        );
        $this->merchantMonthlyFeeRepository->save($merchantMonthlyFee);
    }

    /**
     * @When I receive a purchase event with body:
     */
    public function iReceiveAPurchaseEventWithBody2(PyStringNode $body)
    {
        $event = PurchaseCreatedEvent::fromPrimitives(
            'purchase',
            json_decode($body, true),
            'eventId',
            'occurredOn'
        );

        $this->eventBus->publish($event);
    }

    /**
     * @Then A disbursement with merchantReference :merchantReference to disburse on :disbursedAt with fee :fee, amount :amount, monthly fee :monthlyFee and flagged as first of month :firstOfMonth should be created
     */
    public function aDisbursementWithMerchantReferenceToDisburseOnWithFeeAmountMonthlyFeeAndFlaggedAsFirstOfMonthTrueShouldBeCreated(
        string $merchantReference,
        string $disbursedAt,
        float $fee,
        float $amount,
        float $monthlyFee,
        bool $firstOfMonth
    ) {
        $disbursement = $this->disbursementRepository->getByMerchantAndDisbursedDate(
            new MerchantReference($merchantReference), new DisbursementDisbursedAt(new \DateTime($disbursedAt)));

        if (null === $disbursement) {
            throw new \Exception('Disbursement not found');
        }

        if ($disbursement->fee()->value !== $fee) {
            throw new \Exception('Fee does not match: Expected '.$fee.' but got '.$disbursement->fee()->value);
        }

        if ($disbursement->amount()->value !== $amount) {
            throw new \Exception('Amount does not match: Expected '.$amount.' but got '.$disbursement->amount()->value);
        }

        if ($disbursement->monthlyFee()->value !== $monthlyFee) {
            throw new \Exception('Monthly fee does not match: Expected '.$monthlyFee.' but got '.$disbursement->monthlyFee()->value);
        }

        if ($disbursement->firstOfMonth !== $firstOfMonth) {
            throw new \Exception('First of month does not match: Expected '.$firstOfMonth.' but got '.$disbursement->firstOfMonth);
        }
    }
}
