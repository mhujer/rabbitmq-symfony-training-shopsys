<?php declare(strict_types = 1);

namespace App\Product;

use Doctrine\ORM\EntityManagerInterface;

final class ProductFacade
{

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
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
        $this->entityManager->flush();

        return $product;
    }
}
