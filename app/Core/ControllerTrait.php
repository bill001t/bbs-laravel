<?php

namespace App\Core;

use App\Core\Hook\BaseHookService;
use App\Core\Hook\Hook;
use Core;
use Illuminate\Http\Request;
use Response;

Trait ControllerTrait
{
    protected $loginUser = null;
    protected $request = null;

    public function __construct(Request $request)
    {
        $this->loginUser = Core::getLoginUser($request);
        $this->request = $request;
    }

    protected function runHook($registerKey, $bp)
    {
        if (!$registerKey) return;

        if (!$bp instanceof BaseHookService) {
            throw new CommonErrorException((new ErrorBag())->getError('class.type.fail',
                [
                    '{parm1}' => 'src.library.base.PwBaseController.runHook',
                    '{parm2}' => 'PwBaseHookService',
                    '{parm3}' => get_class($bp),
                ])
            );
        }

        if (!$filters = Hook::getRegistry($registerKey)) return;

        if (!$filters = Hook::resolveActionHook($filters, $bp)) return;

        $args = func_get_args();
        $_filters = array();

        foreach ($filters as $key => $value) {
            $args[0] = isset($value['method']) ? $value['method'] : '';
            $_filters[] = array('class' => $value['class'], 'args' => $args);
        }

        $this->resolveActionFilter($_filters);
    }

    protected function resolveActionFilter($filters)
    {
        if (!$filters) return;

        $chain = app(FilterChain::class);
        foreach ((array)$filters as $value) {
            $chain->addInterceptors(app($value['class'], $value['args']));
        }

        $chain->handle();
    }

    protected function showError($error = '', $referer = '', $refresh = false, $iserror = true)
    {
        return $this->showMessage($error, $referer, $refresh, $iserror);
    }

    protected function showMessage($message = '', $referer = '', $refresh = false, $iserror = false)
    {
        if ((empty($message) || $iserror === false) && $this->request->ajax()) {
            $headers = array('Content-Type' => 'application/json');

            $data = [
                'state' => 'success',
                'message' => (array)$message,
                'referer' => $referer,
                'refresh' => $refresh,
            ];

            return Response::json($data, 200, $headers, JSON_UNESCAPED_UNICODE);
        }

        /*如果为数组，第1个为消息类型，第2个为消息的参数*/
        if (is_array($message)) {
            $key = $message[0];
            $vars = $message[1];
        } else {
            $key = $message;
            $vars = [];
        }

        $messages = MessageTool::getMessage($key, $vars);

        if ($this->request->ajax()) {
            $headers = array('Content-Type' => 'application/json');

            $data = [
                'state' => 'fail',
                'message' => (array)$messages,
                'referer' => $referer,
                'refresh' => $refresh,
            ];

            return Response::json($data, 200, $headers, JSON_UNESCAPED_UNICODE);
        }

        return view('common.error')
            ->with('message', is_array($messages) ? $messages : (array)$messages)
            ->with('refresh', $refresh)
            ->with('referer', $referer);
    }
}