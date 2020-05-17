<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;

class ProductAction extends AbstractController
{
    public function __invoke(Request $request, ProductRepository $productRepository,ProductService $productService)
    {
        $page = (int)$request->query->get('page', 1);

        if (!$request->get('item_max')
            || !$request->get('item_min')
            || !$request->get('price_min')
            || !$request->get('price_max')) {
            return $productRepository->getAllProducts($page);
        }

        return $productService->getOderProducts(
            $request->get('item_max'),
            $request->get('item_min'),
            $request->get('price_max'),
            $request->get('price_min'),
            $page
        );
    }
}
