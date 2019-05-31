<?php declare(strict_types = 1);

namespace App\Product;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
final class Product
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $price;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    private $createdTime;

    public function __construct(
        string $name,
        int $price
    )
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->price = $price;
        $this->createdTime = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

}
