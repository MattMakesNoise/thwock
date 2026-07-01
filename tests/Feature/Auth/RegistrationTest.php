<?php

namespace Tests\Feature\Auth;

use App\Models\Course;
use App\Models\Round;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_claims_previous_round_player_rows_by_email(): void
    {
        $owner = User::factory()->create();
        $course = Course::create(['name' => 'Mousehold']);
        $round = Round::create([
            'course_id' => $course->id,
            'user_id' => $owner->id,
            'status' => 'final',
        ]);

        $round->players()->create([
            'display_name' => 'Chris',
            'email' => 'test@example.com',
            'position' => 1,
            'scoring_mode' => 'all_par_4',
            'handicap_strokes' => 0,
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('round_players', [
            'round_id' => $round->id,
            'email' => 'test@example.com',
            'user_id' => $user->id,
        ]);
    }
}
