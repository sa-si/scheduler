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

    public function edit(Request $request) {
        $url = parse_url(url()->previous(), PHP_URL_PATH);
        $new_url = substr($url, 1);

        if ($request->path() === $new_url || is_null($request->headers->get('referer'))) {
            $previous_url = route('month');
        } else {
            $previous_url = $request->headers->get('referer');
        }

        $user_profile = User::find(Auth::id());

        return view('profile', compact('user_profile', 'previous_url'));
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
}
