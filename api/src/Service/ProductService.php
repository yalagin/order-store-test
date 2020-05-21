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
        $totalSumOfOrder9 = $this->getTotalSumOfOrder();


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

    private $shiftedItem = null;

    private function scaleItemsCost($itemMax, $priceMax)
    {

        $order = array_slice($this->orders, null, $itemMax);

        $totalSum = 0;
        if (!empty($order)) {
            foreach ($order as $product) {
                $totalSum += $product['price'];
            }
        }

        $amountOfOrder = $this->getAmountOfOrder();
        if ($itemMax >= $amountOfOrder || $totalSum > $priceMax) {
            if($this->shiftedItem) {
                $order = array_slice($this->orders, null, $itemMax);
                array_unshift($order, $this->shiftedItem);
            }
            $this->orders = $order;
        } else {
            $this->shiftedItem = array_shift($this->orders);
            $this->scaleItemsCost($itemMax, $priceMax);
        }
    }
}
