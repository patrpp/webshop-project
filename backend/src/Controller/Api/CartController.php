<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cart', name: 'api_cart_')]
class CartController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('', name: 'get', methods: ['GET'])]
    public function getCart(SessionInterface $session): JsonResponse
    {
        $cart = $session->get('cart', []);

        foreach ($cart as $id => $item) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $cart[$id]['image'] = $product->getImageUrl();
            }
        }

        $total = $this->calculateTotal($cart);

        $session->set('cart', $cart);

        return $this->json([
            'items' => array_values($cart),
            'total' => $total,
        ]);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addItem(Request $request, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id'], $data['name'], $data['price'])) {
            return $this->json(['error' => 'Hiányzó adatok a kosárhoz adáshoz'], 400);
        }

        $id = $data['id'];
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $product = $this->productRepository->find($id);

            $cart[$id] = [
                'id' => $id,
                'name' => $data['name'],
                'price' => $data['price'],
                'image' => $product ? $product->getImageUrl() : null,
                'quantity' => 1,
            ];
        }

        $session->set('cart', $cart);
        $total = $this->calculateTotal($cart);

        return $this->json([
            'items' => array_values($cart),
            'total' => $total,
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateItem(int $id, Request $request, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'] ?? 0;

        $cart = $session->get('cart', []);

        if (!isset($cart[$id])) {
            return $this->json(['error' => 'A termék nem található a kosárban'], 404);
        }

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['quantity'] = $quantity;

            $product = $this->productRepository->find($id);
            if ($product) {
                $cart[$id]['image'] = $product->getImageUrl();
            }
        }

        $session->set('cart', $cart);
        $total = $this->calculateTotal($cart);

        return $this->json([
            'items' => array_values($cart),
            'total' => $total,
        ]);
    }

    #[Route('/{id}', name: 'remove', methods: ['DELETE'])]
    public function removeItem(int $id, SessionInterface $session): JsonResponse
    {
        $cart = $session->get('cart', []);

        if (!isset($cart[$id])) {
            return $this->json(['error' => 'A termék nem található a kosárban'], 404);
        }

        unset($cart[$id]);
        $session->set('cart', $cart);

        $total = $this->calculateTotal($cart);

        return $this->json([
            'items' => array_values($cart),
            'total' => $total,
        ]);
    }
    #[Route('/clear', name: 'clear', methods: ['POST'])]
    public function clear(SessionInterface $session): JsonResponse
    {
        $session->remove('cart');

        return $this->json([
            'items' => [],
            'total' => 0,
        ]);
    }


    private function calculateTotal(array $cart): float
    {
        $total = 0.0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
