<?php
// tests/Service/OrderServiceTest.php

namespace App\Tests\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use App\Service\Validator\OrderValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class OrderServiceTest extends TestCase
{
    private OrderRepository $repository;
    private OrderValidator $orderValidator;
    private OrderService $orderService;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(OrderRepository::class);
        $this->orderValidator  = $this->createMock(OrderValidator::class);

        $this->repository->expects($this->any())
            ->method('add');

        // Create the service with the mocked repository.
        $this->orderService = new OrderService($this->repository, $this->orderValidator);
    }

    public function testCreateOrderCalculatesTotalCorrectly(): void
    {
        // with
        $orderData = [
            'items' => [
                ['productId' => '1', 'productName' => 'Product A', 'price' => 100, 'quantity' => 2],
                ['productId' => '2', 'productName' => 'Product B', 'price' => 50,  'quantity' => 1],
            ],
        ];

        // when
        $order = $this->orderService->createOrder($orderData);

        // then
        $this->assertEquals(25000, $order->getTotal());
        $this->assertEquals(OrderStatus::NEW, $order->getStatus());
        $this->assertCount(2, $order->getItems());
    }

    public function testUpdateOrderStatusValid(): void
    {
        // with
        $someUUid = Uuid::v4();
        $order = new Order();
        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($someUUid->toString())
            ->willReturn($order);

        // when
        $updatedOrder = $this->orderService->updateOrderStatus($someUUid->toString(), 'paid');

        // then
        $this->assertInstanceOf(Order::class, $updatedOrder);
        $this->assertEquals(OrderStatus::PAID, $updatedOrder->getStatus());
    }

    public function testUpdateOrderStatusInvalidShouldThrowException(): void
    {
        // then
        $this->expectException(\InvalidArgumentException::class);

        // when
        $this->orderService->updateOrderStatus('non-existing-id', 'invalid_status');
    }
}