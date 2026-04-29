<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;
use App\Modules\User\Repositories\Interfaces\IUserRepository;

class UserRepository implements IUserRepository
{
    public function findByLogin(string $login): ?User
    {
        return User::where(function ($query) use ($login) {
            $query->where('email', $login)
                  ->orWhere('username', $login);
        })->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): ?User
    {
        /** @var User|null $user */
        $user = User::find($id);

        if (!$user) return null;

        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        /** @var User|null $user */
        $user = User::find($id);

        if (!$user) return false;

        return $user->delete();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}