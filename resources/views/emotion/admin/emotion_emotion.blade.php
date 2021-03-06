<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
<!--=====================表情组管理=========================-->
	<div class="nav">
		<div class="return"><a href="{{ url('emotion/emotion/run') }}">返回上一级</a></div>
		<ul class="cc">
			<li class="current"><a href="{{ url('emotion/emotion/emotion?catid=' . $catid) }}">表情管理</a></li>
		</ul>
	</div>
	<form class="J_ajaxForm" action="{{ url('emotion/emotion/dobatchedit') }}" method="post">
	<div class="J_check_wrap">
		<div class="table_list mb10">
			<div class="h_a">分类表情管理</div>
			<table width="100%">
				<colgroup>
					<col width="65" />
					<col width="80" />
					<col width="220" />
					<col width="80" />
					<col width="120" />
					<col width="80" />
				</colgroup>
				<tr>
					<td><label><input type="checkbox" data-direction="x" data-checklist="J_check_x1" class="J_check_all">启用</label></td>
					<td>顺序</td>
					<td>表情名称</td>
					<td>表情代码</td>
					<td>文件名</td>
					<td>预览</td>
					<td>操作</td>
				</tr>

@foreach ($emotionList as $key=>$emotion)

				<tr>
					<td><input type="checkbox" name="isused[]" {{ App\Core\Tool::ifcheck($emotion['isused']) }} value="1" class="J_check" data-yid="J_check_y1" data-xid="J_check_x1" />
						<input type="hidden" name="emotionid[]" value="{{ $emotion['emotion_id'] }}"/>
					</td>
					<td><input type="number" class="input length_1" name="orderid[]" value="{{ $emotion['vieworder'] }}"/></td>
					<td><input type="text" class="input length_3" name="emotionname[]" value="{{ $emotion['emotion_name'] }}"/></td>
					<td>{{ $emotion['sign'] }}</td>
					<td>{{ $emotion['emotion_icon'] }}</td>
					<td><img src="{{ $iconUrl }}/{$folder}/{{ $emotion['emotion_icon'] }}" /></td>

@if($emotion['isused'])

					<td><a href="{{ url('emotion/emotion/doused') }}" class="J_ajax_refresh" data-pdata="{'emotionid': {{ $emotion['emotion_id'] }}}">[关闭]</a></td>

@else

					<td><a href="{{ url('emotion/emotion/doused') }}" class="J_ajax_refresh" style="color:#ff0000" data-pdata="{'emotionid': {{ $emotion['emotion_id'] }}, 'used':'1'}">[开启]</a></td>
					<!--# } #-->
				</tr>
			<!--# } #-->
			</table>
		</div>
		<div class="btn_wrap_pd">
			<label class="mr20"><input type="checkbox" data-direction="y" data-checklist="J_check_y1" class="J_check_all">全选</label><button class="btn btn_submit J_ajax_submit_btn" type="submit" data-subcheck="true">提交</button>
		</div>
	</div>
	</form>
	<form class="J_ajaxForm" action="{{ url('emotion/emotion/dobatchadd') }}" method="post">
	<div class="J_check_wrap">
		<div class="table_list mb10">
			<div class="h_a">未添加的表情</div>
			<table width="100%">
				<colgroup>
					<col width="65" />
					<col width="80" />
					<col width="220" />
					<col width="80" />
					<col width="120" />
					<col width="80" />
				</colgroup>
				<tr>
					<td><label><input type="checkbox" data-direction="x" data-checklist="J_check_x2" class="J_check_all">全选</label></td>
					<td>顺序</td>
					<td>表情名称</td>
					<td>&nbsp;</td>
					<td>文件名</td>
					<td>预览</td>
					<td>操作</td>
				</tr>

@foreach ($folderEmotion as $key=>$icon)

				<tr>
					<td><input type="checkbox" name="emotionid[]" value="{{ $key }}" class="J_check J_emotionid" data-yid="J_check_y2" data-xid="J_check_x2" data-tid="116" /></td>
					<td><input type="number" class="input length_1 J_orderid" name="orderid[]" /></td>
					<td><input type="text" class="input length_3 J_emotionname" name="emotionname[] "/></td>
					<td>&nbsp;</td>
					<td>{{ $icon }}<input type="hidden" value="{{ $icon }}" name="icon[]" class="J_icon" /></td>
					<td><img src="{{ $iconUrl }}/{$folder}/{{ $icon }}" /></td>
					<td><a href="{{ url('emotion/emotion/dobatchadd') }}" class="J_emotion_add">[添加]</a></td>
				</tr>
				<!--# } #-->
			</table>
		</div>
		<div class="btn_wrap_pd">
			<label class="mr20">
			<input type="checkbox" data-direction="y" data-checklist="J_check_y2" class="J_check_all">全选</label>
			<button class="btn btn_submit J_ajax_submit_btn" type="submit">添加</button>
			<input type="hidden" value="{{ $catid }}" name="catid"/>
		</div>
	</div>
	</form>
		
<!--=====================结束=========================-->
	
	

</div>
@include('admin.common.footer')
<script>
var VAR_CATID = '{{ $catid }}';
$(function(){
	//添加
	$('a.J_emotion_add').on('click', function(e){
		e.preventDefault();
		var tr = $(this).parents('tr');
		$.post($(this).attr('href'), {
			catid : VAR_CATID,
			emotionid : [tr.find('input.J_emotionid').val()],
			orderid : [tr.find('input.J_orderid').val()],
			emotionname : [tr.find('input.J_emotionname').val()],
			icon : [tr.find('input.J_icon').val()],
		}, function(data){
			if(data.state === 'success') {
				reloadPage(window);
			}else if( data.state === 'fail' ) {
				Wind.dialog.alert(data.message);
			}
		}, 'json');
	});
});
</script>
</body>
</html>
