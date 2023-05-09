<?php

namespace Wave\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * https://MorganLevetti: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@github.com/MorganLevetti/erp-laravel-react.git
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
        if(setting('auth.email_or_username')){
            return setting('auth.email_or_username');
        }

        return 'email';
    }
    // Vue du Formulaire
    public function showLoginForm()
    {
        return view('theme::auth.login');
    }
    // Vérification email
    protected function authenticated(Request $request, $user)
    {
        if(setting('auth.verify_email') && !$user->verified){
            $this->guard()->logout();
            return redirect()->back()->with(['message' => 'Please verify your email before logging into your account.', 'message_type' => 'warning']);
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath())->with(['message' => 'Successfully logged in.', 'message_type' => 'success']);
    }


    public function logout(){
        Auth::logout();
        return redirect(route('wave.home'));
    }
}
