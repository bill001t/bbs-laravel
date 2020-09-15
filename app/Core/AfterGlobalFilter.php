<?php

namespace App\Core;

use App\Services\online\bs\PwGuestOnline;
use App\Services\online\bs\PwUserOnline;
use App\Services\online\dm\PwOnlineDm;
use Closure;
use Core;

class AfterGlobalFilter
{
    public function handle($request, Closure $next)
    {
        $respose = $next($request);

        $this->updateOnline($request);

        return $respose;
    }

    protected function updateOnline($request)
    {
        $loginUser = Core::getLoginUser();

        $online = app('online.srv.PwOnlineService');

        if ($loginUser->uid > 0 && $request->path == 'bbs/thread/run') {
            $createdTime = $online->forumOnline($this->getInput('fid'));
        } else {
            $clientIp = $loginUser->ip;
            $createdTime = $online->visitOnline($clientIp);
        }

        if (!$createdTime) return false;

        $dm = app(PwOnlineDm::class);
        $time = time();
        if ($loginUser->uid > 0) {
            $dm->setUid($loginUser->uid)->setUsername($loginUser->username)->setModifytime($time)->setCreatedtime($createdTime)->setGid($loginUser->gid)->setFid($this->getInput('fid', 'get'))->setRequest($request['mca']);
            app(PwUserOnline::class)->replaceInfo($dm);
        } else {
            $dm->setIp($clientIp)->setCreatedtime($createdTime)->setModifytime($time)->setFid($this->getInput('fid', 'get'))->setTid($this->getInput('tid', 'get'))->setRequest($request->path());
            app(PwGuestOnline::class)->replaceInfo($dm);
        }
    }
}