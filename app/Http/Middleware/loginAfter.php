<?php
namespace App\Http\Middleware;

use Closure;

class LoginAfter
{
    public function handle($request, Closure $next)
    {
// 执行动作
        return $next($request);
    }
}