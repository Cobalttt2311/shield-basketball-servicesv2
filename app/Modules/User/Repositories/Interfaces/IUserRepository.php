<?php

namespace App\Modules\User\Repositories\Interfaces;

use App\Modules\User\Models\User;
use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;

interface IUserRepository
{
    public function findByLogin(string $login): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): ?User;
    public function delete(int $id): bool;
    public function findByEmail(string $email): ?User;
    public function getProfileByUser(User $user);

    public function updateCoachProfile(
        int $userId,
        array $data
    ): Coach;

    public function updatePlayerProfile(
        int $userId,
        array $data
    ): Player;
}