<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<div class="nav">
	<div class="return"><a href="{{ url('admin/u/groups/run?type=' . $group['type']) }}">返回上一级</a></div>
	<ul class="cc J_tabs_nav">
		<li class="current">
			<a href="{{ url('admin/u/groups/setright?rkey=' . $rkey) }}">权限设置</a>
		</li>
	</ul>
</div>

<div class="tips_bubble" style="display:none;right:18px;margin-top:50px;" id="J_tips_bubble">
	<div class="core_arrow_bottom"><em></em><span></span></div>
	<p class="mb5">在这里勾选设置项，完成复制设置</p>
	<p class="tar"><a href="" id="J_tips_bubble_close">我知道了</a></p>
</div>

<form class="J_ajaxForm" action="{{ url('u/groups/dosetright') }}" method="post">
<input type="hidden" name="rkey" value="{{ $rkey }}" />
<div class="h_a">权限设置</div>
<div class="table_full">
	<table width="100%">
		<col class="th" />
		<col width="400" />
		<col />
		<tr>
			<th>权限点</th>
			<td>{{ $permission[2] }}</td>
			<td class="td_tips">{!! $permission[3] !!}</td>
		</tr>
	</table>
</div>

<div>

@foreach ($typeName as $k => $v)


@if (isset($permissionConfigs[$k]))

	<div class="h_a">{{ $v }}</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<col width="44" />

@foreach ($permissionConfigs[$k] as $k2 => $v2)

			<tr><!--  选中后效果 class="tr_checkbox" -->
				<th><a href="{{ url('u/groups/edit?gid=' . $k2 . '&manage=' . $manage) }}">{{ $v2['name'] }}</a></th>
				<td>

@if ($permission[0] == 'html')

					{{-- <segment tpl='permission_html_segments' alias='permission_html_segments' args='$k2,$v2' name="$rkey" /> --}}

@elseif ($permission[0] == 'app')

					{{-- <segment tpl="{{ $v2['config'][5] }}" alias='permission_html_segments' args='$k2,$v2' name="$rkey" /> --}}

@else

					{{-- <segment tpl='permission_type_segments' alias='permission_html_segments' args='$k2,$v2' name="$permission[0]" /> --}}
				<!--# } #-->
				</td>
				<td style="vertical-align:middle"></td>
			</tr>
			<!--# } #-->
		</table>
	</div>
	<!--# } #-->
	<!--# } #-->
</div>

<div class="btn_wrap">
   <div class="btn_wrap_pd">
      <button type="submit" class="btn btn_submit mr20 J_ajax_submit_btn">提交</button>
   </div>
</div>

</form>

</div>
@include('admin.common.footer')
<script>

</script>
</body>
</html>