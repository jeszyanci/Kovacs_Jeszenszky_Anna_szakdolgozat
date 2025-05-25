<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Log;

 
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $pw = DB::table('users')
            ->where('email', $credentials['email'])
            ->select('password')
            ->get();

        if (Hash::check($credentials['password'], $pw[0]->password)) {
            if ( Auth::attempt($credentials)) {
                $request->session()->regenerate();
    
                Log::create('login', 'User logged in');

                return redirect()->route('mainPage');
            }  
        } else {
            return redirect()->route('login');

            Log::create('login', 'User failed to log in');
            // TODO: sweetalert - wrong pw / email
        }

    }
}