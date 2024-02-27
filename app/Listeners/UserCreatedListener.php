<?php

namespace App\Listeners;


use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserCreatedNotification;
use Illuminate\Support\Facades\Notification;


class UserCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event)
    {
        
         // Save user creation event data in the log file
        //  Log::info('User created', ['user' => $event->user->toArray()]);

         Log::info('User Created: ' . json_encode($event->user));

         //send notification to the "notification" service
         $event->user->notify(new UserCreatedNotification($event->user));
    }
}
