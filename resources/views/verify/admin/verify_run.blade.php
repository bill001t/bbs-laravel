<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<!-- start -->
<div class="nav">
	<ul class="cc">
		<li class="current"><a href="{{ url('/verify/verify/run') }}">验证码</a></li>
		<li><a href="{{ url('/verify/verify/set') }}">验证策略</a></li>
	</ul>
</div>
<div class="h_a">提示信息</div>
<div class="prompt_text mb10">
	验证机制统一设置验证码规则，对应模块需要开启验证码功能需在验证策略中启用。
</div>
<form method="post" class="J_ajaxForm" action="{{ url('/verify/verify/dorun') }}" data-role="list">
	<div class="h_a">验证码设置</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>验证码类型</th>
				<td>
					<ul class="single_list cc">

@foreach($verifyType AS $k=>$v)
$msg .= $v['description'] . '<br/>';
						#-->
						<li><label><input name="type"  value="{{ $k }}" type="radio" {{ App\Core\Tool::ifcheck($config['type']==$k) }}><span>{{ $v['name'] }}</span></label></li>
						<!--# } #-->
					</ul>
				</td>
				<td class="td_tips">
				<div> {!! $msg !!}
				</div></td>
			</tr>
			<tr>
				<th>识别难度</th>
				<td>
					<ul class="three_list cc">
						<li><label><input name="randtype[]" type="checkbox" value="font" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('font',$config['randtype'])) }}><span>随机字体</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="size" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('size',$config['randtype'])) }}><span>随机字体大小</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="angle" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('angle',$config['randtype'])) }}><span>随机倾斜</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="background" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('background',$config['randtype'])) }}><span>随机背景</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="color" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('color',$config['randtype'])) }}><span>随机颜色</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="graph" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('graph',$config['randtype'])) }}><span>随机干扰图形</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="distortion" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('distortion',$config['randtype'])) }}><span>随机扭曲</span></label></li>
						<li><label><input name="randtype[]" type="checkbox" value="gif" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('gif',$config['randtype'])) }}><span>gif动画</span></label></li>
					</ul>
				</td>
				<td class="td_tips"><div>由于部分服务器对字体和gif功能支持不友好，如果您的站点开启验证码后出现服务器不稳定现象请关闭“随机字体”、“gif动画”</div></td>
			</tr>
			<tr>
				<th class="th" rowspan="2">验证码内容</th>
				<td class="td">
					<ul class="single_list cc mb20">
						<li><label><input name="contentType[]" type="checkbox" value="3" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('3',$config['content.type'])) }}><span>英文字符和数字</span></label></li>
						<li><label><input name="contentType[]" type="checkbox" value="5" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('5',$config['content.type'])) }}><span>中文字符</span></label></li>
					</ul>
					<div class="b mb5">验证码个数（建议设置为4个）</div>
					<input name="contentLength" type="number" class="input" value="{{ $config['content.length'] }}">
				</td>
				<td class="td_tips"><div>四种验证码内容供选择，多选情况下随机出现其中一种<br><span class="red">注：启用中文验证码，先要在src/repository/font目录中添加中文字体，文件名以cn_开头</span></div></td>
			</tr>
			<tr>
				<td class="td">
					<ul class="single_list cc mb20">
						<li><label><input name="contentType[]" type="checkbox" value="4" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('4',$config['content.type'])) }}><span>100以内随机加减法</span></label></li>
						<li><label><input name="contentType[]" type="checkbox" value="6" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('6',$config['content.type'])) }}><span>自定义验证问题</span></label></li>
					</ul>
					<div class="b mb5">验证问题与答案</div>
					<div class="cross">
						<ul id="J_ul_list_verify" class="J_ul_list_public">

@foreach($config['content.questions'] as $key => $value)

							<li>
								<span class="span_3"><input name="contentQuestions[{{ $key }}][ask]" type="text" class="input length_2" value="{{ $value['ask'] }}"></span>
								<span class="span_4"><input name="contentQuestions[{{ $key }}][answer]" type="text" class="input  length_2 mr15" value="{{ $value['answer'] }}"><a href="" class="J_ul_list_remove">[删除]</a></span></li>
<!--# } #-->
						</ul>
					</div>
					<div class="group_list">
						<ul >
						
						</ul>
					</div>
					<div class="mb20"><a href="#" class="link_add J_ul_list_add" data-related="verify">添加验证问题</a></div>
					<div class="b mb5">显示验证问题答案</div>
					<ul class="switch_list cc">
						<li><label><input name="contentShowanswer"  value="1" type="radio"  {{ App\Core\Tool::ifcheck($config['content.showanswer']==1) }}><span>开启</span></label></li>
						<li><label><input name="contentShowanswer"  value="0" type="radio"  {{ App\Core\Tool::ifcheck($config['content.showanswer']==0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td class="td_tips">
				<div style="margin-top:20px;">
					<p>自定义验证问题，系统将随机显示其一</p>
					<p>注：如果验证问题和答案里有中文，先要在src/repository/font目录中添加中文字体，文件名以cn_开头</p>
				</div></td>
			</tr>
			<tr>
				<th>语音验证码</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input name="voice"  value="1" type="radio" {{ App\Core\Tool::ifcheck($config['voice']==1) }}><span>开启</span></label></li>
						<li><label><input name="voice"  value="0" type="radio" {{ App\Core\Tool::ifcheck($config['voice']==0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td class="td_tips"><div>语音文件存放在src/repository/audio下,可以用自定义的语音文件替换。注:格式和命名需相同</div></td>
			</tr>
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
</form>
<!-- end -->

</div>
@include('admin.common.footer')
<script>
//添加验证问题html
var _li_html = '<li>\
					<span class="span_3"><input type="text" value="" class="input length_2" name="contentQuestions[new_][ask]"></span>\
					<span class="span_4">\
						<input type="text" value="" class="input length_2 mr15" name="contentQuestions[new_][answer]"><a class="J_ul_list_remove" href="#">[删除]</a>\
					</span>\
				</li>';
</script>
</body>
</html>