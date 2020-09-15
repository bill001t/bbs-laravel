<?php

namespace App\Services\forum\bm;

use App\Core\Tool;
use App\Services\attach\bm\PwAttachDisplay;
use App\Services\credit\bo\PwCreditBo;
use App\Services\forum\bo\PwForumBo;
use App\Services\forum\bo\PwThreadBo;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bs\PwTopicType;

/**
 * 帖子显示流程
 *
 * -> 1.check 检查帖子显示运行环境
 * -> 2.appendDo(*) 增加帖子显示时的行为动作,例:投票、悬赏等(可选)
 * -> 3.execute 数据准备
 * -> 4.get... 获取数据以满足页面显示要求
 */
class PwThreadDisplay
{

    public $page = 1;
    public $perpgae = 20;
    public $total = 0;
    public $maxpage;

    public $tid;
    public $fid;
    public $isBM;
    public $readdb = array();

    public $user;    //PwUserBo
    public $thread;    //PwThreadBo
    public $forum;    //PwForumBo
    public $attach;    //PwAttachDisplay

    protected $_ds;
    protected $users = array();
    protected $area = array();
    protected $_floorName;
    protected $_definedFloorName;

    private $imgLazy = false;

    public function __construct($tid/*, PwUserBo $user*/)
    {
        $this->thread = new PwThreadBo($tid);
        $this->tid = $tid;
        $this->fid = $this->thread->fid;
        $this->forum = new PwForumBo($this->fid);
//		$this->user = $user;
        $this->isBM = $this->forum->isBM($this->user->username);
//		$config = Core::C('bbs');
//		$this->_floorName = $config['read.floor_name'];
//		$this->_definedFloorName = $this->_parseDefindFloorName($config['read.defined_floor_name']);
    }

    /**
     * 检查帖子显示运行环境
     *
     * @return bool|ErrorBag
     */
    public function check()
    {
        if (!$this->thread->isThread()) {
//			return new ErrorBag('BBS:forum.thread.exists.not');
        }
        if (!$this->forum->isForum()) {
//			return new ErrorBag('BBS:forum.exists.not');
        }
        if (($result = $this->forum->allowVisit($this->user)) !== true) {
//			return new ErrorBag('BBS:forum.permissions.visit.allow', array('{grouptitle}' => $this->user->getGroupInfo('name')));
        }
        if (($result = $this->forum->allowRead($this->user)) !== true) {
//			return new ErrorBag('BBS:forum.permissions.read.allow', array('{grouptitle}' => $this->user->getGroupInfo('name')));
        }
        if (!$this->forum->foruminfo['allow_read'] && !$this->user->getPermission('allow_read') && $_COOKIE) {
//			return new ErrorBag('permission.read.allow', array('{grouptitle}' => $this->user->getGroupInfo('name')));
        }
        if ($this->thread->isDeleted()) {
//			return new ErrorBag('BBS:forum.thread.deleted');
        }
        if (!$this->thread->isChecked() && $this->thread->authorid != $this->user->uid && !$this->isBM) {
//			$permission = $this->user->getPermission('panel_bbs_manage', false, array());
//			if (!$permission['thread_check']) return new ErrorBag('BBS:forum.thread.ischeck');
        }
        if ($this->thread->info['tpcstatus'] && Tool::getstatus($this->thread->info['tpcstatus'], PwThread::STATUS_CLOSED) && !$this->user->getPermission('operate_thread.lock', $this->isBM)) {
//			return new ErrorBag('BBS:forum.thread.closed');
        }
    }

    /**
     * 逻辑处理，数据准备
     */
    public function execute(PwReadDataSource $ds)
    {
        $this->_ds = $ds;
        $ds->execute();
        $this->perpage = $ds->perpage;
        $this->maxpage = $ds->maxpage;
        $start = $ds->firstFloor;
//		$this->bulidUsers($ds->getUser());
        $this->readdb =& $ds->getData();
//		$this->_initAttachs($ds->getAttach());

        foreach ($this->readdb as $key => $read) {
            $this->readdb[$key] = $this->bulidRead($read, $start++);
        }
        $this->thread->hit();
    }

    /**
     * 加工帖子数据
     *
     * @param array $read 帖子数据(来自数据库)
     * @param int $lou 楼层
     * @return array
     */
    public function bulidRead($read, $lou)
    {
        $read['lou'] = $lou;
        if (!$read['usehtml']) {
            $read['content'] = Tool::escapeHTML($read['content']);
        }
        $tip = '';
        $display = 1;
        if ($read['ifshield']) {
            list($tip, $display) = $this->_bulidShieldContent();
        } elseif ($this->users[$read['created_userid']]['groupid'] == '6') {
            list($tip, $display) = $this->_bulidBanContent();
        }
        if ($display) {
            $read['content'] = $tip . $this->_bulidContent($read);
        } else {
            $read['content'] = $tip;
            $this->attach && $this->attach->deleteList($read['pid']);
        }
        return $read;
    }

    /**
     * 获取主题信息
     *
     * @return array
     */
    public function getThreadInfo()
    {
        return $this->thread->getThreadInfo();
    }

    /**
     * 获取帖子内容数据
     *
     * @return array
     */
    public function getList()
    {
        return $this->readdb;
    }

    /**
     * 获取版块对象
     *
     * @return PwForumBo
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * 获取用户信息
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function getArea()
    {
        return $this->area;
    }

    /**
     * 获取楼层名称
     *
     * @param int $lou 楼层号
     * @return string
     */
    public function getFloorName($lou)
    {
        return isset($this->_definedFloorName[$lou]) ? $this->_definedFloorName[$lou] : $lou . $this->_floorName;
    }

    public function getTopicTypeName($id)
    {
        $array = $this->_getTopicTypeDs()->getTopicType($id);
        return $array['name'];
    }

    /**
     * 获取当前路径导航条
     *
     * @return string
     */
    public function getHeadguide()
    {
        return $this->forum->headguide()
        . $this->forum->bulidGuide(array(Tool::substrs($this->thread->info['subject'], 30), url('bbs/read/run', array('tid' => $this->tid, 'fid' => $this->fid))));
    }

    public function setUrlArg($key, $value)
    {
        $this->_ds->setUrlArg($key, $value);
    }

    public function getUrlArgs($except = '')
    {
        return $this->_ds->getUrlArgs($except);
    }

    /**
     * 帖子内容中，图片懒加载设置
     *
     * @param bool $isLazy
     */
    public function setImgLazy($isLazy)
    {
        $this->imgLazy = empty($isLazy) ? false : true;
    }

    /**
     * 准备用户显示信息
     *
     * @param array $uids 用户id序列
     * @return array
     */
    public function bulidUsers($uids)
    {
        $groupRight = Core::cache()->get('group_right');
        $uids = array_unique($uids);
        $users = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_ALL);
        in_array('0', $uids) && $users['0'] = $this->_getGuestInfo();
        foreach ($users as $key => $value) {
            $value['groupid'] == '0' && $value['groupid'] = $value['memberid'];
            if ($value['bbs_sign']) {
                $value['bbs_sign'] = $this->_bulidBbsSign($value['bbs_sign'], $groupRight[$value['groupid']], $value['status']);
            }
            $users[$key] = $value;
        }
        $this->users = $this->runWithFilters('bulidUsers', $users);
    }

    public static function escapeSpace($str)
    {
        $str = str_replace(array('  ', "\n ", "\n"), array(' &nbsp;', '<br />&nbsp;', '<br />'), $str);
        $str[0] === ' ' && $str = '&nbsp;' . ltrim($str);
        return $str;
    }


    protected function _bulidContent($read)
    {
        return self::escapeSpace($read['content']);
    }

    protected function _bulidShieldContent()
    {
        $tip = '<div class="shield">此帖已被屏蔽</div>';
        if (!$this->user->getPermission('operate_thread.shield', $this->isBM)) {
            return array($tip, 0);
        }
        return array($tip, 1);
    }

    protected function _bulidBanContent()
    {
        $tip = '<div class="shield">用户被禁言,该主题自动屏蔽!</div>';
        if (!$this->user->getPermission('operate_thread.ban', $this->isBM)) {
            return array($tip, 0);
        }
        return array($tip, 1);
    }

    protected function _bulidBbsSign($sign, $groupRight, $userstatus)
    {

    }

    protected function _parseDefindFloorName($string)
    {
        $array = array(0 => '楼主');
        $_tmp = explode("\n", $string);
        foreach ($_tmp as $value) {
            list($key, $name) = explode(':', trim($value));
            $names = explode(',', $name);
            foreach ($names as $v) {
                $array[$key++] = $v;
            }
        }
        return $array;
    }

    protected function _initAttachs($pids)
    {
        if ($pids) {
            $this->attach = new PwAttachDisplay($this->tid, $pids, $this->user, $this->imgLazy);
        }
    }

    protected function _getGuestInfo()
    {
        $info = array(
            'groupid' => 2,
            'postnum' => 0,
            'fans' => 0,
            'follows' => 0,
            'lastvisit' => Tool::getTime()
        );
        foreach (PwCreditBo::getInstance()->cType as $key => $value) {
            $info['credit' . $key] = 0;
        }
        return $info;
    }

    protected function _getTopicTypeDs()
    {
        return app(PwTopicType::class);
    }

    protected function _getInterfaceName()
    {
        return 'PwThreadDisplayDoBase';
    }
}