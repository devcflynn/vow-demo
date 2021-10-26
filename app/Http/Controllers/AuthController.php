<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * Register new User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',             
            'email' => 'required|string|email|max:255',
            'password' => 'required|confirmed|string'
        ]);

        try {
            // Create
            if($user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ])) {
                Auth::login($user);
                return redirect("credentials")->withSuccess('You have signed-in');
            } 
        } catch (\Exception $e) {
            return redirect('register.form')
                    ->withInput()
                    ->withErrors($e->getMessage());
        }
        
    }
    

    /**
     * Log our user into the application and provide a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */
    public function login(Request $request)
    {
        // Validate our input
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('credentials');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
       
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with([
            'success' => 'You have susccessfully logged out'
        ]);
    }

}
