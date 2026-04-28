<?php

namespace App\Modules\Admin\Repositories\Interfaces;

interface IManagementDataRepository
{
    // COACH
    public function getAllCoaches();
    public function findCoachById(int $id);
    public function createCoach(array $data);
    public function updateCoach(int $id, array $data);
    public function deleteCoach(int $id);

    // PLAYER
    public function getAllPlayers();
    public function findPlayerById(int $id);
    public function createPlayer(array $data);
    public function updatePlayer(int $id, array $data);
    public function deletePlayer(int $id);
}