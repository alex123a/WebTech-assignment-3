<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index() {
        $feed = Post::withCount('likes')->latest()->paginate(10);
        return view('home', ['feed' => $feed]);
    }

    public function register() {
        return view('register');
    }

    public function doRegister(Request $request) {
        $user = new User();
        request()->validate([
            "name" => "required|max:300",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed"
        ]);
        $user->name = request("name");
        $user->email = request("email");
        $user->password = bcrypt(request("password"));
        $user->save();
        auth()->login($user);
        return redirect()->route("home");
    }

    public function doLogin(Request $request) {
        request()->validate([
            "email" => "required|exists:users,email",
            "password" => "required"
        ]);
        auth()->attempt(["email" => $request["email"], "password" => $request["password"]]);
        return redirect()->route("home");
    }

    public function logOut() {
        auth()->logout();
        return redirect()->route("home");
    }
}
