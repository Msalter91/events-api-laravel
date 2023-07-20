<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a reminder to all attendees that the event starts soom';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $events = Event::with('attendees.user')
      ->whereBetween('start_time', [now(), now()->addDay()])
      ->get();

      $events->each(fn($event) => $event->attendees->each(
        fn($attendee) => $attendee->user->notify(
          new EventReminderNotification($event)
        )
      ));

      // $eventCount = $events->count();
      // $eventLabel = Str::plural('event', $eventCount);
      // $this->info("There are {$eventCount} {$eventLabel} in the next 24 hours");
    }
}
