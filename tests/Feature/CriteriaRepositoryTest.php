<?php

use App\Modules\Admin\Models\Group;
use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Repositories\CriteriaRepository;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns criteria for the given group id', function () {
    $group = Group::create(['age_group' => 'U16']);

    Criteria::create([
        'group_id' => $group->id,
        'name' => 'Physical',
    ]);

    Criteria::create([
        'group_id' => $group->id,
        'name' => 'Technique',
    ]);

    $repository = new CriteriaRepository();

    $result = $repository->getCriteriaByGroupId($group->id);

    expect($result)->toHaveCount(2)
        ->and($result->pluck('name')->all())->toEqual(['Physical', 'Technique']);
});
