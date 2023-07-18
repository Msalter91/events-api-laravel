<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Gate::define('update-event', function($user, Event $event){
          return $user->id === $event->user_id;
        });

        Gate::define('remove-attendee', function($user, Event $event, Attendee $attendee) {
          return $user->id === $event->user_id || $user->id === $attendee->user_id;
        });
    }
}
