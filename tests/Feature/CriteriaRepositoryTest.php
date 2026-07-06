<?php

use App\Modules\Admin\Models\Group;
use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\CriteriaSet;
use App\Modules\Coaches\Repositories\CriteriaRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns criteria for the given group id', function () {
    $group = Group::create(['age_group' => 'U16']);

    $set = CriteriaSet::create([
        'name' => 'Default Set',
        'group_id' => $group->id,
    ]);

    Criteria::create([
        'criteria_set_id' => $set->id,
        'name' => 'Physical',
    ]);

    Criteria::create([
        'criteria_set_id' => $set->id,
        'name' => 'Technique',
    ]);

    $repository = new CriteriaRepository;

    $result = $repository->getCriteriaByGroupId($group->id);

    expect($result)->toHaveCount(2)
        ->and($result->pluck('name')->all())->toEqual(['Physical', 'Technique']);
});
