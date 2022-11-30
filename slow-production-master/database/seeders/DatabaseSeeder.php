<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'name'     => 'Test user',
            'email'    => 'test@sdu.dk',
            'password' => bcrypt('webtech2021'),
        ]);



        User::factory(10)
            ->has(Post::factory()->count(5))
            ->create();


        Post::all()->each(function (Post $post)
        {
            User::all()
                ->random(rand(0, User::all()->count()))
                ->reject(fn(User $user) => $user->id == $post->user_id)
                ->each(function (User $user) use ($post)
                {
                    $post->likes()->attach($user->id);
                });
        });
    }
}
