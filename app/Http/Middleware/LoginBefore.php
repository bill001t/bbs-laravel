<?php
namespace App\Http\Middleware;

use App\Core\ErrorBag;
use App\Core\MiddlewareTrait;
use App\Services\invite\bm\PwInviteFriendService;
use Closure;
use Core;

class LoginBefore
{

    use MiddlewareTrait;

    public function handle($request, Closure $next)
    {
//        $this->loginUser = Core::getLoginUser($request);

        $currentRoute = Route::currentRouteName();
        $action = trim(mb_strrichr($currentRoute, '.'), '.');

        if ($this->loginUser->isExists() && !in_array($action, array('showverify', 'logout', 'show'))) {

            $inviteCode = $request->get('invite');
            if ($inviteCode) {
                $user = app(PwInviteFriendService::class)->invite($inviteCode, $this->loginUser->uid);
                if ($user instanceof ErrorBag) {
                    return $this->showError($user->getError());
                }
            }

            if ($action == 'fast') {
                return $this->showMessage('USER:login.success');
            } elseif ($action == 'welcome') {
                return redirect('u/login/show');
            } elseif ($request->ajax()) {
                return $this->showError('USER:login.exists');
            } else {
                return redirect($this->_filterUrl());
            }
        }

        return $next($request);
    }
}