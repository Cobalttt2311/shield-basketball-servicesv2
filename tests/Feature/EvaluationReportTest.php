<?php

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Group;
use App\Modules\Admin\Models\Player;
use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // 1. Setup Group
    $this->group = Group::create([
        'name' => '14 Putra',
    ]);

    // 2. Setup Coach
    $this->coachUser = User::create([
        'name' => 'Coach Johan',
        'username' => 'coachjohan',
        'email' => 'coach@example.com',
        'password' => bcrypt('password'),
        'role' => 'coach',
    ]);

    $this->coach = Coach::create([
        'user_id' => $this->coachUser->id,
        'name' => 'Coach Johan',
        'birth_date' => '1990-01-01',
        'group_id' => $this->group->id,
        'position' => 'Head Coach',
        'phone_number' => '1234567',
        'email' => 'coach@example.com',
    ]);

    // 3. Setup Assistant Coach in the same group to test UI integration
    $this->assistantUser = User::create([
        'name' => 'Assis Nathaniel',
        'username' => 'assisnathaniel',
        'email' => 'assistant@example.com',
        'password' => bcrypt('password'),
        'role' => 'coach',
    ]);

    $this->assistantCoach = Coach::create([
        'user_id' => $this->assistantUser->id,
        'name' => 'Assis Nathaniel',
        'birth_date' => '1992-01-01',
        'group_id' => $this->group->id,
        'position' => 'Assistant Coach',
        'phone_number' => '12345679',
        'email' => 'assistant@example.com',
    ]);

    // 4. Setup Player
    $this->playerUser = User::create([
        'name' => 'Player Romli',
        'username' => 'playerromli',
        'email' => 'player@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
    ]);

    $this->player = Player::create([
        'user_id' => $this->playerUser->id,
        'name' => 'Player Romli',
        'birth_date' => '2012-01-01',
        'group_id' => $this->group->id,
        'phone_number' => '1234568',
        'email' => 'player@example.com',
        'height' => 170.0,
        'weight' => 60.0,
        'parent_name' => 'Parent Name',
        'parent_phone' => '1234569',
    ]);

    // 5. Setup Positions
    $this->recPosition = Position::create([
        'group_id' => $this->group->id,
        'name' => 'Guard',
    ]);

    $this->finalPosition = Position::create([
        'group_id' => $this->group->id,
        'name' => 'Forward',
    ]);

    // 6. Setup Evaluation
    $this->evaluation = Evaluation::create([
        'title' => 'Evaluasi Utama',
        'date' => now()->toDateString(),
        'coach_id' => $this->coach->id,
    ]);

    // 7. Setup Criteria & SubCriteria
    $this->criteria = Criteria::create([
        'group_id' => $this->group->id,
        'name' => 'Skill',
    ]);

    $this->subCriteria = SubCriteria::create([
        'criteria_id' => $this->criteria->id,
        'name' => 'Dribbling',
    ]);

    // 8. Setup Evaluation Scores
    $this->score = EvaluationScore::create([
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'sub_criteria_id' => $this->subCriteria->id,
        'score' => 80,
    ]);
});

test('coach can finalize report successfully', function () {
    $token = JWTAuth::fromUser($this->coachUser);

    $payload = [
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'recommended_position_id' => $this->recPosition->id,
        'final_position_id' => $this->finalPosition->id,
        'notes' => 'Pemain menunjukkan peningkatan pesat di dribbling.',
    ];

    $response = $this->withHeaders(['Authorization' => "Bearer $token"])
        ->postJson('/api/evaluation-reports/finalize', $payload);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Evaluation report finalized successfully',
        ]);

    $this->assertDatabaseHas('evaluation_reports', [
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'recommended_position_id' => $this->recPosition->id,
        'final_position_id' => $this->finalPosition->id,
        'notes' => 'Pemain menunjukkan peningkatan pesat di dribbling.',
    ]);
});

test('non-coach cannot finalize report', function () {
    $token = JWTAuth::fromUser($this->playerUser);

    $payload = [
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'recommended_position_id' => $this->recPosition->id,
        'final_position_id' => $this->finalPosition->id,
        'notes' => 'Latihan dribbling terus.',
    ];

    $response = $this->withHeaders(['Authorization' => "Bearer $token"])
        ->postJson('/api/evaluation-reports/finalize', $payload);

    $response->assertStatus(403);
});

test('coach can retrieve finalized report with scores', function () {
    // Finalize first
    $token = JWTAuth::fromUser($this->coachUser);

    $payload = [
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'recommended_position_id' => $this->recPosition->id,
        'final_position_id' => $this->finalPosition->id,
        'notes' => 'Kerja bagus.',
    ];

    $this->withHeaders(['Authorization' => "Bearer $token"])
        ->postJson('/api/evaluation-reports/finalize', $payload);

    // Get report
    $response = $this->withHeaders(['Authorization' => "Bearer $token"])
        ->getJson("/api/evaluation-reports/evaluation/{$this->evaluation->id}/player/{$this->player->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Evaluation report retrieved successfully',
            'data' => [
                'evaluation_id' => $this->evaluation->id,
                'player_name' => 'Player Romli',
                'group_name' => '14 Putra',
                'head_coach' => 'Coach Johan',
                'assistant_coach' => 'Assis Nathaniel',
                'recommended_position_name' => 'Guard',
                'final_position_name' => 'Forward',
                'notes' => 'Kerja bagus.',
                'scores' => [
                    [
                        'sub_criteria_name' => 'Dribbling',
                        'criteria_name' => 'Skill',
                        'score' => 80,
                    ],
                ],
            ],
        ]);
});

test('player can retrieve their own report but not others', function () {
    // Finalize first
    $coachToken = JWTAuth::fromUser($this->coachUser);

    $payload = [
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'recommended_position_id' => $this->recPosition->id,
        'final_position_id' => $this->finalPosition->id,
        'notes' => 'Sangat bagus.',
    ];

    $this->withHeaders(['Authorization' => "Bearer $coachToken"])
        ->postJson('/api/evaluation-reports/finalize', $payload);

    // Player retrieves own report
    $playerToken = JWTAuth::fromUser($this->playerUser);
    $response = $this->withHeaders(['Authorization' => "Bearer $playerToken"])
        ->getJson("/api/evaluation-reports/my-report/{$this->evaluation->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Evaluation report retrieved successfully',
            'data' => [
                'evaluation_id' => $this->evaluation->id,
                'player_name' => 'Player Romli',
                'notes' => 'Sangat bagus.',
            ],
        ]);

    // Create another player and try to retrieve Romli's report (since route has no playerId parameter, player retrieves their own, but since they have no report, they should get 404 Not Found)
    $otherPlayerUser = User::create([
        'name' => 'Other Player',
        'username' => 'otherplayer',
        'email' => 'other@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
    ]);
    $otherPlayer = Player::create([
        'user_id' => $otherPlayerUser->id,
        'name' => 'Other Player',
        'birth_date' => '2012-01-01',
        'group_id' => $this->group->id,
        'phone_number' => '12345680',
        'email' => 'other@example.com',
        'height' => 170.0,
        'weight' => 60.0,
        'parent_name' => 'Parent Name',
        'parent_phone' => '1234569',
    ]);

    $otherToken = JWTAuth::fromUser($otherPlayerUser);
    $otherResponse = $this->withHeaders(['Authorization' => "Bearer $otherToken"])
        ->getJson("/api/evaluation-reports/my-report/{$this->evaluation->id}");

    $otherResponse->assertStatus(404);
});

test('retrieving non-existent report returns 404', function () {
    $token = JWTAuth::fromUser($this->coachUser);
    $response = $this->withHeaders(['Authorization' => "Bearer $token"])
        ->getJson('/api/evaluation-reports/evaluation/999/player/999');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Evaluation report not found',
        ]);
});

test('player can retrieve list of finalized reports', function () {
    // Finalize one report
    $coachToken = JWTAuth::fromUser($this->coachUser);

    $payload = [
        'evaluation_id' => $this->evaluation->id,
        'player_id' => $this->player->id,
        'recommended_position_id' => $this->recPosition->id,
        'final_position_id' => $this->finalPosition->id,
        'notes' => 'Sangat bagus.',
    ];

    $this->withHeaders(['Authorization' => "Bearer $coachToken"])
        ->postJson('/api/evaluation-reports/finalize', $payload);

    // Fetch reports list
    $playerToken = JWTAuth::fromUser($this->playerUser);
    $response = $this->withHeaders(['Authorization' => "Bearer $playerToken"])
        ->getJson('/api/evaluation-reports/my-reports');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Evaluation report retrieved successfully',
        ])
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'evaluation_id' => $this->evaluation->id,
            'evaluation_title' => 'Evaluasi Utama',
            'final_position_name' => 'Forward',
        ]);
});
