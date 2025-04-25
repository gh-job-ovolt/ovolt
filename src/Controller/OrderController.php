<?php
namespace App\Controller;

use App\Presenter\SimpleOrderPresenter;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    #[Route('/orders', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $order = $this->orderService->createOrder($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(SimpleOrderPresenter::createOrderPresenter($order), 201);
    }

    #[Route('/orders/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(string $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        // Return simplified order details
        return new JsonResponse(SimpleOrderPresenter::getOrderPresenter($order));
    }

    #[Route('/orders/{id}', name: 'update_order_status', methods: ['PATCH'])]
    public function updateOrderStatus(Request $request, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newStatus = $data['status'] ?? '';

        try {
            $order = $this->orderService->updateOrderStatus($id, $newStatus);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        return new JsonResponse(SimpleOrderPresenter::updateOrderStatusPresenter($order));
    }
}
