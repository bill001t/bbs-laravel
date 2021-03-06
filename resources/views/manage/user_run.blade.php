<div class="content">
	<div class="profile_nav">
		<ul>
			<li class="current"><a href="{{ url('manage/user/run') }}">注册审核</a></li>
			<li><a href="{{ url('manage/user/email') }}">邮箱审核</a></li>
		</ul>
	</div>
	<div class="profile_table">

@if ($list)

	<form action="{{ url('manage/user/docheck') }}" method="post" class="J_form_ajax">
		<table width="100%" class="J_check_wrap">
			<colgroup>
				<col width="65" />
				<col width="50" />
				<col width="100" />
				<col width="130" />
				<col width="160" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<td><label><input type="checkbox" class="J_check_all">全选</label></td>
					<td>UID</td>
					<td>用户名</td>
					<td>注册时间</td>
					<td>电子邮箱</td>
					<td>注册原因</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td><label><input type="checkbox" class="J_check_all">全选</label></td>
					<td colspan="3"><button type="submit" class="btn btn_submit J_form_sub_check">通过</button><button type="submit" class="btn J_form_sub_check" data-action="{{ url('manage/user/delete') }}">拒绝</button></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>

@foreach ($list as $key => $item)

			<tr>
				<td><input type="checkbox" class="J_check" name="uid[]" value="{{ $item['uid'] }}"></td>
				<td>{{ $item['uid'] }}</td>
				<td><a href="{{ url('space/index/run?uid=' . $item['uid']) }}">{{ $item['username'] }}</a></td>
				<td>{{ App\Core\Tool::time2str($item['regdate'], 'Y-m-d H:i:s') }}</td>
				<td>{{ $item['email'] }}</td>
				<td>{{ $item['regreason'] }}</td>
			</tr>
			<!--# } #-->
			<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='manage/user/run' args='$args'/>
		</table>
		</form>

@else

		<div class="not_content">啊哦，没有符合条件的用户！</div>
	<!--# } #-->
	</div>
</div>
