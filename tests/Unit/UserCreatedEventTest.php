<?php
namespace Tests\Unit;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCreatedEvent()
    {
        $user = User::factory()->create();

        // Dispatch the event
        event(new UserCreated($user));

        // Assertions, for example:
        $this->assertDatabaseHas('user_created_logs', [
            'user_id' => $user->id,
        ]);
    }
}
