<?php

namespace App\Modules\Admin\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;
use App\Modules\User\Services\Interfaces\IUserService;

class ManagementDataService implements IManagementDataService
{
    protected IManagementDataRepository $repo;
    protected IUserService $userService;

    public function __construct(
        IManagementDataRepository $repo,
        IUserService $userService
    ) {
        $this->repo = $repo;
        $this->userService = $userService;
    }

    public function getAllCoaches($groupId = null)
    {
        $coaches = $this->repo->getAllCoaches($groupId);

        return $coaches->map(function ($coach) {

            if (!$coach->user) {
                return $coach;
            }

            $coach->user_data = [
                'id' => $coach->user->id,
                'username' => $coach->user->username,
                'default_password' => '*ShieldCoach' . $coach->user->id . '#'
            ];

            return $coach;
        });
    }

    public function createCoach(array $data)
    {
        return DB::transaction(function () use ($data) {

            $userData = $this->userService->createUser([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'birth_date' => $data['birth_date'],
                'role'       => 'coach'
            ]);

            $user = $userData['user'];

            $coach = $this->repo->createCoach([
                'name'         => $data['name'],
                'birth_date'   => $data['birth_date'],
                'group_id'     => $data['group_id'],
                'position'     => $data['position'],
                'license'      => $data['license'] ?? null,
                'phone_number' => $data['phone_number'],
                'email'        => $data['email'],
                'user_id'      => $user->id
            ]);

            return [
                'coach' => $coach,
                'user' => [
                    'id'       => $user->id,
                    'username' => $userData['username'],
                    'password' => $userData['password']
                ]
            ];
        });
    }

    public function updateCoach(int $id, array $data)
    {
        return $this->repo->updateCoach($id, $data);
    }

    public function deleteCoach(int $id)
    {
        return DB::transaction(function () use ($id) {
            $coach = $this->repo->findCoachById($id);
            if (!$coach) {
                return false;
            }

            $userId = $coach->user_id;

            $this->repo->deleteCoach($id);
            $this->userService->deleteUser($userId);

            return true;
        });
    }
    public function getAllPlayers($groupId = null)
    {
        $players = $this->repo->getAllPlayers($groupId);

        return $players->map(function ($player) {

            if (!$player->user) {
                return $player;
            }

            $player->user_data = [
                'id' => $player->user->id,
                'username' => $player->user->username,
                'default_password' => '*ShieldPlayer' . $player->user->id . '#'
            ];

            return $player;
        });
    }

    public function createPlayer(array $data)
    {
        return DB::transaction(function () use ($data) {

            $userData = $this->userService->createUser([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'birth_date' => $data['birth_date'],
                'role'       => 'player'
            ]);

            $user = $userData['user'];

            $player = $this->repo->createPlayer([
                'name'                 => $data['name'],
                'birth_date'           => $data['birth_date'],
                'group_id'             => $data['group_id'],
                'phone_number'         => $data['phone_number'],
                'email'                => $data['email'],
                'height'               => $data['height'],
                'weight'               => $data['weight'],
                'parent_name'          => $data['parent_name'],
                'parent_phone'         => $data['parent_phone'],
                'user_id'              => $user->id
            ]);

            return [
                'player' => $player,
                'user' => [
                    'id'       => $user->id,
                    'username' => $userData['username'],
                    'password' => $userData['password']
                ]
            ];
        });
    }

    public function updatePlayer(int $id, array $data)
    {
        return $this->repo->updatePlayer($id, $data);
    }

    public function deletePlayer(int $id)
    {
        return DB::transaction(function () use ($id) {
            $player = $this->repo->findPlayerById($id);
            if (!$player) {
                return false;
            }

            $userId = $player->user_id;

            $this->repo->deletePlayer($id);
            $this->userService->deleteUser($userId);

            return true;
        });
    }
}