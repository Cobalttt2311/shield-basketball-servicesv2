<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Repositories\Interfaces\IPositionRepository;

class PositionRepository
    implements IPositionRepository
{
    public function getAll()
    {
        return Position::with(
            'group'
        )->get();
    }

    public function getByGroupId(
        int $groupId
    )
    {
        return Position::where(
            'group_id',
            $groupId
        )->get();
    }

    public function findById(
        int $id
    )
    {
        return Position::findOrFail(
            $id
        );
    }

    public function create(
        array $data
    )
    {
        return Position::create(
            $data
        );
    }

    public function update(
        int $id,
        array $data
    )
    {
        $position =
            Position::findOrFail($id);

        $position->update($data);

        return $position;
    }

    public function delete(
        int $id
    )
    {
        return Position::destroy($id);
    }
}