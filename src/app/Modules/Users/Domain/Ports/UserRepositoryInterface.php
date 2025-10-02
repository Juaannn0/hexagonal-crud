<?php

namespace Modules\User\Domain\Ports;

use Modules\User\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;
    
    public function findById(int $id): ?User;
    
    public function findByEmail(string $email): ?User;
    
    public function findAll(int $limit = 100, int $offset = 0): array;
    
    public function update(User $user): User;
    
    public function delete(int $id): bool;
    
    public function exists(int $id): bool;
    
    public function emailExists(string $email, ?int $excludeId = null): bool;
}
