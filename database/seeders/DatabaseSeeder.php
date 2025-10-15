<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com', 
            'name' => 'Admin', 
            'role' => 'admin', 
            'password' => bcrypt('password')
        ]);
        User::factory()->create([
            'email' => 'org@example.com', 
            'name' => 'Organizer', 
            'role' => 'organizer', 
            'password' => bcrypt('password')
        ]);
        User::factory()->count(10)->create();
        
        $organizer = User::where('role', 'organizer')->first();

        for ($i = 1; $i <= 5; $i++) {
            $event = Event::create([
                'title' => 'Event ' . $i, 
                'description' => 'Desc', 
                'date' => now()->addDays($i), 
                'location' => 'Loc ' . $i, 
                'created_by' => $organizer->id
            ]);
            Ticket::create([
                'type' => 'VIP', 
                'price' => 100 + $i * 10, 
                'quantity' => 50, 
                'event_id' => $event->id
            ]);
            Ticket::create([
                'type' => 'Standard', 
                'price' => 50 + $i * 5, 
                'quantity' => 100, 
                'event_id' => $event->id
            ]);
        }
    }
}
