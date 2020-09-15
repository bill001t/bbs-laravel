<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	
<!-- start -->
@include('nav_tab')


@if ($navType == 'main')

	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>“设为首页” 可以把当前页设为网站默认首页。</li>
		</ul>
	</div>
	<!--# } #-->

@if ($navType == 'my')

	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>如果应用设置了开关功能， 设置相同的“应用标识”可以同时开启和关闭。</li>
		</ul>
	</div>
	<!--# } #-->

<!--div class="mb10">
	<a href="{{ url('/nav/nav/add') }}&type={{ $navType }}" class="btn J_dialog"><span class="add"></span>添加导航</a>
</div-->
<form method="post" class="J_ajaxForm" action="{{ url('/nav/nav/dorun') }}" data-role="list">

<div class="table_list">
	<table width="100%" id="J_table_list" style="table-layout:fixed;">
		<colgroup>
			<col width="30">
			<col width="380">
			<col width="260">
			<col width="68">
			<col>
		</colgroup>
		<thead>
			<tr>
				<td></td>
				<td>[顺序] 导航名称</td>
				<td>链接地址</td>
				<td>

@if ($navType == 'main')

				设为首页
				<!--# } #-->

@if ($navType == 'my')

				应用标识
				<!--# } #-->
				</td>
				<td class="tac">启用</td>
				<td>操作</td>
			</tr>
		</thead>

@foreach ($navList as $value)
$count=count($value['child']);
		$icon='zero_icon';
		if($count>0){
			$icon='J_start_icon away_icon';
		}
	#-->
		<tbody>
		<tr>
			<td><span class="{{ $icon }}" data-id="{{ $value['navid'] }}"></span></td>
			<td>
				<input name="data[{{ $value['navid'] }}][navid]" type="hidden" value="{{ $value['navid'] }}" >
				<input name="data[{{ $value['navid'] }}][orderid]" type="text" class="input length_0 mr10" value="{{ $value['orderid'] }}">
				<input name="data[{{ $value['navid'] }}][name]" type="text" class="input length_3 mr5" value="{{ $value['name'] }}">

@if ($navType == 'main')

				<a style="display:none" href="{{ url('nav/nav/add?parentid=' . $value['navid'] . '&type=' . $navType) }}" class="link_add J_addChild add_nav" data-id="{{ $value['navid'] }}" data-html="tbody" data-type="nav_2">添加二级导航</a>
				<!--# } #-->
			</td>
			<td><input name="data[{{ $value['navid'] }}][link]" type="text" class="input length_4" value="{{ $value['link'] }}"></td>
			<td>

@if ($navType == 'main')

				<input type="radio" name="home"  value="{{ $value['navid'] }}" {{ App\Core\Tool::ifcheck($homeUrl && $homeUrl == $value['link'] ) }}>
				<!--# } #-->

@if ($navType == 'my')

				<input type="text" name="data[{{ $value['navid'] }}][sign]"  value="{{ $value['sign'] }}" class="input length_2">
				<!--# } #-->
			</td>
			<td class="tac"><input name="data[{{ $value['navid'] }}][isshow]" type="checkbox" value="1" {{ App\Core\Tool::ifcheck($value['isshow']) }}></td>
			<td>
				<a href="{{ url('nav/nav/edit?navid=' . $value['navid'] . '&type=' . $value['type']) }}" class="mr10 J_dialog" title="导航编辑">[编辑]</a>
				<a href="{{ url('nav/nav/del') }}" class="mr10 J_ajax_del" data-pdata="{'navid': {{ $value['navid'] }}}">[删除]</a>
			</td>
		</tr>
		</tbody>

@if($count>0)

			<tbody id="J_table_list_{{ $value['navid'] }}">

@foreach ($value['child'] as  $childKey=>$childValue)
$checked=$childValue['isshow']?'checked':'';
			$endicon=($childKey==$count-1)?'  plus_end_icon':'';
		#-->
			<tr>
				<td>&nbsp;</td>
				<td><span class="plus_icon{$endicon} mr10"></span><input name="data[{{ $childValue['navid'] }}][navid]" type="hidden" value="{{ $childValue['navid'] }}" ><input name="data[{{ $childValue['navid'] }}][orderid]" type="text" class="input length_0 mr10" value="{{ $childValue['orderid'] }}" style="width:20px;"><input name="data[{{ $childValue['navid'] }}][name]" type="text" class="input length_3 mr5" value="{{ $childValue['name'] }}"><!--<a href="{{ $addUrl }}&type={$navType}&parentid={{ $value['navid'] }}" style="display:none" class="s2 dialog">+添加导航</a>-->
					</td>
				<td>
					<input name="data[{{ $childValue['navid'] }}][link]" type="text" class="input length_4" value="{{ $childValue['link'] }}">
				</td>
				<td></td>
				<td class="tac"><input name="data[{{ $childValue['navid'] }}][isshow]" type="checkbox" value="1"{{ $checked }}></td>
				<td>
					<a href="{{ url('nav/nav/edit?navid=' . $childValue['navid'] . '&type=' . $childValue['type']) }}" class="mr10 J_dialog" title="导航编辑">[编辑]</a><a href="{{ url('nav/nav/del') }}" class="mr10 J_ajax_del" data-pdata="{'navid': {{ $childValue['navid'] }}}">[删除]</a>
				</td>
			</tr>
		<!--#
			}
		#-->
		</tbody>
		<!--#
			}
			}
		#-->
	</table>
	<table width="100%">
		<tr class="ct"><td colspan="5" style="padding-left:38px;"><a data-type="nav_1" data-html="tbody" href="" id="J_add_root" class="link_add">添加导航</a></td></tr>
	</table>
</div>
<div class="btn_wrap">
	<div class="btn_wrap_pd">
		<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		<input name="navtype" type="hidden" value="{{ $navType }}" >
	</div>
</div>	
</form>
<!-- end -->

</div>
@include('admin.common.footer')
<script>
/*
root_tr_html 为“添加导航”html
child_tr_html 为“添加二级导航”html
*/
var root_tr_html = '<tr>\
                            <td><span class="zero_icon mr10"></span></td>\
                                        <td>\
                                            <input name="newdata[root_][orderid]" type="text" value="" class="input length_0 mr10">\
                                            <input name="newdata[root_][name]" type="text" class="input length_3 mr5" value="">\

@if ($navType == 'main')

											<a style="display: none; " href="#" class="link_add J_addChild add_nav" data-html="tbody" data-id="temp_root_" data-type="nav_2">添加二级导航</a>\
											<!--# } #-->
                                            <input type="hidden" name="newdata[root_][tempid]" value="temp_root_"/>\
                                        </td>\
                                        <td>\
                                            <input name="newdata[root_][link]" type="text" class="input length_4" value="">\
                                        </td>\
																				<td>\

@if ($navType == 'main')

										<input type="radio" name="home" value="home_root_" ></td>\
										<!--# } #-->

@if ($navType == 'my')

										<input type="text" name="newdata[root_][sign]" class="input length_2" value="" >\
										<!--# } #--></td>\
                                        <td class="tac"><input name="newdata[root_][isshow]" type="checkbox" value="1" checked="checked"></td>\
                                        <td>\
                                            <a href="" class="mr5 J_newRow_del">[删除]</a>\
                                        </td>\
                                    </tr>',
	child_tr_html = '<tr>\
						<td></td>\
						<td><span class="plus_icon"></span>\
							<input name="newdata[child_][orderid]" type="text" value="" class="input length_0 mr10">\
                                            <input name="newdata[child_][name]" type="text" class="input length_3 mr5" value="">\
                                        </td>\
                                        <td>\
                                            <input name="newdata[child_][link]" type="text" class="input length_4" value="">\
                                        </td>\
																				<td></td>\
                                        <td class="tac"><input name="newdata[child_][isshow]" type="checkbox" value="1" checked="checked"></td>\
                                        <td>\
                                            <a href="" class="mr5 J_newRow_del">[删除]</a>\
                                            <input type="hidden" name="newdata[child_][parentid]" value="id_"/>\
                                        </td>\
                                    </tr>';

Wind.js(GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' +GV.JS_VERSION);
</script>
</body>
</html>