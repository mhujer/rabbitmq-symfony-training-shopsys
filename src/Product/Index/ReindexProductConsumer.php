<?php declare(strict_types = 1);

namespace App\Product\Index;

use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;

final class ReindexProductConsumer
    implements \OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface
{

    /** @var \App\Product\Index\ProductIndexingFacade */
    private $productIndexingFacade;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        ProductIndexingFacade $productIndexingFacade,
        EntityManagerInterface $entityManager
    )
    {
        $this->productIndexingFacade = $productIndexingFacade;
        $this->entityManager = $entityManager;
    }

    public function execute(AMQPMessage $message)
    {
        // clear identity map to prevent stale data
        $this->entityManager->clear();

        $productIdPayload = $message->getBody();
        $productId = Uuid::fromString($productIdPayload);

        echo 'Will index product ' . $productIdPayload . "\n";

        try {
            $product = $this->productIndexingFacade->reindexProduct($productId);
        } catch (\Exception $e) { // @todo should be specific exception!
            echo 'Indexing failed, requeueing' . "\n";

            sleep(2);

            return ConsumerInterface::MSG_REJECT_REQUEUE;
        }
        echo 'Indexed product ' . $product->getName() . "\n";

        return ConsumerInterface::MSG_ACK;
    }
}
