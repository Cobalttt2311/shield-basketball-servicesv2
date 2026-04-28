<?php

namespace App\Modules\User\Services\Interfaces;

use App\Modules\User\Models\User;

interface IUserService
{
    public function login(string $login, string $password): ?array;
    public function logout(User $user): bool;
    public function createUser(array $data): array;
    public function deleteUser(int $id): bool;
}