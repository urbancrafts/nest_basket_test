<?php
namespace Tests\Feature;

use App\Events\UserCreated;
use App\Models\User;
use App\Notifications\UserCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserNotification()
    {
        Notification::fake();

        $user = User::make([
                            'name' => 'John Leo',
                            'email' => 'john@mail.com'
                            ]);

        // $user = User::first();

        // Dispatch the UserCreated event
        event(new UserCreated($user));

        // Assertions for UserCreatedNotification
        Notification::assertSentTo(
            $user,
            UserCreatedNotification::class,
            function ($notification, $channels, $notifiable) use ($user) {
                return $notification->user->id === $user->id;
            }
        );
    }
}
