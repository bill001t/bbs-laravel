<?php
Wind::import('SRV:invite.vo.PwInviteCodeSo');
/**
 * 邀请好友
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: InviteController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package service.products.bbs.controller
 */
class InviteController extends Controller{
	private $regist = array();
	
	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run', array('backurl' => 'my/invite/run')));
		}
		$this->regist = Core::C('register');
		->with('invite', 'li');
	}

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		if ($this->regist['type'] != 2) {
			return redirect('my/invite/inviteFriend'));
//			return $this->showError('USER:invite.close');
		}
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();
		
		$startTime = Tool::str2time(Tool::time2str(Tool::getTime(), 'Y-m-d'));
		$readyBuy = $this->_getDs()->countByUidAndTime($this->loginUser->uid, $startTime);
		$gidLimit = abs(ceil($this->loginUser->getPermission('invite_limit_24h')));
		$price = abs(ceil($this->loginUser->getPermission('invite_buy_credit_num')));
		
		$_tmpId = $this->regist['invite.credit.type'];
		$_credit = array('id' => $_tmpId, 'name' => $pwCreditBo->cType[$_tmpId], 'unit' => $pwCreditBo->cUnit[$_tmpId]);
		->with($_credit, 'creditWithBuy');//用于购买的积分信息
		
		$_tmpId = $this->regist['invite.reward.credit.type'];
		$_credit = array('id' => $_tmpId, 'name' => $pwCreditBo->cType[$_tmpId], 'unit' => $pwCreditBo->cUnit[$_tmpId]);
		->with($_credit, 'rewardCredit');//奖励的积分信息
		
		->with($readyBuy > $gidLimit ? 0 : ($gidLimit - $readyBuy), 'canBuyNum');//还能购买的邀请数
		->with($price, 'pricePerCode');//每个邀请码需要积分的单价
		->with($this->loginUser->info['credit' . $this->regist['invite.credit.type']], 'myCredit');//我拥有的积分
		->with($this->regist['invite.reward.credit.num'], 'rewardNum');//奖励积分数
		->with($this->regist['invite.expired'], 'codeExpired');//邀请码有效期
		->with($this->loginUser->getPermission('invite_allow_buy'), 'canInvite');//该用户组是否可以购买邀请码
		->with($this->regist['invite.pay.money'], 'money');
		->with(/*$this->regist['invite.pay.open']*/ false, 'canBuyWithMoney');
		
		$this->listCode();
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.invite.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 购买邀请码
	 */
	public function buyAction(Request $request) {
		if (!$this->loginUser->getPermission('invite_allow_buy')) return $this->showError('USER:invite.buy.forbidden');
		$num = $request->get('num', 'post');
		/* @var $service PwInviteCodeService */
		$service = app('invite.srv.PwInviteCodeService');
		$result = $service->buyInviteCodes($this->loginUser, $num, $this->regist['invite.credit.type']);
		if ($result instanceof ErrorBag) return $this->showError($result->getError());
		return $this->showMessage('USER:invite.buy.success');
	}
	
	/**
	 * 在线购买
	 */
	public function onlineAction(Request $request) {
		
	}
	
	/**
	 * 判断是否可以购买如此数量的邀请码
	 */
	public function allowBuyAction(Request $request) {
		if (!$this->loginUser->getPermission('invite_allow_buy')) return $this->showError('USER:invite.buy.forbidden');
		$num = $request->get('num', 'post');
		/* @var $service PwInviteCodeService */
		$service = app('invite.srv.PwInviteCodeService');
		$result = $service->allowBuyInviteCode($this->loginUser, $num, $this->regist['invite.credit.type']);
		if ($result instanceof ErrorBag) return $this->showError($result->getError());
		return $this->showMessage();
	}
	
	/**
	 * 邀请统计页面
	 */
	public function statisticsAction(Request $request) {
		$page = intval($request->get('page'));
		$perpage = 18;
		$page || $page = 1;
		$count = $this->_getDs()->countUsedCodeByCreatedUid($this->loginUser->uid);
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count / $perpage);
			$page > $totalPage && $page = $totalPage;
			list($start, $limit) = Tool::page2limit($page, $perpage);
			$list = $this->_getDs()->getUsedCodeByCreatedUid($this->loginUser->uid, $limit, $start);
			$invitedUids = array_keys($list);
			/* @var $userDs PwUser */
			$userDs = app('user.PwUser');
			$list = $userDs->fetchUserByUid($invitedUids);
		}
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();
		
		$_tmpid = $this->regist['invite.reward.credit.type'];
		$_credit = array('id' => $_tmpid, 'name' => $pwCreditBo->cType[$_tmpid], 'unit' => $pwCreditBo->cUnit[$_tmpid]);
		->with($_credit, 'rewardCredit');//奖励的积分信息
		
		->with($this->regist['invite.reward.credit.num'], 'rewardNum');//奖励积分数
		->with($list, 'list');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
	}
	
	/**
	 * 邀请链接
	 */
	public function inviteFriendAction(Request $request) {
		if ($this->regist['type'] == 2) {
			return redirect('my/invite/run');
		}
		
		/* @var $pwInviteUrlLogSrv PwInviteFriendService */
		$pwInviteUrlLogSrv = app('invite.srv.PwInviteFriendService');
		$invite = $pwInviteUrlLogSrv->createInviteCode($this->loginUser->uid);
		->with(url('u/register/run', array('invite' => $invite)), 'url');
		return view('invite_friend');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:bbs.invite.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 列出用户拥有的邀请码
	 */
	private function listCode() {
		$perpage = 20;
		list($type, $page) = $request->get(array('type', 'page'), 'get');
		$vo = new PwInviteCodeSo();
		$vo->setCreatedUid($this->loginUser->uid)
			->setIfused(0)//未使用
			->setExpireTime(Tool::getTime() - ($this->regist['invite.expired'] * 86400));//未过期
		$count = $this->_getDs()->countSearchCode($vo);
		$list = array();
		if ($count) {
			$totalPage = ceil($count/$perpage);
			$page = intval($page);
			$page = $page < 1 ? 1 : ($page > $totalPage ? $totalPage : $page);
			list($start, $limit) = Tool::page2limit($page, $perpage);
			
			/* @var $service PwInviteCodeService */
			$service = app('invite.srv.PwInviteCodeService');
			$list = $service->searchInvitecodeList($vo, $limit, $start);
		}
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($list, 'list');
		->with($page, 'page');
		->with($type, 'type');
	}
	
	/**
	 * 获得邀请码DS
	 *
	 * @return PwInviteCode
	 */
	private function _getDs() {
		return app('invite.PwInviteCode');
	}
}