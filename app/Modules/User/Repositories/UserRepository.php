<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;
use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
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

        if (! $user) {
            return null;
        }

        $user->update($data);

        return $user;
    }

    public function delete(int $id): bool
    {
        /** @var User|null $user */
        $user = User::find($id);

        if (! $user) {
            return false;
        }

        return $user->delete();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getProfileByUser(User $user)
    {
        if ($user->role === 'coach') {

            $coach = Coach::where('user_id', $user->id)
                ->first();

            return [
                'role' => 'coach',
                'profile' => $coach
            ];
        }

        if ($user->role === 'player') {

            $player = Player::where('user_id', $user->id)
                ->first();

            return [
                'role' => 'player',
                'profile' => $player
            ];
        }

        return [
            'role' => $user->role,
            'profile' => $user
        ];
    }

    public function updateCoachProfile(
        int $userId,
        array $data
    ): Coach
    {
        /** @var Coach $coach */
        $coach = Coach::where(
            'user_id',
            $userId
        )->firstOrFail();

        User::findOrFail($userId)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

        $coach->update([
            'name' => $data['name'],
            'birth_date' => $data['birth_date'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'license' => $data['license'] ?? null,
        ]);

        return $coach->fresh();
    }

    public function updatePlayerProfile(
        int $userId,
        array $data
    ): Player
    {
        /** @var Player $player */
        $player = Player::where(
            'user_id',
            $userId
        )->firstOrFail();

        User::findOrFail($userId)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

        $player->update([
            'name' => $data['name'],
            'birth_date' => $data['birth_date'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'height' => $data['height'],
            'weight' => $data['weight'],
            'parent_name' => $data['parent_name'],
            'parent_phone' => $data['parent_phone'],
        ]);

        return $player->fresh();
    }
}
