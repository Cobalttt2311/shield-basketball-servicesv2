<?php

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Group;
use App\Modules\Admin\Models\Player;
use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Models\PairwiseCriteria;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\PairwiseSubCriteria;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSubCriteriaService;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // 1. Setup Group
    $this->group = Group::create([
        'age_group' => 'KU 13-18',
    ]);

    // 2. Setup Master Coach
    $this->masterUser = User::create([
        'name' => 'Coach Master',
        'username' => 'coachmaster',
        'email' => 'master@example.com',
        'password' => bcrypt('password'),
        'role' => 'coach',
    ]);

    $this->masterCoach = Coach::create([
        'user_id' => $this->masterUser->id,
        'name' => 'Coach Master',
        'birth_date' => '1985-01-01',
        'group_id' => $this->group->id,
        'position' => 'Head Coach',
        'phone_number' => '111111',
        'email' => 'master@example.com',
        'is_master' => true,
    ]);

    // 3. Setup Assistant Coach (Non-Master)
    $this->assistantUser = User::create([
        'name' => 'Coach Assistant',
        'username' => 'coachassistant',
        'email' => 'assistant@example.com',
        'password' => bcrypt('password'),
        'role' => 'coach',
    ]);

    $this->assistantCoach = Coach::create([
        'user_id' => $this->assistantUser->id,
        'name' => 'Coach Assistant',
        'birth_date' => '1990-01-01',
        'group_id' => $this->group->id,
        'position' => 'Assistant Coach',
        'phone_number' => '222222',
        'email' => 'assistant@example.com',
        'is_master' => false,
    ]);

    // 4. Setup Player
    $this->playerUser = User::create([
        'name' => 'Player Fandi',
        'username' => 'playerfandi',
        'email' => 'fandi@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
    ]);

    $this->player = Player::create([
        'user_id' => $this->playerUser->id,
        'name' => 'Player Fandi',
        'birth_date' => '2012-01-01',
        'group_id' => $this->group->id,
        'phone_number' => '333333',
        'email' => 'fandi@example.com',
        'height' => 175.0,
        'weight' => 65.0,
        'parent_name' => 'Parent Fandi',
        'parent_phone' => '444444',
    ]);

    // 5. Setup Position
    $this->position = Position::create([
        'group_id' => $this->group->id,
        'name' => 'Shooting Guard',
    ]);

    // 6. Setup Criteria & SubCriteria
    $this->criteria = Criteria::create([
        'group_id' => $this->group->id,
        'name' => 'Skill',
    ]);

    $this->subCriteria = SubCriteria::create([
        'criteria_id' => $this->criteria->id,
        'name' => 'Passing',
    ]);

    // 7. Setup Evaluation & Score
    $this->evaluation = Evaluation::create([
        'title' => 'Evaluasi Bulanan',
        'date' => now()->toDateString(),
        'coach_id' => $this->masterCoach->id,
    ]);

    $this->evaluationScore = EvaluationScore::create([
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'sub_criteria_id' => $this->subCriteria->id,
        'score' => 85,
    ]);
});

test('master coach can create pairwise set successfully', function () {
    $masterToken = JWTAuth::fromUser($this->masterUser->fresh('coach'));

    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson('/api/pairwise-sets', [
            'name' => 'Set Master',
            'group_id' => $this->group->id,
        ]);
    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Pairwise set created successfully',
            'data' => [
                'name' => 'Set Master',
                'group_id' => $this->group->id,
            ],
        ]);

    $this->assertDatabaseHas('pairwise_sets', [
        'name' => 'Set Master',
        'group_id' => $this->group->id,
    ]);
});

test('assistant coach cannot create pairwise set', function () {
    $assistantToken = JWTAuth::fromUser($this->assistantUser->fresh('coach'));

    $response = $this->withHeaders(['Authorization' => "Bearer $assistantToken"])
        ->postJson('/api/pairwise-sets', [
            'name' => 'Set Asisten',
            'group_id' => $this->group->id,
        ]);
    $response->assertStatus(403);
});

test('compatibility filtering filters sets based on active criteria', function () {
    $masterToken = JWTAuth::fromUser($this->masterUser->fresh('coach'));

    // Create a new pairwise set (blank) - always compatible
    $set1 = PairwiseSet::create([
        'name' => 'Set Kompatibel Baru',
        'group_id' => $this->group->id,
    ]);

    // Create another set with filled comparison matching criteria
    $set2 = PairwiseSet::create([
        'name' => 'Set Kompatibel Isi',
        'group_id' => $this->group->id,
    ]);

    PairwiseCriteria::create([
        'position_id' => $this->position->id,
        'criteria_first_id' => $this->criteria->id,
        'criteria_second_id' => $this->criteria->id,
        'value' => 1.0,
        'pairwise_set_id' => $set2->id,
    ]);

    PairwiseSubCriteria::create([
        'position_id' => $this->position->id,
        'criteria_id' => $this->criteria->id,
        'sub_criteria_first_id' => $this->subCriteria->id,
        'sub_criteria_second_id' => $this->subCriteria->id,
        'value' => 1.0,
        'pairwise_set_id' => $set2->id,
    ]);

    // Fetch compatible sets
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->getJson("/api/pairwise-sets?evaluation_id={$this->evaluation->id}");

    $response->assertStatus(200);
    $data = $response->json('data');

    // Both should be compatible
    $ids = array_column($data, 'id');
    expect($ids)->toContain($set1->id)->toContain($set2->id);

    // Now, trigger mismatch compatibility by adding a new criteria to the group
    Criteria::create([
        'group_id' => $this->group->id,
        'name' => 'Kriteria Baru',
    ]);

    // Fetch compatible sets again
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->getJson("/api/pairwise-sets?evaluation_id={$this->evaluation->id}");

    $data = $response->json('data');
    $ids = array_column($data, 'id');

    // set1 (blank new) should still be compatible, but set2 should be filtered out
    expect($ids)->toContain($set1->id)->not->toContain($set2->id);
});

test('process recommendation and finalization list player works successfully', function () {
    $masterToken = JWTAuth::fromUser($this->masterUser);

    // Create pairwise set
    $set = PairwiseSet::create([
        'name' => 'Set Sukses',
        'group_id' => $this->group->id,
    ]);

    // Seed comparison & weights directly
    PairwiseCriteria::create([
        'position_id' => $this->position->id,
        'criteria_first_id' => $this->criteria->id,
        'criteria_second_id' => $this->criteria->id,
        'value' => 1.0,
        'pairwise_set_id' => $set->id,
    ]);

    PairwiseSubCriteria::create([
        'position_id' => $this->position->id,
        'criteria_id' => $this->criteria->id,
        'sub_criteria_first_id' => $this->subCriteria->id,
        'sub_criteria_second_id' => $this->subCriteria->id,
        'value' => 1.0,
        'pairwise_set_id' => $set->id,
    ]);

    // Calculate weights via service calls
    $pairwiseCriteriaService = app(IPairwiseCriteriaService::class);
    $pairwiseCriteriaService->saveWeights($this->group->id, $this->position->id, $set->id);

    $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
    $pairwiseSubCriteriaService->saveWeights($this->position->id, $this->criteria->id, $set->id);

    // Process Recommendation
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson("/api/evaluations/{$this->evaluation->id}/process-recommendation", [
            'pairwise_set_id' => $set->id,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Recommendation processed and calculated successfully',
        ]);

    // The evaluation in DB should have pairwise_set_id saved
    $this->assertDatabaseHas('evaluations', [
        'id' => $this->evaluation->id,
        'pairwise_set_id' => $set->id,
    ]);

    // Fetch players for finalization
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->getJson("/api/evaluation-reports/evaluation/{$this->evaluation->id}/players");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Evaluation players retrieved successfully',
        ])
        ->assertJsonFragment([
            'player_id' => $this->player->id,
            'player_name' => 'Player Fandi',
            'status_finalisasi' => false,
        ]);

    $recs = $response->json('data.0.recommendations');
    expect($recs)->not->toBeEmpty();
    expect($recs[0]['position_id'])->toEqual($this->position->id);
});

test('complete set-based pairwise flow works successfully', function () {
    $masterToken = JWTAuth::fromUser($this->masterUser->fresh('coach'));

    // Create a second criteria and subcriteria so we have at least 2 items to compare
    $criteria2 = Criteria::create([
        'group_id' => $this->group->id,
        'name' => 'Fisik',
    ]);
    $subCriteria2 = SubCriteria::create([
        'criteria_id' => $this->criteria->id,
        'name' => 'Dribbling',
    ]);

    // 1. Create set with only name
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson('/api/pairwise-sets', [
            'name' => 'Set Dinamis Baru',
        ]);
    $response->assertStatus(201);
    $setId = $response->json('data.id');
    expect($setId)->not->toBeNull();

    $this->assertDatabaseHas('pairwise_sets', [
        'id' => $setId,
        'name' => 'Set Dinamis Baru',
        'group_id' => null,
    ]);

    // 2. Update set to associate with group
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->patchJson("/api/pairwise-sets/{$setId}", [
            'group_id' => $this->group->id,
        ]);
    $response->assertStatus(200);

    $this->assertDatabaseHas('pairwise_sets', [
        'id' => $setId,
        'group_id' => $this->group->id,
    ]);

    // 3. Generate pairwise criteria
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson('/api/pairwise-criteria/generate', [
            'pairwise_set_id' => $setId,
        ]);
    $response->assertStatus(200);

    // 4. Get pairwise criteria
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->getJson("/api/pairwise-criteria?pairwise_set_id={$setId}");
    $response->assertStatus(200);
    $criteriaData = $response->json('data');
    expect($criteriaData)->not->toBeEmpty();

    // Collect comparison IDs for saving
    $comparisons = [];
    foreach ($criteriaData as $item) {
        foreach ($item['comparisons'] as $comp) {
            $comparisons[] = [
                'id' => $comp['id'],
                'value' => 1.0,
            ];
        }
    }

    // 5. Save pairwise criteria values
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->putJson('/api/pairwise-criteria/save', [
            'pairwise_set_id' => $setId,
            'comparisons' => $comparisons,
        ]);
    $response->assertStatus(200);

    // 6. Calculate weights criteria
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson('/api/pairwise-criteria/calculate-weights', [
            'pairwise_set_id' => $setId,
        ]);
    $response->assertStatus(200);

    // 7. Generate pairwise subcriteria
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson('/api/pairwise-subcriteria/generate', [
            'pairwise_set_id' => $setId,
            'criteria_id' => $this->criteria->id,
        ]);
    $response->assertStatus(200);

    // 8. Get pairwise subcriteria
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->getJson("/api/pairwise-subcriteria?pairwise_set_id={$setId}&criteria_id={$this->criteria->id}");
    $response->assertStatus(200);
    $subCriteriaData = $response->json('data');
    expect($subCriteriaData)->not->toBeEmpty();

    // Collect subcriteria comparisons
    $subComparisons = [];
    foreach ($subCriteriaData as $item) {
        foreach ($item['comparisons'] as $comp) {
            $subComparisons[] = [
                'id' => $comp['id'],
                'value' => 1.0,
            ];
        }
    }

    // 9. Save pairwise subcriteria values
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->putJson('/api/pairwise-subcriteria/save', [
            'pairwise_set_id' => $setId,
            'comparisons' => $subComparisons,
        ]);
    $response->assertStatus(200);

    // 10. Calculate weights subcriteria
    $response = $this->withHeaders(['Authorization' => "Bearer $masterToken"])
        ->postJson('/api/pairwise-subcriteria/calculate-weights', [
            'pairwise_set_id' => $setId,
            'criteria_id' => $this->criteria->id,
        ]);
    $response->assertStatus(200);
});
