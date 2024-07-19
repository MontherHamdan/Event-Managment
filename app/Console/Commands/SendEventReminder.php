<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

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
    protected $description = 'Send notification to all event attendees that event starts soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // this command will find the event will happend in next 24 hour
        $events = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();

        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}");

        // // each() to run the function for every single event
        // $events->each(
        //     fn ($event) => $event->attendees->each(
        //         fn ($attendee) => $this->info("notifiying the user {$attendee->user->id}")
        //     )
        // );

        // insteat of the line above will create it another way after we created EventReminderNotification class
        $events->each(
            fn ($event) => $event->attendees->each(
                fn ($attendee) => $attendee->user->notify(
                    new EventReminderNotification($event)
                )
            )
        );

        $this->info('reminder notificateion sent successfully');
    }
}
