<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testCreateOrderEndpoint(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'http://nginx:80/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'items' => [
                    ['productId' => '1', 'productName' => 'Product A', 'price' => 3.3, 'quantity' => 2],
                    ['productId' => '2', 'productName' => 'Product B', 'price' => 50,  'quantity' => 1],
                ]
            ])
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Order creation should return HTTP 201.');

        // Optionally, decode the response and check values.
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals(56.6, $data['total']);
        $this->assertEquals('new', $data['status']);
    }

    public function testGetOrderEndpoint(): void
    {
        $client = static::createClient();

        // First, create an order.
        $client->request(
            'POST',
            '/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'items' => [
                    ['productId' => '1', 'productName' => 'Product A', 'price' => 100, 'quantity' => 2],
                    ['productId' => '2', 'productName' => 'Product B', 'price' => 50,  'quantity' => 1],
                ]
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $orderId = $data['id'];

        // Now, test getting the order details.
        $client->request('GET', '/orders/' . $orderId);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Fetching an order should return HTTP 200.');

        $orderData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($orderId, $orderData['id']);
    }

    public function testUpdateOrderStatusEndpoint(): void
    {
        $client = static::createClient();

        // First, create an order.
        $client->request(
            'POST',
            '/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'items' => [
                    ['productId' => '1', 'productName' => 'Product A', 'price' => 100, 'quantity' => 2]
                ]
            ])
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $orderId = $data['id'];

        // Now, patch to update the status.
        $client->request(
            'PATCH',
            '/orders/' . $orderId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['status' => 'paid'])
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $updatedData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('paid', $updatedData['status']);
    }
}
