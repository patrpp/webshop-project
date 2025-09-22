<?php
// src/Controller/ProductController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products')]
    public function getProducts(): JsonResponse
    {
        $products = [
            ['id' => 1, 'name' => 'Alma'],
            ['id' => 2, 'name' => 'Körte'],
        ];

        return $this->json($products);
    }
}