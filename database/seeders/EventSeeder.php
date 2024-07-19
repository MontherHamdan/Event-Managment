<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // we would to generate some events but each event have to be tied with user so we will do that
        $users = User::all();

        for ($i = 0; $i < 200; $i++) {
            $user = $users->random();

            Event::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
