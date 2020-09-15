<?php

namespace App\Core\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));

        return redirect($this->redirectPath());
    }


    public function __construct() {
        $this->config = Core::C('register');
        $this->isOpenInvite = (2 == $this->config['type'] ? 1 : 0);
        $this->isOpenMobileCheck = (1 == $this->config['active.phone'] ? 1 : 0);
    }

    public function checkIp($request) {
        if (!($ipSpace = abs($this->config['security.ip']))) return true;
        $space = $ipSpace * 3600;

        if ($this->hasTooManyRegisterIpAttempts($request, $space)) {
            return $this->sendLockoutResponse($request);
        }

        /*$registerDs = app('user.PwUserRegisterIp');
        $data = $registerDs->getRecodeByIp($ip);
        if (!$data || Tool::getTime() - $data['last_regdate'] > $space) return true;
        return new ErrorBag('USER:register.error.security.ip', array('{ipSpace}' => $ipSpace));*/
    }


}
