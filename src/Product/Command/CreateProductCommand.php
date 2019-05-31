<?php declare(strict_types = 1);

namespace App\Product\Command;

use App\Product\ProductFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateProductCommand extends Command
{

    /** @var \App\Product\ProductFacade */
    private $productFacade;

    public function __construct(
        ProductFacade $productFacade
    )
    {
        parent::__construct();
        $this->productFacade = $productFacade;
    }

    public function configure(): void
    {
        $this->setName('app:create-product');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $product = $this->productFacade->createProduct(
            'Great thing! ' . date('Y-m-d H:i:s'),
            random_int(100, 1000)
        );

        $output->writeln(sprintf('Created product %s', $product->getId()->toString()));
    }

}
