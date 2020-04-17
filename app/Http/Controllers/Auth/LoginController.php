<?php

namespace App\Http\Controllers\Auth;


// 

// use Laravel\Socialite\Two\User;

use Google_Client;
use Google_Service_Drive;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;  

use App\Providers\RouteServiceProvider;


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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function redirectToProvider()
    {
        $parameters = ['access_type' => 'offline'];
        return Socialite::driver('google')->scopes(["https://www.googleapis.com/auth/drive"])->with(
            $parameters)->redirect();
            
            // dd($parameters); //estas lineas son para comporbaciones
            // die();
        
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $userLogin = Socialite::driver('google')->stateless()->user();
            // dd($userLogin);
           
        $user = User::updateOrCreate(
            [
                'email' => $userLogin->email
            ],
            [               
                'refresh_token'=> $userLogin->token,
                'name' => $userLogin->name
            ]);
            
            // dump($userLogin);
            // dump(User::get());
            // dd($user->get());

            // die(); //con dd ya die
            
        Auth::login($user,true);
        return redirect()->to('/');

       /////// // $user->token;
    }

    public function logout(Request $request){

        session('g_token','');
        $this->guard()->logout();

        $request->session()->invalidate();
        return redirect('/');

    }

}
