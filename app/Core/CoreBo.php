<?php

namespace App\Core;

use App\Services\config\bs\PwConfig;
use App\Services\user\bo\PwUserBo;
use App\Services\user\bs\PwUser;
use App\Services\user\dm\PwUserInfoDm;
use Request;

class CoreBo
{
    private static $_cache;
    private static $_config;
    private static $_var = [];

    private $_loginUser = null;

    public function V($key)
    {
        $_tmp = self::$_var;

        foreach (func_get_args() as $arg) {
            if (is_array($_tmp) && isset($_tmp[$arg])) {
                $_tmp = $_tmp[$arg];
            } else {
                return '';
            }
        }
        return $_tmp;
    }

    public static function setV($key, $value = '')
    {
        if (is_array($key) && empty($value)) {
            self::$_var = array_merge(self::$_var, $key);
        } else {
            self::$_var[$key] = $value;
        }
    }

    public function C($namespace = '', $key = '')
    {
        return self::$_config->C($namespace, $key);
    }

    public function setC(PwConfigBo $configBo)
    {
        self::$_config = $configBo;
    }

    public function cache()
    {
        return self::$_cache;
    }

    public function setCache(PwCache $cache)
    {
        self::$_cache = $cache;
    }

    public function initUser($request)
    {
        $_cOnlinetime = $this->C('site', 'onlinetime') * 60;
        $requestUri = $request->path();

        if (!($lastvisit = $request->cookie('lastvisit'))) {
            $onlinetime = 0;
            $lastvisit = time();
            $lastRequestUri = '';
        } else {
            list($onlinetime, $lastvisit, $lastRequestUri) = explode("\t", $lastvisit);
            ($thistime = time() - $lastvisit) < $_cOnlinetime && $onlinetime += $thistime;
        }

        $user = $this->getLoginUser($request);

        if ($user->isExists()) {
            $today = Tool::str2time(Tool::time2str(Tool::getTime(), 'Y-m-d'));

            if ($user->info['lastvisit'] && $today > $user->info['lastvisit']) {
                $loginSrv = app(PwLoginService::class);
                $loginSrv->welcome($user, $request->ip());
            } elseif ((time() - $user->info['lastvisit'] > min(1800, $_cOnlinetime))) {
                $dm = app(PwUserInfoDm::class, [$user->uid]);
                $dm->setLastvisit(time())->setLastActiveTime(time());

                if ($onlinetime > 0) {
                    $dm->addOnline($onlinetime > $_cOnlinetime * 1.2 ? $_cOnlinetime : $onlinetime);
                }

                app(PwUser::class)->editUser($dm, PwUser::FETCH_DATA);
                $onlinetime = 0;
            }
        }
        Tool::setCookie('lastvisit', $onlinetime . "\t" . time() . "\t" . $requestUri, 31536000);

        $obj = new \stdClass();
        $obj->lastvisit = $lastvisit;
        $obj->requestUri = $requestUri;
        $obj->lastRequestUri = $lastRequestUri;

        $this->setV('lastvist', $obj);
    }

    public function getLoginUser()
    {
        if ($this->_loginUser === null) {
            $user = $this->_getLoginUser();
            $user->ip = Request::ip();
            $this->_loginUser = $user->uid;
            PwUserBo::pushUser($user);
        }

        return PwUserBo::getInstance($this->_loginUser);
    }

    protected function _getLoginUser()
    {
        if (!($userCookie = Tool::getCookie('winduser'))) {
            $uid = $password = '';
        } else {
            list($uid, $password) = explode("\t", Tool::decrypt($userCookie));
        }

        $user = new PwUserBo($uid);

        if (!$user->isExists() || Tool::getPwdCode($user->info['password']) != $password) {
            $user->reset();
        } else {
            unset($user->info['password']);
        }

        return $user;
    }

    public function getConfigService()
    {
        return app(PwConfig::class);
    }
}


