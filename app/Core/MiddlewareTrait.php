<?php

namespace App\Core;

use App\Core\Hook\BaseHookService;
use App\Core\Hook\Hook;
use Core;
use Illuminate\Http\Request;

Trait MiddlewareTrait
{
    protected $loginUser = null;

    public function __construct(Request $request)
    {
        $this->loginUser = Core::getLoginUser($request);
    }

    protected function showError($error = '', $referer = '', $refresh = false)
    {
        return $this->showMessage($error, $referer, $refresh);
    }

    protected function showMessage($message = '', $referer = '', $refresh = false)
    {
        /*如果为数组，第1个为消息类型，第2个为消息的参数*/
        if (is_array($message)) {
            $key = $message[0];
            $vars = $message[1];
        }else{
            $key = $message;
            $vars = [];
        }

        $messages = (new MessageTool($key, $vars))->get($key);

        if (empty($messages)) {
            return;
        }

        return view('common.error')
            ->with('message', is_array($messages) ? $messages : (array)$messages)
            ->with('refresh', $refresh)
            ->with('referer', $referer);
    }
}