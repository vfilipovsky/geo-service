<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testValidInstantiation(): void
    {
        $password = 'password';
        $email = 'john@doe.com';
        $role = User::USER;
        $user = new User();
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setRoles([$role]);

        $this->assertEquals($password, $user->getPassword(), 'Passwords should match');
        $this->assertEquals($email, $user->getEmail(), 'Emails should match');
        $this->assertEquals($role, $user->getRoles()[0], 'Should have a role');
    }
}