<?php

namespace App\Core;

class MessageTool extends BaseMessageBag
{
    public static function getMessage($key = '', $var = array())
    {
        return app(BaseMessageBag::class)->add($key, $var)->get($key);
    }
}