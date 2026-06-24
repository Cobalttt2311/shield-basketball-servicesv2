<?php

namespace App\Modules\Admin\Repositories\Interfaces;

interface IManagementDataRepository
{
    // COACH
    public function getAllCoaches($groupId = null);

    public function getCoachDetail(int $id);

    public function findCoachById(int $id);

    public function createCoach(array $data);

    public function updateCoach(int $id, array $data);

    public function deleteCoach(int $id);

    public function findCoachByUserId(int $userId);

    // PLAYER
    public function getAllPlayers($groupId = null);

    public function getPlayerDetail(int $id);

    public function findPlayerById(int $id);

    public function createPlayer(array $data);

    public function updatePlayer(int $id, array $data);

    public function deletePlayer(int $id);
    public function findPlayerByUserId(int $userId);   
}
     
