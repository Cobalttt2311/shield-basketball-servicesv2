<?php

namespace App\Modules\User\Repositories\Interfaces;

use App\Modules\User\Models\User;

interface IUserRepository
{
    public function findByLogin(string $login): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): ?User;

    public function delete(int $id): bool;

    public function findByEmail(string $email): ?User;
}
