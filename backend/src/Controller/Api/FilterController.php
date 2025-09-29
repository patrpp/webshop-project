<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FilterController extends AbstractController
{
    #[Route('/api/products/filter', name: 'api_products_filter', methods: ['GET'])]
    public function filter(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $query = $request->query->get('q');
        $season = $request->query->get('season');
        $diameter = $request->query->get('diameter');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = (int) $request->query->get('limit', 24);
        $offset = ($page - 1) * $limit;

        $products = $productRepository->findByFilters($query, $season, $diameter, $limit, $offset);

        $total = $productRepository->countByFilters($query, $season, $diameter);

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

        return $this->json([
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
        ]);
    }
}
