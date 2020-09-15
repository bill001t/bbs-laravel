<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 任务奖励扩展-设置端
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: TaskRewardController.php 15745 2012-08-13 02:45:07Z xiaoxia.xuxx $
 * @package src.modules.task.admin
 */
class TaskRewardController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see AdminBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$var = unserialize($request->get('var'));
		if (is_array($var)) {
			->with($var, 'reward');
		}
	}
	
	/* (non-PHPdoc)
	 * 任务奖励扩展-积分扩展
	 * @see WindController::run()
	 */
	public function run() {
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();
		
		->with($pwCreditBo, 'credit');
		return view('reward.reward_credit');
	}
	
	/**
	 * 任务奖励扩展-用户组扩展
	 */
	public function groupAction(Request $request) {
		/* @var $userGroups PwUserGroups */
		$userGroups = app('usergroup.PwUserGroups');
		$groupList = $userGroups->getGroupsByType('special');
		->with($groupList, 'groups');
		return view('reward.reward_group');
	}
}