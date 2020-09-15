<?php

namespace App\Services\hook\bm;

use App\Services\hook\bs\PwHookInject;
use App\Services\hook\bs\PwHook;

class PwHookRefresh
{
    public function refresh()
    {
        $conf = config('hook');/*获取整个hook.php文件，这样是否有问题？？？？*/
        if (!$conf || !is_array($conf)) return new ErrorBag('fail');
        $hooks = $inject = array();
        foreach ($conf as $k => $v) {
            $hooks[] = array(
                'name' => $k,
                'app_name' => '系统',
                'created_time' => time(),
                'document' => implode("\r\n",
                    array($v['description'], implode("\n", (array)$v['param']), $v['interface'])));
            foreach ($v['list'] as $k1 => $v1) {
                $inject[] = array(
                    'hook_name' => $k,
                    'app_id' => 'system',
                    'app_name' => '系统',
                    'alias' => $k1,
                    'class' => $v1['class'],
                    'method' => $v1['method'],
                    'loadway' => $v1['loadway'],
                    'expression' => $v1['expression'],
                    'description' => $v1['description'],
                    'created_time' => time());
            }
        }
        $this->_loadHooks()->delByAppId('');
        $this->_loadHookInject()->deleteByAppId('');
        $this->_loadHookInject()->deleteByAppId('system');
        $this->_loadHooks()->batchAdd($hooks);
        $this->_loadHookInject()->batchAdd($inject);
        return true;
    }


    /**
     * @return PwHooks
     */
    private function _loadHooks()
    {
        return app(PwHooks::class);
    }

    /**
     * @return PwHookInject
     */
    private function _loadHookInject()
    {
        return app(PwHookInject::class);
    }
}

?>