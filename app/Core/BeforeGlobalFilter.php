<?php

namespace App\Core;

use App\Services\site\bm\PwSiteStatusService;
use App\Core\Hook\Hook;
use Closure;
use Core;

class BeforeGlobalFilter
{
    public function handle($request, Closure $next)
    {
        $url = $request->path();

        Core::setV(config('optimization'));
        Core::setV('lang_path', dirname(app()->path()) . '/resources/lang/' . config('app.locale') . '/');

        Core::setCache(app(PwCache::class));

        $this->_setPreCache($url);

        Core::cache()->mergeKeys(Core::V('cacheKeys'));
        Core::setC(app(\App\Core\PwConfigBo::class, ['re' => \App\Core\Corebo::class]));
        Core::C()->sets(Core::cache()->get('config'));
        Core::initUser($request);
        Core::setV('timestamp', time());
        Core::setV('charset', 'UTF-8');

        $loginUser = Core::getLoginUser($request);

        $config = Core::C('site');
        if (isset($config['visit.state']) && $config['visit.state'] > 0) {
            $service = app(PwSiteStatusService::class);
            $resource = $service->siteStatus($loginUser, $config);
            if ($resource instanceof ErrorBag) {
                if (!($config['visit.state'] == 1 && $request['mc'] == 'u/login')) {
                    throw new CommonErrorException((new ErrorBag())->getError($resource->getError()));
                }
            }
        }

        /*if (!in_array($url, array('u/login', 'u/register', 'u/findPwd')) && !$loginUser->getPermission('allow_visit')) {
            if ($loginUser->isExists()) {
                throw new CommonErrorException((new ErrorBag())->getError('permission.visit.allow', ['{grouptitle}' => $loginUser->getGroupInfo('name')]));
            } else {
                return redirect('u/login');
            }
        }*/

         if ($config['refreshtime'] > 0 && $request->isMethod('get') && !$request->ajax()) {
             if (Core::V('lastvist')->lastRequestUri == Core::V('lastvist')->requestUri && (Core::V('lastvist')->lastvisit + $config['refreshtime']) > Tool::getTime()) {
                 throw new CommonErrorException((new ErrorBag())->getError('SITE:refresh.fast'));
             }
         }

         $this->_setPreHook($request);

        return $next($request);
    }

    protected function _setPreCache($url)
    {
        $precache = Core::V('precache');

        if (isset($precache[$url])) {
            Core::cache()->preset($precache[$url]);
        }
    }

    protected function _setPreHook($request)
    {
        $prehook = Core::V('prehook');

        Hook::preset($prehook['ALL']);

        Hook::preset($prehook[Core::getLoginUser($request)->isExists() ? 'LOGIN' : 'UNLOGIN']);

        if (isset($prehook[$request->path()])) {
            Hook::preset($prehook[$request->path()]);
        }
    }
}