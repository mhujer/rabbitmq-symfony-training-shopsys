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

        // not a good example, I just need a DB update/insert here
        $productCopy = new Product($product->getName() . '-2', $product->getPrice());
        $this->entityManager->persist($productCopy);
        $this->entityManager->flush();


        if (random_int(1, 100) < 50) {
            //throw new \Exception('Service is not available, processing failed');
            $productClone = clone $product;
            $this->entityManager->persist($productClone);
            $this->entityManager->flush();
        }

        // do some hard indexing here
        usleep(300 * 1000);

        return $product;
    }
}
