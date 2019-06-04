<?php declare(strict_types = 1);

namespace App\Product\Index;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;

final class ReindexProductConsumer
    implements \OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface
{

    /** @var \App\Product\Index\ProductIndexingFacade */
    private $productIndexingFacade;

    public function __construct(
        ProductIndexingFacade $productIndexingFacade
    )
    {
        $this->productIndexingFacade = $productIndexingFacade;
    }

    public function execute(AMQPMessage $message)
    {
        $productIdPayload = $message->getBody();
        $productId = Uuid::fromString($productIdPayload);

        echo 'Will index product ' . $productIdPayload . "\n";
        $product = $this->productIndexingFacade->reindexProduct($productId);
        echo 'Indexed product ' . $product->getName() . "\n";

        return ConsumerInterface::MSG_ACK;
    }
}
