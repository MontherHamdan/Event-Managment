<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    // here you can define some matching between model and policies
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */

    public function boot(): void
    {

        // // define gate for update an event
        // // Gates: are simply closures that determine if a user is authorized to perform a given action
        // Gate::define('update-event', function ($user, Event $event) {
        //     return $user->id === $event->user_id;
        // });

        // // gate for delete attendee
        // Gate::define('delete-attendee', function ($user, Event $event, Attendee $attendee) {
        //     return $user->id === $event->user_id ||
        //         $user->id === $attendee->user_id;
        // });

        //  we dont have to use that in above cause we used policy
        // remember you can use gates if you want for some generic app wide autherization checks
        // but for resoucres or model its better to use policies
    }
}
