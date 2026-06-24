<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;

class ManagementDataRepository implements IManagementDataRepository
{
    public function getAllCoaches($groupId = null)
    {
        $query = Coach::query();

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return $query->get();
    }

    public function getCoachDetail(int $id)
    {
        return Coach::with(['group', 'user'])->find($id);
    }

    public function findCoachById(int $id)
    {
        return Coach::find($id);
    }

    public function createCoach(array $data)
    {
        return Coach::create($data);
    }

    public function updateCoach(int $id, array $data)
    {
        $coach = Coach::findOrFail($id);
        $coach->update($data);

        return $coach;
    }

    public function deleteCoach(int $id)
    {
        return Coach::findOrFail($id)->delete();
    }

    public function findCoachByUserId(int $userId)
    {
        return Coach::where('user_id', $userId)->first();
    }

    public function getAllPlayers($groupId = null)
    {
        $query = Player::query();

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return $query->get();
    }

    public function getPlayerDetail(int $id)
    {
        return Player::with(['group', 'user'])->find($id);
    }

    public function findPlayerById(int $id)
    {
        return Player::find($id);
    }

    public function createPlayer(array $data)
    {
        return Player::create($data);
    }

    public function updatePlayer(int $id, array $data)
    {
        $player = Player::findOrFail($id);
        $player->update($data);

        return $player;
    }

    public function deletePlayer(int $id)
    {
        return Player::findOrFail($id)->delete();
    }
}
