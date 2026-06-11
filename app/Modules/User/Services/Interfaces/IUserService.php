<?php

namespace App\Modules\User\Services\Interfaces;

use App\Modules\User\Models\User;

interface IUserService
{
    public function login(
        string $login,
        string $password
    ): ?array;

    public function logout(
        User $user
    ): bool;

    public function createUser(
        array $data
    ): array;

    public function deleteUser(
        int $id
    ): bool;

    public function sendResetLinkEmail(
        string $email
    ): string;

    public function resetPassword(
        array $credentials
    ): string;

    public function getProfile(
        User $user
    );

    public function updateProfile(
        User $user,
        array $data
    );

    public function updatePassword(
        User $user,
        string $oldPassword,
        string $newPassword
    ): bool;
}