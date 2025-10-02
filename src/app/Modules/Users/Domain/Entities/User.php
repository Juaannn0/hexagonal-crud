<?php

namespace Modules\User\Domain\Entities;

use Modules\User\Domain\ValueObjects\Email;

class User
{
    private ?int $id;
    private string $name;
    private Email $email;
    private string $password;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        ?int $id,
        string $name,
        Email $email,
        string $password,
        ?\DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getEmailValue(): string
    {
        return $this->email->getValue();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // Business logic
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email->getValue(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}