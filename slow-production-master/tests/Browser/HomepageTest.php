<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use Tests\Browser\Pages\Homepage;

use App\Models\User;
use App\Models\Post;
use App\Models\PostLike;

class HomepageTest extends DuskTestCase
{
    use DatabaseMigrations;
    use withFaker;

    public function testCaseGuestOne()
    {

        User::factory(5)
            ->has(Post::factory()->count(6))
            ->create();


        Post::all()->each(function (Post $post)
        {
            User::all()
                ->random(rand(0, User::all()->count()))
                ->reject(fn(User $user) => $user->id == $post->user_id);
        });

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testGuestShowFeed();
        });
    }

    public function testCaseGuestTwo()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testRegisterUser();
        });

        $createdUser = User::where('email', 'john@doe.com')->first();
        $this->assertNotNull($createdUser, 'Unable to locate the registered user, are you saving the correct details?');
        $this->assertTrue(Hash::check('1234', $createdUser->password),
            'Unable to verify that the user\'s password is equal to the one the user signed up with. ' .
            'Did you remember to hash the password before saving to the database? Passwords should never be stored in clear text. Please check: https://laravel.com/docs/8.x/hashing and https://laravel.com/docs/8.x/helpers#method-bcrypt');

    }

    public function testCaseGuestThree()
    {
        User::factory()->create([
            'email'    => 'john@doe.com',
            'password' => bcrypt('1234'),
        ]);

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testLoginUser();
        });
    }

    public function testCaseUserOne()
    {
        User::factory(1)->create();

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserIsLoggedIn();
        });
    }

    public function testCaseUserTwo()
    {
        User::factory(1)->create();

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserLogout();
        });
    }

    public function testCaseUserThree()
    {
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

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserShowFeed();
        });
    }

    public function testCaseUserFour()
    {
        User::factory(10)
            ->has(Post::factory()->count(5))
            ->create();

        Post::all()->each(function (Post $post)
        {
            User::all()
                ->random(rand(0, User::all()->count()))
                ->reject(fn(User $user) => $user->id == $post->user_id);
        });

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserLikePost();
        });
    }

    public function testCaseUserFive()
    {
        User::factory(10)
            ->has(Post::factory()->count(5))
            ->create();

        Post::all()->each(function (Post $post)
        {
            User::all()
                ->random(rand(0, User::all()->count()))
                ->reject(fn(User $user) => $user->id == $post->user_id);
        });
        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserDislikePost();
        });

    }

    public function testCaseUserSix()
    {
        User::factory(2)->create();

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserDeletePost();
        });
    }

    public function testCaseModeratorOneModeratorUser()
    {
        User::factory()->create([
            'name'     => 'Test user',
            'email'    => 'test@sdu.dk',
            'password' => bcrypt('webtech2021'),
            'role'     => 'moderator',
        ]);


        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testModeratorText();
        });
    }

    public function testCaseModeratorOneStandardUser()
    {
        User::factory()->create([
            'name'     => 'Test user',
            'email'    => 'test@sdu.dk',
            'password' => bcrypt('webtech2021'),
            'role'     => 'standard',
        ]);


        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testModeratorTextNotPresent();
        });
    }

    public function testCaseModeratorTwo()
    {
        User::factory(10, [
            'role' => 'moderator'
        ])->has(Post::factory()->count(5))
            ->create();

        Post::all()->each(function (Post $post)
        {
            User::all()
                ->random(rand(0, User::all()->count()))
                ->reject(fn(User $user) => $user->id == $post->user_id);
        });
        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testUserDislikePost();
        });
    }

    public function testCaseModeratorThree()
    {
        User::factory()->create([
            'name'     => 'Test user',
            'email'    => 'test@sdu.dk',
            'password' => bcrypt('webtech2021'),
            'role'     => 'moderator',
        ]);

        $this->browse(function (Browser $browser)
        {
            $browser->visit(new HomePage())->testModeratorDelete();
        });
    }

}
