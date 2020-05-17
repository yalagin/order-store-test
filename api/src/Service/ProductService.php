<?php

namespace App\Service;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Repository\ProductRepository;

class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getOderProducts($itemMax, $itemMin, $priceMax, $priceMin, $page): ?Paginator
    {
        $oderProducts = $this->productRepository->getOderProducts(
            $itemMax, $itemMin, $priceMax, $priceMin, $page
        );

        $oderProducts->count();
        return $oderProducts;
    }
}
