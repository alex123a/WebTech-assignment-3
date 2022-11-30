<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use App\Models\User;

class Homepage extends Page
{
    use withFaker;

    public function __construct(){
        $this->setUpFaker();
    }
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '.',

        ];

    }



    public function testGuestShowFeed(Browser $browser){
        $browser->visit('/')->assertGuest();
        for ($posts = 1; $posts <= 10; $posts++) {
            $browser->assertPresent('section > div > div > div.column.is-8 > div > div > div:nth-child('.$posts.')');
        }
        $browser->assertNotPresent('section > div > div > div.column.is-8 > div.columns.mb-6 > div > form');
    }

    public function testRegisterUser(Browser $browser){
        $browser->visit('');
        $browser->assertPresent('.register-link');
        $browser->click('.register-link');
        $browser->assertPathIs('/register');
        $browser->assertPresent('input[type=text].name');
        $browser->assertPresent('input[type=email].email');
        $browser->assertPresent('input[type=password].password');
        $browser->assertPresent('input[type=password].password-confirmation');
        $browser->type('.name', 'John Doe');
        $browser->type('.email', 'john@doe.com');
        $browser->type('.password', '1234');
        $browser->type('.password-confirmation', '1234');
        $browser->assertPresent('.register-submit');
        $browser->click('.register-submit');
        $browser->assertPathIs('/');
        $browser->assertAuthenticated();
        $browser->logout();
    }

    public function testLoginUser(Browser $browser)
    {
        $browser->visit('/');
        $browser->assertPresent('input[type=email].email');
        $browser->assertPresent('input[type=password].password');
        $browser->type('.email', 'john@doe.com');
        $browser->type('.password', '1234');
        $browser->assertPresent('.login-submit');
        $browser->click('.login-submit');
        $browser->assertPathIs('/');
        $browser->assertAuthenticated();
    }

    public function testUserIsLoggedIn(Browser $browser)
    {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertPresent('.user-name');
        $browser->assertPresent('.user-name-nav');
        $browser->assertSeeIn('.user-name', User::find(1)->name);
        $browser->assertSeeIn('.user-name-nav', User::find(1)->name);
        $browser->assertNotPresent('.register-link');
        $browser->assertNotPresent('.login-panel');
    }

    public function testUserLogout(Browser $browser)
    {
        $browser->loginAs(User::find(1));
        $browser->assertAuthenticated();
        $browser->visit('/');
        $browser->assertPresent('.logout-link');
        $browser->click('.logout-link');
        $browser->assertPathIs('/');
        $browser->assertGuest();
    }

    public function testUserShowFeed(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');

        for ($posts = 1; $posts <= 10; $posts++) {
            $browser->assertPresent('.post:nth-child('.$posts.')');
        }
        $browser->assertPresent('section > div > div > div.column.is-8 > div.columns.mb-6 > div > form');
    }

    public function testUserLikePost(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        for ($posts = 1; $posts <= 10; $posts++) {
            $browser->assertPresent('.post:nth-child('.$posts.')');
        }
        for ($posts = 1; $posts <= 2; $posts++) {
            $browser->assertSeeIn('.post:nth-child('.$posts.') > article', 'Like');
            $browser->click(".post:nth-child($posts) .like");
            $browser->assertPathIs('/');
            $browser->assertSeeIn(".post:nth-child($posts) .dislike", 'Dislike');
        }
    }

    public function testUserDislikePost(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');

        for ($posts = 1; $posts <= 5; $posts++) {
            $browser->assertSeeIn('.post:nth-child('.$posts.') > article', 'Like');
            $browser->click(".post:nth-child($posts) .like");
            $browser->assertPathIs('/');
            $browser->assertSeeIn(".post:nth-child($posts) .dislike", 'Dislike');
            $browser->assertNotPresent(".post:nth-child($posts) .like");
        }

        for ($posts = 1; $posts <= 5; $posts++) {
            $browser->assertSeeIn('.post:nth-child('.$posts.') > article', 'Dislike');
            $browser->click(".post:nth-child($posts) .dislike");
            $browser->assertPathIs('/');
            $browser->assertSeeIn(".post:nth-child($posts) .like", 'Like');
            $browser->assertNotPresent(".post:nth-child($posts) .dislike");
        }

    }

    public function testUserDeletePost(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');

        for ($posts = 1; $posts <= 3; $posts++) {
            $browser->assertPresent('.textarea');
            $browser->type('.textarea', $this->faker->sentences(2));
            $browser->click('.submit-post');
            $browser->assertPathIs('/');
        }


        for ($posts = 3; $posts >= 2; $posts--) {
            $delete_button = ".post:nth-child($posts) .delete-post";
            $browser->assertPresent($delete_button);
            $browser->click($delete_button);
            $browser->assertSee('The post was deleted');
        }

        $browser->loginAs(User::find(2));
        $browser->visit('/');
        $browser->assertNotPresent('.delete-post');

    }

    public function testModeratorText(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertPresent('.moderator');
        $browser->assertSeeIn('.moderator', 'Moderator');
    }

    public function testModeratorTextNotPresent(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertNotPresent('.moderator');
    }

    public function testModeratorDelete(Browser $browser) {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertSee('This list seems to be empty');
        for ($posts = 1; $posts <= 2; $posts++) {
            $browser->assertPresent('.textarea');
            $browser->type('.textarea', $this->faker->sentences(2));
            $browser->click('.submit-post');
        }

        for($posts = 2; $posts >= 1; $posts--) {
            $delete_button = ".post:nth-child($posts) .delete-post";
            $browser->assertPresent($delete_button);
            $browser->click($delete_button);
            $browser->assertSee('The post was deleted');
        }

        $browser->assertSee('This list seems to be empty');
    }


}
