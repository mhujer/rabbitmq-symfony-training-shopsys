<?php declare(strict_types = 1);

namespace App\Product;

use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

final class ProductFacade
{

    /** @var \OldSound\RabbitMqBundle\RabbitMq\ProducerInterface */
    private $reindexProductProducer;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        ProducerInterface $reindexProductProducer,
        EntityManagerInterface $entityManager
    )
    {
        $this->reindexProductProducer = $reindexProductProducer;
        $this->entityManager = $entityManager;
    }

    public function createProduct(
        string $name,
        int $price
    ): Product
    {
        $product = new Product(
            $name,
            $price
        );

        $this->entityManager->persist($product);
        $this->reindexProductProducer->publish($product->getId()->toString());

        $this->entityManager->flush();

        return $product;
    }
}
