<div class="menubar">
	<ul>
<!--# $manageLeft = Wind::getApp()->getResponse()->getData('G','manageLeft'); 
	${$manageLeft.'_current'} = 'current';
#-->

@if (Core::getLoginUser()->getPermission('panel_bbs_manage'))

		<li class="{{ $content_current }}"><a href="{{ url('manage/content/run') }}">帖子审核</a></li>
<!--#}if (Core::getLoginUser()->getPermission('panel_user_manage')) {#-->
		<li class="{{ $user_current }}"><a href="{{ url('manage/user/run') }}">用户管理</a></li>
<!--#} if (Core::getLoginUser()->getPermission('panel_report_manage')) {#-->
		<li class="{{ $report_current }}"><a href="{{ url('manage/report/run') }}">举报管理</a></li>
<!--#} if (Core::getLoginUser()->getPermission('panel_recycle_manage')) {#-->
		<li class="{{ $recycle_current }}"><a href="{{ url('manage/recycle/run') }}">回收站</a></li>
<!--#} if (Core::getLoginUser()->getPermission('panel_log_manage')) {#-->
		<li class="{{ $manageLog_current }}"><a href="{{ url('manage/manageLog/run') }}">管理日志</a></li>
<!--#}#-->
	</ul>
</div>