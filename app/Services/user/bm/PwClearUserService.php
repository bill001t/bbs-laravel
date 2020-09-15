<?php

namespace App\Services\user\bm;

use App\Core\CommonValidator;
use App\Core\ErrorBag;
use App\Core\Hook\BaseHookService;
use App\Core\Tool;
use App\Services\credit\bo\PwCreditBo;
use App\Services\message\bs\PwNoticeService;
use App\Services\user\bo\PwUserBo;
use App\Services\user\bs\PwUser;
use App\Services\space\bs\PwSpace;
use App\Services\user\validator\PwUserValidator;
use Core;
use Request;

class PwClearUserService extends BaseHookService
{

    private $uid = 0;
    private $operator = null;
    private $totalClearTypes = 0;
    private $nowClearTypes = 0;

    /**
     * 清理用户信息
     */
    public function __construct($uid = 0, PwUserBo $operator = null)
    {
        $this->uid = intval($uid);
        $operator && $this->operator = $operator;
    }

    /**
     * 执行
     *
     * @param array $clearType 具体清理的项目
     * @return boolean
     */
    public function run($clearType)
    {
        if (0 >= $this->uid || empty($clearType)) return false;
        $this->init($clearType);
        if ($this->nowClearTypes == 0) return false;
        $this->runDo('gleanData', $this->operator);
        $this->runDo('run', $this->uid);
        //当清理用户全部信息的时候，将会清除用户的资料
        if ($this->ifClearAllUserInfo()) {
            $result = $this->_getUserDs()->deleteUser($this->uid);
            app(PwUserService::class)->restoreDefualtAvatar($this->uid);
            app(PwSpace::class)->deleteInfo($this->uid);
        }
        return true;
    }

    /**
     * 是否清楚帐号的所有信息(如果是返回true，否则返回false)
     *
     * @return boolean
     */
    public function ifClearAllUserInfo()
    {
        return $this->nowClearTypes == $this->totalClearTypes;
    }

    /**
     * 返回支持的用户清理清理项目,可对类型进行扩展
     *
     * @return array
     */
    public function getClearTypes()
    {
        //【用户清理】扩展-添加到hooks.PwClearUser下
        return array(
            'topic' => array('title' => '主题', 'class' => 'SRC:hooks.PwClearUser.PwClearDoTopic'),  //主题
            'post' => array('title' => '回复', 'class' => 'SRC:hooks.PwClearUser.PwClearDoPost'),  //回复
            'message' => array('title' => '消息', 'class' => 'SRC:hooks.PwClearUser.PwClearDoMessage'),  //消息
            //'fresh' => array('title' => '新鲜事', 'class' => 'SRC:hooks.PwClearUser.PwClearDoFresh')//新鲜事
        );
    }

    /* (non-PHPdoc)
     * @see PwBaseHookService::_getInterfaceName()
     */
    protected function _getInterfaceName()
    {
        return 'iPwGleanDoHookProcess';
    }

    /**
     * 初始化
     */
    private function init($clearType)
    {
        $types = $this->getClearTypes();
        $num = 0;
        foreach ($clearType as $item) {
            $_type = isset($types[$item]) ? $types[$item] : array();
            if (!$_type || !$_type['class']) continue;
            /* @var $instance iPwDoHookProcess */
            $this->appendDo(app($_type['class'], [$this]));
            $num++;
        }
        $this->totalClearTypes = count($types);
        $this->nowClearTypes = $num;
        return true;
    }

    /**
     * 获得用户Ds
     *
     * @return PwUser
     */
    private function _getUserDs()
    {
        return app('user.PwUser');
    }
}
