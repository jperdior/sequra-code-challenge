<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\NoSql\Repository;

use App\LandingPageGenerator\Infrastructure\Storage\Mongo\AbstractMongoRepository;
use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use MongoDB\Collection;
use Symfony\Component\Serializer\SerializerInterface;

class PurchaseRepository extends AbstractMongoRepository
{

    private Collection $collection;

    public function __construct(
        private readonly SerializerInterface $serializer
    )
    {
        parent::__construct();
        $this->collection = $this->mongoDbDatabase->selectCollection('purchases');
    }

    public function save(Purchase $purchase): void
    {
        $this->collection->replaceOne([
            '_id' => $purchase->getId(),
        ],
            $this->serializer->normalize($purchase),
            [
                'upsert' => true,
            ]);
    }

    public function getOldestPendingPurchase(): ?Purchase
    {

        $purchase = $this->collection->findOne([
            'status' => [
                '$exists' => false,
            ],
        ],
            [
                'sort' => [
                    'createdAt' => 1,
                ],
            ]);

        if ($purchase) {
            $purchase = json_encode($purchase);
            dump($purchase);die;
            $purchaseEntity = new Purchase();
            $purchaseEntity->setId($purchase['id']);
            $purchaseEntity->setCreatedAt(new \DateTime($purchase['createdAt']));
        }
        return null;
    }
}