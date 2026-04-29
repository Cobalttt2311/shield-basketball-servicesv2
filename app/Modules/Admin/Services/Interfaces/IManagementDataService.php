<?php

namespace App\Modules\Admin\Services\Interfaces;

interface IManagementDataService
{
    public function getAllCoaches($groupId = null);
    public function getCoachDetail(int $id);
    public function createCoach(array $data);
    public function updateCoach(int $id, array $data);
    public function deleteCoach(int $id);
    public function getAllPlayers($groupId = null);
    public function getPlayerDetail(int $id);
    public function createPlayer(array $data);
    public function updatePlayer(int $id, array $data);
    public function deletePlayer(int $id);
}