
@if ($sameUser['sameUser'])

	<div class="menu_arrow"><em></em><span></span></div>
	<!--# 
		$tmp = array();
		foreach($sameUser['sameUser'] as $sk => $su){
			$tmp[] = sprintf('<a href="%s" class="J_user_card_show" data-uid="%d" target="_blank">%s</a>',WindUrlHelper::createUrl('space/index/run?uid=' . $sk),$sk,$su);
		}
		$tmp = implode('、', $tmp);
	 #-->
	您关注的人中： {!! $tmp !!}
@if($v['cnt'] > 3)
等{$v['cnt']}人<!--# } #--> 也关注了Ta
<!--# } #-->
