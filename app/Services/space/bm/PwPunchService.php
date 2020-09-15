<?php

 /**
  * 获取打卡显示状态
  *
  * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
  * @copyright ©2003-2103 phpwind.com
  * @license http://www.phpwind.com
  * @version $Id$
  * @package wind
  */
 class PwPunchService {
	
	/**
	 * 获取首页打卡状态
	 * 
	 * @param PwUserBo $user
	 * return array
	 */
	public function getPunch($user = null) {
		!$user && $user = Core::getLoginUser();
		$punchData = unserialize($user->info['punch']);
		$havePunch = $this->isPunch($punchData);
		if (!$havePunch) {
			$unPunchDays = $punchData['time'] > 0 ? ceil((Tool::str2time(Tool::time2str(Tool::getTime(),'Y-m-d')) - Tool::str2time(Tool::time2str($punchData['time'],'Y-m-d'))) / 86400) : 1;
			$punchText =  $unPunchDays > 1 ? "{$unPunchDays}天未打卡" : "每日打卡";
			return array(true,$punchText,array());
		}
		$behaviorDays = $this->_getBehavior($punchData['time'],$punchData['days']);
		if($punchData['username'] == $user->username && $havePunch){
			$behaviorDays or $behaviorDays = 1; 
			$punchText = "连续{$behaviorDays}天打卡";
			return array(false,$punchText,array());
		}
		return array(true,'继续打卡',$punchData);
	}
	
	/**
	 * 获取个人空间打卡状态
	 * 
	 * @param PwUserBo $user
	 * return array
	 */
	public function getSpacePunch(PwSpaceBo $space) {
		switch ($space->tome) {
			case PwSpaceBo::VISITOR:
				return array(false,'',array());
			case PwSpaceBo::STRANGER:
				return array(false,'',array());
			case PwSpaceBo::MYSELF:
				return $this->getPunch();
			case PwSpaceBo::ATTENTION:
				$spaceUser = $space->spaceUser;
				$punchData = unserialize($spaceUser['punch']);	
				$havePunch = $this->isPunch($punchData);
				if (!$havePunch) {
					return array(true,'帮Ta打卡',array());
				}
				if ($punchData['username'] != $spaceUser['username']) {	
					$data = unserialize($spaceUser['punch']);
					return array(false,'帮Ta打卡',$data);
				}
				return array(false,'帮Ta打卡',array());
		}
	}
	
	/**
	 * 是否已经打卡
	 * 
	 * @param array $punchData
	 * return bool
	 */
	public function isPunch($punchData) {
		$todayStart = Tool::str2time(Tool::time2str(Tool::getTime(),'Y-m-d'));
		$todayEnd = $todayStart + 86400;
		return $punchData['time'] > $todayStart && $punchData['time'] < $todayEnd ? true : false;
	}
	
	/**
	 * 获取打卡配置返回打卡和帮朋友打卡是否开启
	 * 
	 * @param array $punchData
	 * return bool
	 */
	public function getPunchConfig() {
		$config = Core::C('site');
		$punchOpen = $config['punch.open'] ? true : false;
		$punchFriendOpen = $config['punch.friend.open'] ? true : false;
		return array($punchOpen,$punchFriendOpen);
	}
	
	/**
	 * 格式化时间
	 * 
	 * @param int $timestamp
	 * return bool
	 */
	public function formatWeekDay($timestamp) {
		$weeksArray = array('周日','周一','周二','周三','周四','周五','周六');
		$weekDay = Tool::time2str($timestamp, 'w');
		return array(Tool::time2str($timestamp, 'm.d'),$weeksArray[$weekDay]);
	}
 	
 	private function _getBehavior($time,$number) {
 		$time = $time + 86400*2;
 		$time = Tool::str2time(Tool::time2str($time, 'Y-m-d'));
 		
		if($time > 0 && $time < Tool::getTime()) $number = 0;
		return $number;
	}
}
?>