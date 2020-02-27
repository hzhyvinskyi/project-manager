<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Id::next(),
            $email = new Email('test@test.com'),
            $passwordHash = 'hash',
            $token = 'token',
            $date = new \DateTimeImmutable()
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($passwordHash, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmationToken());
        self::assertEquals($date, $user->getCreationDate());
    }
}
