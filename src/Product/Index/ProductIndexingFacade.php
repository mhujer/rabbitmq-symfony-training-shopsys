<?php declare(strict_types = 1);

namespace App\Product\Index;

use App\Product\Product;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class ProductIndexingFacade
{

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function reindexProduct(UuidInterface $productId): Product
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        // do some hard indexing here
        usleep(300 * 1000);

        return $product;
    }
}
