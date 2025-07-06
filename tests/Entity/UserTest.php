<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testDefaultRoleIsUser(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testEmailCanBeSet(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertSame('test@example.com', $user->getEmail());
    }
}
