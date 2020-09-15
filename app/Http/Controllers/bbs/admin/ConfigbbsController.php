<?php

Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台菜单管理操作类
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-21
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ConfigbbsController.php 28788 2013-05-23 10:08:37Z jieyin $
 * @package admin
 * @subpackage controller
 */
class ConfigbbsController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		
		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
		
		$config = Core::C()->getValues('bbs');
		->with($config, 'config');
// 		->with(Core::C('bbs'), 'config');
	}
	
	/**
	 * 设置论坛设置
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($bbsname, $title_length_max, $content_length_min, $content_length_max/*, $ifopen*/, $check) = $request->get(array('bbsname', 'title_length_max', 'content_length_min', 'content_length_max'/*, 'ifopen'*/, 'check'));
		$config = new PwConfigSet('bbs');
		$config->set('bbsname', $bbsname)
			->set('title.length.max', abs(intval($title_length_max)))
			->set('content.length.min', abs(intval($content_length_min)))
			->set('content.length.max', abs(intval($content_length_max)))
			//->set('post.timing.open', intval($ifopen))
			->set('post.check.open', intval($check));
		
		/*
		list($start_hour, $start_min, $end_hour, $end_min) = $request->get(array('start_hour', 'start_min', 'end_hour', 'end_min'), 'post');
		$start_hour = intval($start_hour);
		$start_min = intval($start_min);
		$end_hour = intval($end_hour);
		$end_min = intval($end_min);
		$config->set('post.timing.start_hour', max(0, min(24, $start_hour)))
			->set('post.timing.start_min', max(0, min(60, $start_min)))
			->set('post.timing.end_hour', max(0, min(24, $end_hour)))
			->set('post.timing.end_min', max(0, min(60, $end_min)))
			->set('post.timing.groups', $request->get('timing_groups', 'post'));
		*/

		list($check_start_hour, $check_start_min, $check_end_hour, $check_end_min) = $request->get(array('check_start_hour', 'check_start_min', 'check_end_hour', 'check_end_min'), 'post');
		$check_start_hour = intval($check_start_hour);
		$check_start_min = intval($check_start_min);
		$check_end_hour = intval($check_end_hour);
		$check_end_min = intval($check_end_min);

		$config->set('post.check.start_hour', max(0, min(24, $check_start_hour)))
			->set('post.check.start_min', max(0, min(24, $check_start_min)))
			->set('post.check.end_hour', max(0, min(24, $check_end_hour)))
			->set('post.check.end_min', max(0, min(24, $check_end_min)))
			->set('post.check.groups', $request->get('check_groups', 'post'));

	    $config->flush();
		return $this->showMessage('config.setting.success');
	}
}
?>