<?php

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Group;
use App\Modules\Admin\Models\Player;
use App\Modules\Attendance\Models\PlayerAttendance;
use App\Modules\Attendance\Models\Training;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // 1. Setup Group A
    $this->groupA = Group::create(['age_group' => 'KU A']);

    // 2. Setup Group B
    $this->groupB = Group::create(['age_group' => 'KU B']);

    // 3. Setup Coach A
    $this->coachUserA = User::create([
        'name' => 'Coach A',
        'username' => 'coacha',
        'email' => 'coacha@example.com',
        'password' => bcrypt('password'),
        'role' => 'coach',
    ]);
    $this->coachA = Coach::create([
        'user_id' => $this->coachUserA->id,
        'name' => 'Coach A',
        'birth_date' => '1985-01-01',
        'group_id' => $this->groupA->id,
        'position' => 'Head Coach',
        'phone_number' => '123456',
        'email' => 'coacha@example.com',
        'is_master' => false,
    ]);

    // 4. Setup Coach B
    $this->coachUserB = User::create([
        'name' => 'Coach B',
        'username' => 'coachb',
        'email' => 'coachb@example.com',
        'password' => bcrypt('password'),
        'role' => 'coach',
    ]);
    $this->coachB = Coach::create([
        'user_id' => $this->coachUserB->id,
        'name' => 'Coach B',
        'birth_date' => '1986-01-01',
        'group_id' => $this->groupB->id,
        'position' => 'Head Coach',
        'phone_number' => '654321',
        'email' => 'coachb@example.com',
        'is_master' => false,
    ]);

    // 5. Setup Player A
    $this->playerUserA = User::create([
        'name' => 'Player A',
        'username' => 'playera',
        'email' => 'playera@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
    ]);
    $this->playerA = Player::create([
        'user_id' => $this->playerUserA->id,
        'name' => 'Player A',
        'birth_date' => '2010-01-01',
        'group_id' => $this->groupA->id,
        'phone_number' => '111222',
        'email' => 'playera@example.com',
        'height' => 170.0,
        'weight' => 60.0,
        'parent_name' => 'Parent A',
        'parent_phone' => '222333',
    ]);

    // 6. Setup Player B
    $this->playerUserB = User::create([
        'name' => 'Player B',
        'username' => 'playerb',
        'email' => 'playerb@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
    ]);
    $this->playerB = Player::create([
        'user_id' => $this->playerUserB->id,
        'name' => 'Player B',
        'birth_date' => '2011-01-01',
        'group_id' => $this->groupB->id,
        'phone_number' => '333444',
        'email' => 'playerb@example.com',
        'height' => 172.0,
        'weight' => 62.0,
        'parent_name' => 'Parent B',
        'parent_phone' => '444555',
    ]);
});

test('coach can manage training sessions for their own group', function () {
    $tokenA = JWTAuth::fromUser($this->coachUserA);

    // Create Training
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->postJson('/api/trainings', [
            'title' => 'Latihan Group A',
            'date' => '2026-07-25',
            'start_time' => '16:00',
            'end_time' => '18:00',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.title', 'Latihan Group A')
        ->assertJsonPath('data.group_id', $this->groupA->id);

    $trainingId = $response->json('data.id');

    // Get trainings list
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->getJson('/api/trainings');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $trainingId);

    // Update Training
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->putJson("/api/trainings/$trainingId", [
            'title' => 'Latihan Group A Update',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.title', 'Latihan Group A Update');

    // Delete Training
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->deleteJson("/api/trainings/$trainingId");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('trainings', ['id' => $trainingId]);
});

test('coach cannot access or modify training sessions of another group', function () {
    $tokenB = JWTAuth::fromUser($this->coachUserB);

    // Coach A creates a training session
    $training = Training::create([
        'title' => 'Latihan Group A',
        'date' => '2026-07-25',
        'start_time' => '16:00:00',
        'end_time' => '18:00:00',
        'group_id' => $this->groupA->id,
    ]);

    // Coach B attempts to get attendance list for Coach A's training
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenB"])
        ->getJson("/api/trainings/{$training->id}/attendance");
    $response->assertStatus(400)
        ->assertJsonPath('success', false);

    // Coach B attempts to update Coach A's training
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenB"])
        ->putJson("/api/trainings/{$training->id}", [
            'title' => 'Hacked title',
        ]);
    $response->assertStatus(400)
        ->assertJsonPath('success', false);

    // Coach B attempts to delete Coach A's training
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenB"])
        ->deleteJson("/api/trainings/{$training->id}");
    $response->assertStatus(400)
        ->assertJsonPath('success', false);
});

test('coach can record and view player attendance', function () {
    $tokenA = JWTAuth::fromUser($this->coachUserA);

    // Create a training session
    $training = Training::create([
        'title' => 'Latihan A',
        'date' => '2026-07-25',
        'start_time' => '16:00:00',
        'end_time' => '18:00:00',
        'group_id' => $this->groupA->id,
    ]);

    // Get attendance list (initially null attendance status)
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->getJson("/api/trainings/{$training->id}/attendance");

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.players.0.name', 'Player A')
        ->assertJsonPath('data.players.0.attendance', null);

    // Record attendance
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->postJson("/api/trainings/{$training->id}/attendance", [
            'attendances' => [
                [
                    'player_id' => $this->playerA->id,
                    'status' => 'present',
                    'description' => 'Hadir latihan regular',
                ],
            ],
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('player_attendances', [
        'training_id' => $training->id,
        'player_id' => $this->playerA->id,
        'status' => 'present',
        'description' => 'Hadir latihan regular',
    ]);

    // Get attendance list again to verify values
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenA"])
        ->getJson("/api/trainings/{$training->id}/attendance");

    $response->assertStatus(200)
        ->assertJsonPath('data.players.0.attendance.status', 'present')
        ->assertJsonPath('data.players.0.attendance.description', 'Hadir latihan regular');
});

test('player can retrieve their own attendance summary', function () {
    $tokenPlayer = JWTAuth::fromUser($this->playerUserA);

    // Create two training sessions
    $t1 = Training::create([
        'title' => 'Latihan A1',
        'date' => '2026-07-24',
        'start_time' => '16:00:00',
        'end_time' => '18:00:00',
        'group_id' => $this->groupA->id,
        'is_finalized' => true,
    ]);
    $t2 = Training::create([
        'title' => 'Latihan A2',
        'date' => '2026-07-25',
        'start_time' => '16:00:00',
        'end_time' => '18:00:00',
        'group_id' => $this->groupA->id,
        'is_finalized' => true,
    ]);

    // Set present for first session, absent for second session
    PlayerAttendance::create([
        'training_id' => $t1->id,
        'player_id' => $this->playerA->id,
        'status' => 'present',
        'description' => 'Hadir',
        'attended_at' => now(),
    ]);

    PlayerAttendance::create([
        'training_id' => $t2->id,
        'player_id' => $this->playerA->id,
        'status' => 'absent',
        'description' => 'Sakit',
    ]);

    // Retrieve Player summary
    $response = $this->withHeaders(['Authorization' => "Bearer $tokenPlayer"])
        ->getJson('/api/player/attendance/summary');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.summary.total_meetings', 2)
        ->assertJsonPath('data.summary.total_present', 1)
        ->assertJsonPath('data.summary.total_absent', 1)
        ->assertJsonPath('data.summary.attendance_percentage', 50)
        ->assertJsonCount(2, 'data.logs');
});
