<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

class Email
{
    /**
     * @var string
     */
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect email.');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
