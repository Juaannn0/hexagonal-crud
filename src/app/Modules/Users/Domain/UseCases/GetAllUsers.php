<?php

namespace Modules\User\Domain\UseCases;

use Modules\User\Domain\Ports\UserRepositoryInterface;

class GetAllUsers
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $limit = 100, int $offset = 0): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}