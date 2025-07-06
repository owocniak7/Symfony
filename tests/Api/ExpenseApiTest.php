<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpenseApiTest extends WebTestCase
{
    public function testUnauthorizedExpenseList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/expenses');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testLoginAndGetExpenses(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1@example.com',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $client->request('GET', '/api/expenses');
        $this->assertResponseIsSuccessful();
    }
}
