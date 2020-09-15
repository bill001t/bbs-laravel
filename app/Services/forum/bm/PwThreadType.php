<?php
namespace App\Services\forum\bm;

use App\Core\Hook\SimpleHook;

/**
 * 可扩展的帖子类型
 */

class PwThreadType
{

    public $tType = array(
        'default' => array('普通帖', '发布主题', true),
        'poll' => array('投票帖', '发起投票', 'allow_add_vote'),
        //'2' => array('悬赏帖', '发起悬赏', true),
        //'3' => array('商品帖', '发布商品', true),
        //'4' => array('辩论帖', '发起辩论')
    );

    public function __construct()
    {
        $this->tType = SimpleHook::getInstance('PwThreadType')->runWithFilters($this->tType);
    }

    public function getTtype()
    {
        return $this->tType;
    }

    public function has($special)
    {
        return isset($this->tType[$special]);
    }

    public function getName($special)
    {
        return $this->tType[$special][0];
    }
}