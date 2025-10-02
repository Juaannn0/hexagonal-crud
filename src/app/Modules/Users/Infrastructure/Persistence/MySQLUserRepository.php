<?php

namespace Modules\User\Infrastructure\Persistence;

use CodeIgniter\Database\ConnectionInterface;
use Modules\User\Domain\Entities\User;
use Modules\User\Domain\ValueObjects\Email;
use Modules\User\Domain\Ports\UserRepositoryInterface;

class MySQLUserRepository implements UserRepositoryInterface
{
    private ConnectionInterface $db;
    private string $table = 'users';

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function save(User $user): User
    {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmailValue(),
            'password' => $user->getPassword(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
        ];

        $builder = $this->db->table($this->table);
        $builder->insert($data);

        $userId = $this->db->insertID();

        return new User(
            id: $userId,
            name: $user->getName(),
            email: $user->getEmail(),
            password: $user->getPassword(),
            createdAt: $user->getCreatedAt()
        );
    }

    public function findById(int $id): ?User
    {
        $builder = $this->db->table($this->table);
        $result = $builder->where('id', $id)->get()->getRowArray();

        if (!$result) {
            return null;
        }

        return $this->mapToEntity($result);
    }

    public function findByEmail(string $email): ?User
    {
        $builder = $this->db->table($this->table);
        $result = $builder->where('email', $email)->get()->getRowArray();

        if (!$result) {
            return null;
        }

        return $this->mapToEntity($result);
    }

    public function findAll(int $limit = 100, int $offset = 0): array
    {
        $builder = $this->db->table($this->table);
        $results = $builder
            ->limit($limit, $offset)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return array_map(fn($row) => $this->mapToEntity($row), $results);
    }

    public function update(User $user): User
    {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmailValue(),
            'updated_at' => $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];

        // Solo actualizar password si fue modificado
        if ($user->getPassword()) {
            $data['password'] = $user->getPassword();
        }

        $builder = $this->db->table($this->table);
        $builder->where('id', $user->getId())->update($data);

        return $user;
    }

    public function delete(int $id): bool
    {
        $builder = $this->db->table($this->table);
        return $builder->where('id', $id)->delete();
    }

    public function exists(int $id): bool
    {
        $builder = $this->db->table($this->table);
        return $builder->where('id', $id)->countAllResults() > 0;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $builder = $this->db->table($this->table);
        $builder->where('email', $email);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    private function mapToEntity(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            name: $row['name'],
            email: new Email($row['email']),
            password: $row['password'],
            createdAt: new \DateTime($row['created_at']),
            updatedAt: $row['updated_at'] ? new \DateTime($row['updated_at']) : null
        );
    }
}