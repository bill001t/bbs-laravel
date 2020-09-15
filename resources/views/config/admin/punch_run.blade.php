<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

	<div class="h_a">打卡设置</div>
	<form class="J_ajaxForm" action="{{ url('admin/config/punch/dorun') }}" method="post">
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>每日打卡</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="punchOpen" value="1" {{ App\Core\Tool::ifcheck($config['punch.open'] == 1) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="punchOpen" value="0" {{ App\Core\Tool::ifcheck($config['punch.open'] == 0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>奖励积分类型</th>
				<td><select class="select_4" name="punchReward[type]">

@foreach ($creditBo->cType as $key => $value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $config['punch.reward']['type']) }}>{{ $value }}</option>
				<!--# } #-->
				</select></td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>奖励积分数</th>
				<td><input type="number" class="input length_1 mr5" name="punchReward[min]" value="{{ $config['punch.reward']['min'] }}"><span class="mr5">至</span><input type="number" class="input length_1 mr20"  name="punchReward[max]" value="{{ $config['punch.reward']['max'] }}"><span class="mr5">递增</span><input type="number" class="input length_1" name="punchReward[step]" value="{{ $config['punch.reward']['step'] }}"></td>
				<td><div class="fun_tips">递增即连续打卡后递增的积分数，递增后的数值不会超过积分范围最大值。</div></td>
			</tr>
			<tr>
				<th>帮Ta打卡</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="punchFrendOpen" value="1" {{ App\Core\Tool::ifcheck($config['punch.friend.open'] == 1) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="punchFrendOpen" value="0" {{ App\Core\Tool::ifcheck($config['punch.friend.open'] == 0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td><div class="fun_tips">开启后可以帮好友打卡，并获的积分奖励。</div></td>
			</tr>
			<tr>
				<th>帮打卡好友人数上限</th>
				<td>
					<input type="number" class="input length_4" name="punchFrendReward[friendNum]" value="{{ $config['punch.friend.reward']['friendNum'] }}">
				</td>
				<td><div class="fun_tips">每天最多可帮多少个好友打卡。</div></td>
			</tr>
			<tr>
				<th>被帮者领取积分数</th>
				<td>
					<input type="number" class="input length_4" name="punchFrendReward[rewardMeNum]" value="{{ $config['punch.friend.reward']['rewardMeNum'] }}">
				</td>
				<td><div class="fun_tips">帮好友领取的积分数，剩余积分需要好友自己打卡领取。</div></td>
			</tr>
			<tr>
				<th>帮打卡奖励积分数</th>
				<td>
					<input type="number" class="input length_4" name="punchFrendReward[rewardNum]" value="{{ $config['punch.friend.reward']['rewardNum'] }}">
				</td>
				<td><div class="fun_tips">每次帮好友打卡后，自己获得的积分奖励，不得高于打卡奖励积分</div></td>
			</tr>
		</table>
	</div>
	
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
		<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')
</body>
</html>