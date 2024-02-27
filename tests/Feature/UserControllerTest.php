<?php
namespace Tests\Feature;


use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    

    public function testCreateUser()
    {
        Event::fake();
        Log::spy();



        $userData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'test@example.com',
        ];

        // Send POST request to /users endpoint
        $response = $this->post('api/users', $userData);

        $response->assertStatus(200)
            ->assertJson(['status' => true,
                         'message' => 'User created successfully.',
                        //  'data' => $response->data->user,
                         'status_code' => 200
                         
                            ]);

        // Assertions for UserCreated event
        Event::assertDispatched(UserCreated::class, function ($event) use ($userData) {
            return $event->user->email === $userData['email'];
        });

        // Assertions for UserCreatedListener
        Log::assertLogged('info', function ($log) use ($userData) {
            return $log['context']['user']['email'] === $userData['email'];
        });
    }
}
