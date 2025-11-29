<?php

namespace Database\Seeders;

use App\Models\Webpage;
use App\Models\User;
use Illuminate\Database\Seeder;

class WebpageSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user or create one
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $admin = User::first();
        }

        // Create default home landing page if it doesn't exist
        $homePage = Webpage::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'type' => 'landing',
                'meta_description' => 'Welcome to our landing page',
                'meta_keywords' => 'landing, page, home',
                'is_published' => true,
                'order' => 0,
                'created_by' => $admin ? $admin->id : null,
            ]
        );

        $this->command->info('Default home page created at: /pages/home');
    }
}

