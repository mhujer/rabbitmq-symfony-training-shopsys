<?php declare(strict_types = 1);

namespace App\Product\Index;

use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\DequeuerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;

final class ReindexProductConsumer // ConsumerCallback
    implements \OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface
{

    /** @var \App\Product\Index\ProductIndexingFacade */
    private $productIndexingFacade;

    /** @var \OldSound\RabbitMqBundle\RabbitMq\DequeuerInterface */
    private $dequeuer;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        ProductIndexingFacade $productIndexingFacade,
        DequeuerInterface $dequeuer,
        EntityManagerInterface $entityManager
    )
    {
        $this->productIndexingFacade = $productIndexingFacade;
        $this->dequeuer = $dequeuer;
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
        } catch (\RuntimeException $e) { // @todo should be specific exception!
            echo $e->getMessage() . "\n";
            echo 'Indexing failed, requeueing' . "\n";

            sleep(2);

            return ConsumerInterface::MSG_REJECT_REQUEUE;
        } finally {
            if (!$this->entityManager->isOpen()) {
                echo 'EM is closed, shutting down' . "\n";
                $this->dequeuer->forceStopConsumer();
                sleep(2);
            }
        }
        echo 'Indexed product ' . $product->getName() . "\n";

        return ConsumerInterface::MSG_ACK;
    }
}
