<?php

namespace Illuminate\Foundation\Auth;

use App\Events\UserLoginError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator;

trait AuthenticatesUsers
{
    use RedirectsUsers;

    public function __construct(){
        $this->loginConfig = Core::C('site', 'login');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request) {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        $throttles = $this->isUsingThrottlesLoginsTrait();
        $throttlesIp = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request) || $throttlesIp && $this->hasTooManyLoginIpAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        $username = $request->{$this->loginUsername()};

        $arr = ['mobile' => 'regex:/^1\d{10}$/',
            'uid' => 'digits:8',
            'email' => 'email',
            'username' =>  'regex:/^[\x7f-\xff\dA-Za-z\.\_]+$/',
        ];

        $arr_translate = ['mobile' => 4,
            'uid' => 1,
            'email' => 2,
            'username' =>  3,
        ];
        foreach($arr as $k => $v){
            if(Validator::make($username, [$this->loginUsername(), $v])->passes() && in_array($arr_translate[$k], $this->loginConfig['ways'])){
                if($k == 'mobile'){
                    if(empty(app(PwUserMobile::class)->getByMobile($username)[0]->uid)){
                        continue;
                    }

                    $k = app(PwUserMobile::class)->getByMobile($username)[0]->uid;
                }
                if(Auth::attempt($credentials + [$k => $username], $request->has('remember'))){
                    return $this->handleUserWasAuthenticated($request, $throttles);
                }
            }
        }

        $dm = [];
        $dm['username'] = $username;
        $dm['typeid'] = -1;
        $dm['ip'] = $request->ip;
        event(new UserLoginError($dm));

        if ($throttles || $throttlesIp) {
            $this->incrementLoginAttempts($request);
            $this->incrementLoginIpAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only($this->loginUsername(), 'password');
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? Lang::get('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }

    protected function isUsingThrottlesLoginsIpTrait()
    {
        return in_array(
            ThrottlesLoginsIp::class, class_uses_recursive(get_class($this))
        );
    }
}
