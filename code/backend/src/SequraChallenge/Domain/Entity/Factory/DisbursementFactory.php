<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity\Factory;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Identifiers\UniqueIdGeneratorInterface;

class DisbursementFactory
{

    public function __construct(
        private UniqueIdGeneratorInterface $uniqueIdGenerator
    ) {
    }

    public function create(
        Merchant $merchant,
        \DateTime $disbursementDate,
    ): Disbursement
    {
        $disbursement = new Disbursement();
        $disbursement->setId($this->uniqueIdGenerator->generateUlid());
        $disbursement->setReference($disbursement->getId());
        $disbursement->setMerchant($merchant);
        $disbursement->setCreatedAt(new \DateTime());
        $disbursement->setDisbursedAt($disbursementDate);
        return $disbursement;
    }



}