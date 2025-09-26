<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'api_product_detail', methods: ['GET'])]
public function detail(int $id, ProductRepository $productRepository): JsonResponse
{
    $product = $productRepository->find($id);

    if (!$product) {
        throw new NotFoundHttpException('A termék nem található');
    }

    $data = [
        'id' => $product->getId(),
        'name' => $product->getName(),
        'price' => $product->getPrice(),
        'net_price' => $product->getNetPrice(),
        'category' => $product->getCategory(),
        'category_id' => $product->getCategoryId(),
        'image_url' => $product->getImageUrl(),
        'description' => $product->getDescription(),
    ];

    return $this->json($data);
}

    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function list(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'net_price' => $product->getNetPrice(),
                'category' => $product->getCategory(),
                'category_id' => $product->getCategoryId(),
                'image_url' => $product->getImageUrl(),
                'description' => $product->getDescription(),
            ];
        }

        return $this->json($data);
    }
}
