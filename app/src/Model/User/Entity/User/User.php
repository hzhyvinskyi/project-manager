<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @Assert/Uuid
     * @var Id
     */
    private Id $id;

    /**
     * @var Email
     */
    private Email $email;

    /**
     * @var string
     */
    private string $passwordHash;

    /**
     * @var string|null
     */
    private ?string $confirmationToken;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $creationDate;

    /**
     * @var string
     */
    private string $status;

    public function __construct(Id $id, Email $email, string $hash, string $token, DateTimeImmutable $dateTime)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmationToken = $token;
        $this->creationDate = $dateTime;
        $this->status = self::STATUS_WAIT;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmationToken = null;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }
}
