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

    private $orders = [];

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return int
     */
    private function getTotalSumOfOrder(): int
    {
        $totalSum = 0;
        if (!empty($this->orders)) {
            foreach ($this->orders as $product) {
                $totalSum += $product['price'];
            }
        }
        return $totalSum;
    }

    /**
     * @return int
     */
    private function getAmountOfOrder(): int
    {
        return count($this->orders);
    }

    public function getOderProducts($itemMax, $itemMin, $priceMax, $priceMin)
    {
        $this->orders = $this->productRepository->getMaximumCheapOrders($itemMax);

        if ($this->checkIfOrderPriceIsGraterThanMaximumPrice($priceMax)) {
            $this->lowerItemsToFitMaximumPrice($itemMax, $priceMax);
        } else {
            $this->orders = $this->productRepository->getAllOrders();
            $this->scaleItemsCost($itemMax -1, $priceMax);
        }

        $amountOfOrder = $this->getAmountOfOrder();
        $totalSumOfOrder = $this->getTotalSumOfOrder();
        return $amountOfOrder >= $itemMin ? $totalSumOfOrder >= $priceMin ? $this->orders : null : null;
    }

    /**
     * @param $priceMax
     * @return bool
     */
    public function checkIfOrderPriceIsGraterThanMaximumPrice($priceMax): bool
    {
        return ($this->getTotalSumOfOrder() >= $priceMax);
    }

    private function lowerItemsToFitMaximumPrice($itemMax, $priceMax)
    {
        $this->orders = array_slice($this->orders, 0, $itemMax);
        if ($this->checkIfOrderPriceIsGraterThanMaximumPrice($priceMax)) {
            $this->lowerItemsToFitMaximumPrice($itemMax -1 , $priceMax);
        }
    }

    private $totalScalableSum = 0;

    private function scaleItemsCost($itemMax, $priceMax, $offset = 0)
    {
        $order = array_slice($this->orders, $offset + 1, $itemMax+1);

        $totalSum = 0;
        if (!empty($order)) {
            foreach ($order as $product) {
                $totalSum += $product['price'];
            }
        }
        $test = 100;
        if($itemMax+$offset=== 5000){
            $test = 15;
        }

        if ($itemMax+$offset + 1 > $this->getAmountOfOrder() || $totalSum > $priceMax) {
            $this->orders = array_slice($this->orders, $offset, $itemMax+1);
        } else {
            $this->scaleItemsCost($itemMax, $priceMax, $offset + 1);
        }
    }
}
