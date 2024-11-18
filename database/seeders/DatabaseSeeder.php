<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => 't.f.schuil@gmail.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('processes')->insert([
            'class' => 'ChatCompletions',
            'data' => '{"endpoint": "chat/completions", "user_role_content": "give me a random story", "system_role_content": "you are less than average ai llm and make some funny mistakes"}', 
        ]);

        DB::table('processes')->insert([
            'class' => 'PostFacebook',
            'data' => '{"page_id": "488087137720800", "access_token": "EAAIHTtbEnHkBO1Id3t1GFJPVsOepKuQ5delPniuCAPXCH3uAuNHo6tMjE5DmKSK3jYgkhE8fHDVZC1i4KlVbN6gsAEnjJBuZAdCYxAmbYXEZAbtHJKz5kaufMa6fbCqCucTBgEdsXwmzKQgJMHEOuhmrw7CRhdYJiZAY5mzzgXZBF5bS8YwsFFGXGGbXQLAg7uy8NujMt"}', 
        ]);
    }
}
