<?php

namespace App\Core\Hook;

use Illuminate\Support\Facades\Storage;
use App\Services\hook\bm\PwHookInjectService;
use App\Core\Utility;

class Hook
{
    private static $hooks = array();
    private static $prekeys = array();
    private static $prehooks = array();

    public static function preset($keys)
    {
        foreach ($keys as $key) {
            self::$prekeys[] = $key;
        }
    }

    public static function initRegistry()
    {
        $data = app(PwHookInjectService::class)->fetchInjectByHookName(self::$prekeys);
        foreach ($data as $key => $value) {
            self::$hooks[$key] = $value;
        }
        self::$prekeys = array();
    }

    public static function getRegistry($registerKey)
    {
        if (self::$prekeys) self::initRegistry();
        if (!isset(self::$hooks[$registerKey])) {
            self::$hooks[$registerKey] = app(PwHookInjectService::class)->getInjectByHookName($registerKey);
        }
        if (isset(self::$prehooks[$registerKey])) {
            self::$hooks[$registerKey] = array_merge(self::$hooks[$registerKey], self::$prehooks[$registerKey]);
            unset(self::$prehooks[$registerKey]);
        }
        return self::$hooks[$registerKey];
    }

    public static function registerHook($registerKey, $inject)
    {
        self::$prehooks[$registerKey][] = $inject;
    }

    public static function resolveActionHook($filters, $service = null)
    {
        $_filters = array();
        foreach ((array)$filters as $filter) {
            if (empty($filter['class'])) continue;
            if (!class_exists ($filter['class'])) continue;
            if (!empty($filter['expression'])) {
                $v1 = '';
                list($n, $p, $o, $v2) = Utility::resolveExpression($filter['expression']);
                switch (strtolower($n)) {
                    case 'service':
                        $call = array($service, 'getAttribute');
                        break;
                    case 'config':
                        $call = array(self, '_getConfig');
                        break;
                    case 'global':
                        $call = array('Wekit', 'getGlobal');
                        break;
                    default:
                        $call = array(self, '_getRequest');
                        break;
                }
                $v1 = call_user_func_array($call, explode('.', $p));
                if (!Utility::evalExpression($v1, $v2, $o)) continue;
            }
            $_filters[] = $filter;
        }
        return $_filters;
    }

    private static function _getRequest($key, $method = 'get')
    {
        if (!$key) return '';
        switch (strtolower($method)) {
            case 'get':
                return Wind::getApp()->getRequest()->getGet($key);
            case 'post':
                return Wind::getApp()->getRequest()->getPost($key);
            default:
                return Wind::getApp()->getRequest()->getRequest($key);
        }
    }

    private static function _getConfig($var)
    {
        if (func_num_args() > 1) {
            $args = array_slice(func_get_args(), 1);
            return Core::C($var, implode('.', $args));
        }
        return '';
    }

    public function hook($expression)
    {
        $method = isset($expression['method']) ? $expression['method'] : 'runDo';
        $args = isset($expression['args']) ? $expression['args'] : '';

        if (isset($expression['class'])) {
            $callback = [app($expression['class']), $method];
        } else {
            $callback = $method;
        }

        return self::display($callback, $args);
    }

    public static function display($callback, $args)
    {
        if (!$callback || !is_array($args)) return;
        return call_user_func_array($callback, $args);
    }

    public static function template($template)
    {
        $args = func_get_args();
        unset($args[0]);

        $templateFile = config('view.paths')[0] . '/hook/' . trim($template, '/');

        $view_path = str_replace(['/', '\\'], '.', 'hook/' . trim($template, '/'));
            view()->composer($view_path, function ($view) use ($args) {
                $view->with($args);
            });

        if (!file_exists($templateFile)) {
            throw Exception('模板文件不存在');
        }

        $content = Storage::get($templateFile);

        return $content;
    }

}

?>