<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function GuzzleHttp\Promise\all;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit() {
        $user = User::find(Auth::id());

        return view('profile', compact('user'));
    }

    public function update(Request $request) {
        $user = User::find(Auth::id());
        Validator::make($request->all(), [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        return redirect()->route('user.edit');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('day-calendar');
    }

    public function home()
    {
        return view('home');
    }
}
