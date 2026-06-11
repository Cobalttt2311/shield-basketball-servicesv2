<?php

namespace App\Modules\User\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Modules\User\Services\Interfaces\IUserService;
use App\Modules\User\Repositories\Interfaces\IUserRepository;

class UserService implements IUserService
{
    protected IUserRepository $userRepository;

    public function __construct(
        IUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function login(
        string $login,
        string $password
    ): ?array
    {
        $user = $this->userRepository
            ->findByLogin($login);

        if (
            !$user ||
            !Hash::check($password, $user->password)
        ) {
            return null;
        }

        return [
            'user' => $user,
            'token' => JWTAuth::fromUser($user)
        ];
    }

    public function logout(User $user): bool
    {
        JWTAuth::invalidate(
            JWTAuth::getToken()
        );

        return true;
    }

    public function createUser(array $data): array
    {
        $user = $this->userRepository->create([
            'name'     => $data['name'],
            'username' => 'temp_' . Str::random(8),
            'email'    => $data['email'],
            'password' => Hash::make(Str::random(16)),
            'role'     => $data['role'],
        ]);

        $dob = isset($data['birth_date'])
            ? Carbon::parse($data['birth_date'])->format('Ymd')
            : '00000000';

        $username = $user->id . $dob;

        $defaultPassword =
            '*Shield' .
            ucfirst($data['role']) .
            $user->id .
            '#';

        $user = $this->userRepository->update(
            $user->id,
            [
                'username' => $username,
                'password' => Hash::make($defaultPassword)
            ]
        );

        return [
            'user' => $user,
            'username' => $username,
            'password' => $defaultPassword
        ];
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function sendResetLinkEmail(
        string $email
    ): string
    {
        $user = $this->userRepository
            ->findByEmail($email);

        if (!$user) {
            return Password::INVALID_USER;
        }

        return Password::sendResetLink([
            'email' => $user->email
        ]);
    }

    public function resetPassword(
        array $credentials
    ): string
    {
        $user = $this->userRepository
            ->findByEmail($credentials['email']);

        if (!$user) {
            return Password::INVALID_USER;
        }

        return Password::reset(
            $credentials,
            function ($user, $password) {

                $this->userRepository->update(
                    $user->id,
                    [
                        'password' => Hash::make($password)
                    ]
                );
            }
        );
    }

    public function getProfile(User $user)
    {
        return $this->userRepository
            ->getProfileByUser($user);
    }

    public function updateProfile(
        User $user,
        array $data
    )
    {
        return DB::transaction(function () use (
            $user,
            $data
        ) {

            if (
                isset($data['email']) &&
                $data['email'] !== $user->email
            ) {

                $existing = $this->userRepository
                    ->findByEmail($data['email']);

                if (
                    $existing &&
                    $existing->id !== $user->id
                ) {
                    throw new \Exception(
                        'Email already exists'
                    );
                }
            }

            if ($user->role === 'coach') {

                return $this->userRepository
                    ->updateCoachProfile(
                        $user->id,
                        $data
                    );
            }

            if ($user->role === 'player') {

                return $this->userRepository
                    ->updatePlayerProfile(
                        $user->id,
                        $data
                    );
            }

            return $this->userRepository->update(
                $user->id,
                $data
            );
        });
    }

    public function updatePassword(
        User $user,
        string $oldPassword,
        string $newPassword
    ): bool
    {
        if (
            !Hash::check(
                $oldPassword,
                $user->password
            )
        ) {
            return false;
        }

        $this->userRepository->update(
            $user->id,
            [
                'password' => Hash::make(
                    $newPassword
                )
            ]
        );

        return true;
    }
}