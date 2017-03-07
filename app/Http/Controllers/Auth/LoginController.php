<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
 
    public function login(Request $request)
    {
        $username   = $request->get('username');
        $password   = $request->get('password');
        $remember   = $request->get('remember');
 
        if (auth()->attempt([
            'username'     => $username,
            'password'  => $password,
            'activated'  => 1,
        ], $remember == 1 ? true : false)) {
            return redirect()->route('admin.dashboard');
        }
        else {
             return redirect()->back()
                ->with('message','Incorrect username or password')
                ->with('status', 'danger')
                ->withInput();
        }
 
    }
}
