<?php

namespace App\Core\Auth;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;

trait ThrottlesIp
{
    /*同一ip下最多登陆次数*/
    protected function hasTooManyLoginIpAttempts(Request $request)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $this->getThrottleLoginIpKey($request),
            $this->maxLoginIpAttempts(), $this->loginIplockoutTime() / 60
        );
    }

    protected function getThrottleLoginIpKey(Request $request)
    {
        return 'login' . $request->ip();
    }

    protected function maxLoginIpAttempts()
    {
        return property_exists($this, 'maxLoginIpAttempts') ? $this->maxLoginIpAttempts : 100;
    }

    protected function loginIplockoutTime()
    {
        return property_exists($this, 'loginIpLockoutIpTime') ? $this->loginIpLockoutIpTime : 1800;
    }

    protected function incrementLoginIpAttempts(Request $request)
    {
        app(RateLimiter::class)->hit(
            $this->getThrottleLoginIpKey($request)
        );
    }

    protected function clearLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->clear(
            $this->getThrottleLoginIpKey($request)
        );
    }

    /*同一ip下最多注册次数*/
    protected function hasTooManyRegisterIpAttempts(Request $request)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $this->getThrottleRegisterIpKey($request),
            $this->maxRegisterIpAttempts(), $this->registerIplockoutTime() / 60
        );
    }

    protected function getThrottleRegisterIpKey(Request $request)
    {
        return 'register' . $request->ip();
    }

    protected function maxRegisterIpAttempts()
    {
        return property_exists($this, 'maxRegisterIpAttempts') ? $this->maxLoginIpAttempts : 100;
    }

    protected function registerIplockoutTime()
    {
        return property_exists($this, 'registerIpLockoutIpTime') ? $this->registerIpLockoutIpTime : 1800;
    }

    protected function incrementRegisterIpAttempts(Request $request)
    {
        app(RateLimiter::class)->hit(
            $this->getThrottleRegisterIpKey($request)
        );
    }

    protected function clearRegisterIpAttempts(Request $request)
    {
        app(RateLimiter::class)->clear(
            $this->getThrottleRegisterIpKey($request)
        );
    }

    /*显示验证码，无论是登陆，还是注册*/
    protected function hasTooManyIpAttempts(Request $request){
        return
            app(RateLimiter::class)->tooManyAttempts($this->getThrottleRegisterIpKey($request), 3, 15)
            ||
            app(RateLimiter::class)->tooManyAttempts($this->getThrottleLoginIpKey($request), 3, 15);
    }
}
