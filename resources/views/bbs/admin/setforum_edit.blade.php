<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
<div class="nav">
	<div class="return"><a href="{{ url('bbs/setforum/run') }}">返回上一级</a></div>
	<ul class="cc J_tabs_nav">
		<li id="J_nav_jb" data-tab="nav_jb" class="current"><a href="">基本设置</a></li>
		<li id="J_nav_gn" data-tab="nav_gn"><a href="">功能设置</a></li>

@if (!$isCate)

		<li id="J_nav_zt" data-tab="nav_zt"><a href="">主题分类</a></li>
		<li id="J_nav_qx" data-tab="nav_qx"><a href="">权限相关</a></li>
		<li id="J_nav_jf" data-tab="nav_jf"><a href="">积分相关</a></li>
		<!--# } #-->
	</ul>
</div>

<div class="tips_bubble" style="display:none;right:18px;margin-top:15px;" id="J_tips_bubble">
	<div class="core_arrow_bottom"><em></em><span></span></div>
	<p class="mb5">在这里勾选设置项，完成复制设置</p>
	<p class="tar"><a href="" id="J_tips_bubble_close">我知道了</a></p>
</div>

<form id="J_rigths_form" data-role="list" action="{{ url('bbs/setforum/doedit?_json=1') }}" method="post" enctype="multipart/form-data">
<input type="hidden" name="fid" value="{{ $fid }}">
<div class="h_a">版块设置</div>
<div class="table_full">
	<table width="100%">
		<col class="th" />
		<col width="400" />
		<col />
			<col width="44" />
		<tr>
			<th>版块名称</th>
			<td>
				<input type="text" class="input input_hd length_5" value="{{ $foruminfo['name'] }}" name="forumname">
			</td>
			<td><div class="fun_tips">支持HTML代码</div></td>
			<td style="vertical-align:middle"></td>
		</tr>
		<tr>
			<th>版主</th>
			<td>
				<input type="text" class="input length_5" value="{{ @trim($foruminfo['manager'], ',') }}" name="manager">
			</td>
			<td>&nbsp;</td>
			<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="manager"></td>
		</tr>
	</table>
</div>

<div class="J_tabs_contents">
	<!--=============基本设置===============-->
	<div>
		<div class="h_a">基本设置</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<col width="44" />
				<tr>
					<th>顺序</th>
					<td><input type="number" class="input length_5" value="{{ $foruminfo['vieworder'] }}" name="vieworder"></td>
					<td></td>
					<td style="vertical-align:middle"></td>
				</tr>

@if (!$isCate)

				<tr>
					<th>上级版块</th>
					<td>
						<select name="parentid" class="select_5"> {!! $option_html !!}
						</select>
					</td>
					<td></td>
					<td style="vertical-align:middle"></td>
				</tr>
				<!--# } #-->
				<tr>
					<th>版块简介</th>
					<td><textarea class="length_5" name="descrip">{{ $foruminfo['descrip'] }}</textarea></td>
					<td><div class="fun_tips">支持HTML代码</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" value="descrip" class="J_line_highlight" style="display:none;"></td>
				</tr>
				<tr>
					<th>版块风格</th>
					<td>
						<select name="style" class="select_5">
						<option {{ App\Core\Tool::isSelected($foruminfo['style'] == '') }} value="">默认风格</option>

@foreach($styles as $v)

							<option value="{{ $v['alias'] }}" {{ App\Core\Tool::isSelected($foruminfo['style'] == $v['alias']) }}>{{ $v['name'] }}</option>
						<!--# } #-->
						</select>
					</td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="style"></td>
				</tr>
				<tr>
					<th>子版块横排</th>
					<td><input type="number" class="input length_5" value="{{ $foruminfo['across'] }}" name="across"></td>
					<td><div class="fun_tips">设置后下级版块在版块帖子列表页显示时根据数值横向排列</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" value="across" class="J_line_highlight" style="display:none;"></td>
				</tr>
				<tr>
					<th>版块显示</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input type="radio" value="1" name="isshow"{{ App\Core\Tool::ifcheck($foruminfo['isshow']) }}><span>开启</span></label></li>
							<li><label><input type="radio" value="0" name="isshow"{{ App\Core\Tool::ifcheck(!$foruminfo['isshow']) }}><span>关闭</span></label></li>
						</ul>
					</td>
					<td><div class="fun_tips">关闭后版块不显示，但可以通过url直接访问</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="isshow"></td>
				</tr>
				<tr>
					<th>版块跳转链接</th>
					<td>
						<input type="text" class="input length_5" value="{{ $forumset['jumpurl'] }}" name="jumpurl" placeholder="http://">
					</td>
					<td><div class="fun_tips">请输入版块需要跳转的地址，支持站内站外，外部链接必须要添加http://</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="jumpurl"></td>
				</tr>
				<tr>
					<th>版块图标</th>
					<td>
						<div class="single_image_up">
							 <a href="">上传图片</a>
							 <input name="icon" type="file" class="J_upload_preview" data-preview="#j_forum_preview_icon">
						</div>

@if ($foruminfo['icon'])

						<img id="j_forum_preview_icon" src="{{ App\Core\Tool::getPath($foruminfo['icon']) }}" align="top" class="mr5"/>
						<a href="{{ url('bbs/setforum/deleteicon') }}" class="J_ajax_del" data-pdata="{'fid': '{{ $fid }}'}">删除</a>

@else

						<img id="j_forum_preview_icon" style="display:none"/>
						<!--# } #-->
						<!--ul class="switch_list cc">
							<li><label><input type="radio" value="1" name="forumicon">图片链接</label></li>
							<li><label><input type="radio" value="0" name="forumicon">本地上传</label></li>
						</ul>
						<input type="text" class="input" value="{{ $foruminfo['iconurl'] }}" name="iconurl"-->
					</td>
					<td><div class="fun_tips">显示在首页或者分类页，需要模板支持</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="icon"></td>
				</tr>
				<tr>
					<th>版块logo</th>
					<td>
						<div class="single_image_up">
							 <a href="">上传图片</a>
							 <input name="logo" type="file" class="J_upload_preview" data-preview="#j_forum_preview_logo">
						</div>

@if ($foruminfo['logo'])

						<img id="j_forum_preview_logo" src="{{ App\Core\Tool::getPath($foruminfo['logo']) }}" align="top" class="mr5" />
						<a href="{{ url('bbs/setforum/deletelogo') }}" class="J_ajax_del" data-pdata="{'fid': '{{ $fid }}'}">删除</a>

@else

						<img id="j_forum_preview_logo" style="display:none" />
						<!--# } #-->
						<!--ul class="switch_list cc">
							<li><label><input type="radio" value="1" name="forumlogo">图片链接</label></li>
							<li><label><input type="radio" value="0" name="forumlogo">本地上传</label></li>
						</ul>
						<input type="text" class="input" value="{{ $foruminfo['logourl'] }}" name="logourl"-->
						
					</td>
					<td><div class="fun_tips">在帖子列表页显示版块的logo，需要模板支持</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="logo"></td>
				</tr>
				<tr>
					<th>版块域名</th>

@if (isset($forumroot))

						<td>
						http://<input type="text" class="input length_1" value="{{ $forumdomain }}" name="forumdomain">.{$forumroot}
						<input type="hidden" name="forumroot" value="{{ $forumroot }}">
						<td><div class="fun_tips">在<a href="{{ url('rewrite/domain/run') }}">二级域名</a>设置中开启“子域名”后才生效，需要将此域名解析到根域名相同IP</div></td>

@else

						<td>
						<input type="text" class="input length_5"  value="{{ $forumdomain }}" name="forumdomain">
						</td>
						<td><div class="fun_tips">如果需要显示成目录形式，则需要设置<a href="{{ url('rewrite/rewrite/run') }}" class="J_linkframe_trigger">伪静态</a>规则，否则还是会显示动态URL</div></td>
						<!--# } #-->
					<td style="vertical-align:middle"></td>
				</tr>
			</table>
		</div>

		<div class="h_a">SEO信息</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<col width="44" />
				<tr>
					<th>title</th>
					<td><input type="text" class="input length_5" value="{{ $seo['title'] }}" name="seo[title]"></td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="seo"></td>
				</tr>
				<tr>
					<th>keywords</th>
					<td><input type="text" class="input length_5" value="{{ $seo['keywords'] }}" name="seo[keywords]"></td>
					<td></td>
					<td style="vertical-align:middle"></td>
				</tr>
				<tr>
					<th>description</th>
					<td><textarea class="length_5" name="seo[description]">{{ $seo['description'] }}</textarea></td>
					<td></td>
					<td style="vertical-align:middle"></td>
				</tr>
			</table>
		</div>
	</div>

	<!--=============功能设置===============-->
	<div>
		<div class="h_a">显示设置</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<col width="44" />
				<tr>
					<th>帖子列表标题显示字数</th>
					<td><input type="number" class="input length_5" value="{{ $forumset['numofthreadtitle'] }}" name="numofthreadtitle"></td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="numofthreadtitle"></td>
				</tr>
				<tr>
					<th>帖子列表显示主题个数</th>
					<td><input type="number" class="input length_5" value="{{ $forumset['threadperpage'] }}" name="threadperpage"></td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="threadperpage"></td>
				</tr>

@if (!$isCate)

				<tr>
					<th>阅读页每页帖子楼层数</th>
					<td><input type="number" class="input length_5" value="{{ $forumset['readperpage'] }}" name="readperpage"></td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="readperpage"></td>
				</tr>
				<!--# } #-->
				<tr>
					<th>新帖标识时间</th>
					<td><input type="number" class="input length_5 mr5" value="{{ $foruminfo['newtime'] }}" name="newtime">分钟</td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="newtime"></td>
				</tr>
				<tr>
					<th>帖子列表默认排序</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input type="radio" value="0" name="threadorderby"{{ App\Core\Tool::ifcheck(!$forumset['threadorderby']) }}><span>最后回复</span></label></li>
							<li><label><input type="radio" value="1" name="threadorderby"{{ App\Core\Tool::ifcheck($forumset['threadorderby']) }}><span>最新发帖</span></label></li>
						</ul>
					</td>
					<td><div class="fun_tips">如无特殊需求，建议使用最后回复排序，这将能获得更好的效率</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="threadorderby"></td>
				</tr>
			</table>
		</div>

@if (!$isCate)

		<div class="h_a">帖子设置</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<col width="44" />
				<tr>
					<th>内容最小长度</th>
					<td><input type="number" class="input length_5 mr5" value="{{ $forumset['minlengthofcontent'] }}" name="minlengthofcontent">字</td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="minlengthofcontent"></td>
				</tr>
				<tr>
					<th>主题锁定时间</th>
					<td><input type="number" class="input length_5 mr5" value="{{ $forumset['locktime'] }}" name="locktime">天</td>
					<td><div class="fun_tips">锁定多少天以前发布的主题不允许回复</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="locktime"></td>
				</tr>
				<tr>
					<th>编辑时间约束</th>
					<td><input type="number" class="input length_5 mr5" value="{{ $forumset['edittime'] }}" name="edittime">分钟</td>
					<td><div class="fun_tips">超过设定时间后拒绝用户编辑该版块帖子。0或留空表示不限制。管理人员不受限制。</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="edittime"></td>
				</tr>
				<tr>
					<th>允许发帖类型</th>
					<td>
						<div class="cross">
							<ul>
								<li>
									<span class="span_1">启用</span>
									<span class="span_2">顺序</span>
									<span class="span_3">帖子类型</span>
								</li>

@foreach ($threadtype as $key => $value)

								<li>
									<span class="span_1"><input name="allowtype[]" type="checkbox" value="{{ $key }}"{{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($key, $forumset['allowtype'])) }}></span>
									<span class="span_2"><input name="typeorder[{{ $key }}]" value="{{ $forumset['typeorder'][$key] }}" type="number" class="input length_1"></span>
									<span class="span_3">{{ $value[0] }}</span>
								</li>
								<!--# } #-->
							</ul>
						</div>
					</td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="allowtype"></td>
				</tr>
				<tr>
					<th>帖子扩展功能</th>
					<td>
						<ul class="single_list cc">
							<li><label><input type="checkbox" value="1" class="mr10" name="allowhide"{{ App\Core\Tool::ifcheck($forumset['allowhide']) }}><span>隐藏</span></label></li>
							<li><label><input type="checkbox" value="1" class="mr10" name="allowsell"{{ App\Core\Tool::ifcheck($forumset['allowsell']) }}><span>出售</span></label></li>
							<!-- <li><label><input type="checkbox" value="1" class="mr10" name="anonymous"{{ App\Core\Tool::ifcheck($forumset['anonymous']) }}>匿名</label></li> -->
						</ul>
					</td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="allowhide"></td>
				</tr>
				<tr>
					<th>发帖审核设置</th>
					<td>
						<ul class="single_list cc">
							<li><label><input type="radio" name="contentcheck" value="0"{{ App\Core\Tool::ifcheck($forumset['contentcheck']==0) }}><span>不需审核</span></label></li>
							<li><label><input type="radio" name="contentcheck" value="1"{{ App\Core\Tool::ifcheck($forumset['contentcheck']==1) }}><span>审核主题</span></label></li>
							<li><label><input type="radio" name="contentcheck" value="2"{{ App\Core\Tool::ifcheck($forumset['contentcheck']==2) }}><span>审核回复</span></label></li>
							<li><label><input type="radio" name="contentcheck" value="3"{{ App\Core\Tool::ifcheck($forumset['contentcheck']==3) }}><span>审核主题和回复</span></label></li>
						</ul>
					</td>
					<td></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="contentcheck"></td>
				</tr>
				<tr>
					<th>缩略图控制</th>
					<td class="J_radio_change" data-rel=".J_thumb_items">
						<ul class="single_list cc">
							<li><label><input type="radio" data-arr="J_img_tip_def" name="ifthumb" value="0"{{ App\Core\Tool::ifcheck($forumset['ifthumb']==0) }}><span>默认设置</span></label></li>
							<li><label class="mr20"><input type="radio" data-arr="J_img_thumb,J_img_tip_spe" name="ifthumb" value="1"{{ App\Core\Tool::ifcheck($forumset['ifthumb']==1) }}><span>特殊设置</span></label> <span id="J_img_thumb" class="J_thumb_items" style="display:none;"><span>宽：<input type="text" class="input mr5" value="{{ $forumset['thumbwidth'] }}" name="thumbwidth" style="width:60px"><span class="mr5">×</span>高：<input type="text" class="input mr5" value="{{ $forumset['thumbheight'] }}" name="thumbheight" style="width:60px"></span></span></li>
							<li><label><input type="radio" data-arr="" name="ifthumb" value="2"{{ App\Core\Tool::ifcheck($forumset['ifthumb']==2) }}><span>不缩略</span></label></li>
						</ul>
					</td>
					<td>
						<div class="fun_tips J_thumb_items" id="J_img_tip_def" style="display:none;">默认设置：版块内图片将使用全局-附件相关-附件缩略的配置；</div>
						<div class="fun_tips J_thumb_items" id="J_img_tip_spe" style="display:none;">特殊设置：版块内图片将使用此宽高设置，其他设置同默认设置；</div>
					</td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="ifthumb"></td>
				</tr>
				<tr>
					<th>水印功能</th>
					<td class="J_radio_change" data-rel=".J_file_items">
						<ul class="single_list cc">
							<li><label><input type="radio" data-arr="J_img_file_def" value="0" name="water"{{ App\Core\Tool::ifcheck(!$forumset['water']) }}><span>按全局设置</span></label></li>
							<li><label><input type="radio" data-arr="J_img_file,J_img_file_spe" value="1" name="water"{{ App\Core\Tool::ifcheck($forumset['water'] == 1) }}><span>开启</span> <select class="select_5 J_file_items" name="waterimg" id="J_img_file" style="display:none;">

@foreach ($waterlist as $value)

							<option value="{{ $value }}" {{ App\Core\Tool::isSelected($forumset['waterimg']==$value) }}>{{ $value }}</option>
							<!--# } #-->
						</select></label></li>
							<li><label><input type="radio" data-arr="" value="2" name="water"{{ App\Core\Tool::ifcheck($forumset['water'] == 2) }}><span>关闭</span></label></li>
						</ul>
					</td>
					<td>
						<div class="fun_tips J_file_items" id="J_img_file_def" style="display:none;">默认设置：版块内图片将使用全局-水印设置的配置；</div>
						<div class="fun_tips J_file_items" id="J_img_file_spe" style="display:none;">特殊设置：可选用存放在src/repository/mark 目录下的文件设置版块的水印，其他设置同默认设置；</div>
					</td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="water"></td>
				</tr>
			</table>
		</div>
		<!--# } #-->
	</div>

	<!--=============主题分类===============-->

@if (!$isCate)

	<div>
		<div class="h_a">主题分类设置</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<col width="44" />
				<tr>
					<th>主题分类功能</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input type="radio" value="1" name="topic_type"{{ App\Core\Tool::ifcheck($forumset['topic_type']) }}><span>开启</span></label></li>
							<li><label><input type="radio" value="0" name="topic_type"{{ App\Core\Tool::ifcheck(!$forumset['topic_type']) }}><span>关闭</span></label></li>
						</ul>
					</td>
					<td><div class="fun_tips">设置是否开启主题分类功能</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="topic_type"></td>
				</tr>
				<tr>
					<th>强制主题分类</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input type="radio" value="1" name="force_topic_type"{{ App\Core\Tool::ifcheck($forumset['force_topic_type']) }}><span>开启</span></label></li>
							<li><label><input type="radio" value="0" name="force_topic_type"{{ App\Core\Tool::ifcheck(!$forumset['force_topic_type']) }}><span>关闭</span></label></li>
						</ul>
					</td>
					<td><div class="fun_tips">开启后发帖必须选择主题分类</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="force_topic_type"></td>
				</tr>
				<tr>
					<th>主题分类显示</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input type="radio" value="1" name="topic_type_display"{{ App\Core\Tool::ifcheck($forumset['topic_type_display']) }}><span>开启</span></label></li>
							<li><label><input type="radio" value="0" name="topic_type_display"{{ App\Core\Tool::ifcheck(!$forumset['topic_type_display']) }}><span>关闭</span></label></li>
						</ul>
					</td>
					<td><div class="fun_tips">开启后帖子列表标题前显示主题分类，有图标的默认显示图标</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="topic_type_display"></td>
				</tr>
			</table>
		</div>
		<div class="table_list">
			<div class="h_a"><span class="fr"><input type="checkbox" name="copyitems[]" value="topictype" class="J_line_highlight" style="display:none;"></span>主题分类管理</div>
			<table width="100%" id="J_table_list">
				<col width="450">
				<col width="80">
				<thead>
					<tr>
						<td><span class="mr5">[顺序]</span>名称</td>
						<td class="tac">管理专用</td>
						<td>操作</td>
					</tr>
				</thead>
				<!-- update -->

@foreach ($topicTypes['topic_types'] as $v)

				<tbody>
				<tr>
					<td>
						<input type="number" class="input mr5 length_1" name="t_vieworder[{{ $v['id'] }}]" value="{{ $v['vieworder'] }}">
						<input type="text" class="input mr5 length_4" name="t_name[{{ $v['id'] }}]" value="{{ $v['name'] }}"><a style="display:none;" href="#" data-id="{{ $v['id'] }}" data-type="topic_child" data-html="tr" class="link_add J_addChild">添加二级分类</a>
					</td>
					<!--  
					<td><input name="t_logo[{{ $v['id'] }}]" type="file" class="length_3 J_upload_preview" data-preview="#J_icon_{{ $v['id'] }}">&nbsp;<img id="J_icon_{{ $v['id'] }}" src="{{ @Core::getGlobal('url', 'attach') . '/' . $v['logo'] }}" style="max-width:100px;"></td>
					-->
					<td class="tac"><input class="J_issys_par" name="t_issys[{{ $v['id'] }}]" type="checkbox" value="1" {{ App\Core\Tool::ifcheck($v['issys']) }}></td>
					<td><a href="{{ url('admin/bbs/setforum/deletetopictype') }}&id={{ $v['id'] }}" class="J_ajax_del" data-msg="确定删除此主题分类？删除此主题分类将同时删除其二级分类" data-pdata="{'id': {{ $v['id'] }}}">[删除]</a></td>
				</tr>
					<!--# $subTopicTypes = $topicTypes['sub_topic_types'][$v['id']]; #-->

@if (is_array($subTopicTypes))

						<!--#
						$count = count($subTopicTypes);
						$i = 0;
						#-->

@foreach($subTopicTypes as $v2)

							<!--# $spanClass = ++$i == $count ? 'plus_icon plus_end_icon' : 'plus_icon'; #-->
				<tr>
					<td>
						<span class="{{ $spanClass }}"></span>
						<input type="number" class="input mr5 length_1" name="t_vieworder[{{ $v2['id'] }}]" value="{{ $v2['vieworder'] }}">
						<input type="text" class="input mr5 length_4" name="t_name[{{ $v2['id'] }}]" value="{{ $v2['name'] }}">
					</td>
					<!--  
					<td><input name="t_logo[{{ $v2['id'] }}]" type="file" class="length_3 J_upload_preview" data-preview="#J_icon_{{ $v2['id'] }}">&nbsp;<img id="J_icon_{{ $v2['id'] }}" src="" style="max-width:100px;"></td>
					-->
					<td class="tac"><input class="J_issys_child" name="t_issys[{{ $v2['id'] }}]" type="checkbox" value="1" {{ App\Core\Tool::ifcheck($v2['issys']) }}></td>
					<td><a href="{{ url('admin/bbs/setforum/deletetopictype') }}&id={{ $v2['id'] }}" class="J_ajax_del" data-msg="确定删除此主题分类？" data-pdata="{'id': {{ $v2['id'] }}}">[删除]</a></td>
				</tr>
						<!--# } #-->
					<!--# } #-->
				</tbody>
				<!--# } #-->
			</table>
			<table width="100%">
				<tr><td colspan="4"><a id="J_add_root" data-type="topic_root" data-html="tbody" href="" class="link_add">添加分类</a></td></tr>
			</table>
		</div>
	</div>
	<!--# } #-->

	<!--=============权限相关===============-->
	<div>
		<div class="h_a">版块访问控制</div>
		<div class="table_full">
			<table width="100%">
				<col class="th">
				<col width="400">
				<col />
				<col width="44" />
				<tr>
					<th>访问密码</th>
					<td><input name="password" type="text" class="input length_5" value="{{ $password }}"></td>
					<td><div class="fun_tips">不加密请留空。如果版块已加密，显示的密码为******，不必再修改。</div></td>
					<td style="vertical-align:middle"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="password"></td>
				</tr>
			</table>
		</div>
		<div class="h_a"><span class="fr"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="user_allows"></span>用户组权限</div>
		<div class="table_purview">
			<table width="100%" class="J_check_wrap">
				<col width="160">
				<tr>
					<th></th>
					<td><label><input class="J_check_all" data-direction="y" data-checklist="J_check_allowvisit" name="" type="checkbox" value="">浏览版块</label></td>
					<td><label><input class="J_check_all" data-direction="y" data-checklist="J_check_allowread" name="" type="checkbox" value="">浏览帖子</label></td>
					<td><label><input class="J_check_all" data-direction="y" data-checklist="J_check_allowpost" name="" type="checkbox" value="">发布主题</label></td>
					<td><label><input class="J_check_all" data-direction="y" data-checklist="J_check_allowreply" name="" type="checkbox" value="">发布回复</label></td>
					<td><label><input class="J_check_all" data-direction="y" data-checklist="J_check_allowupload" name="" type="checkbox" value="">上传附件</label></td>
					<td><label><input class="J_check_all" data-direction="y" data-checklist="J_check_allowdownload" name="" type="checkbox" value="">下载附件</label></td>
				</tr>

@foreach($groupTypes as $k => $v)
if (!$userGroups[$k]) continue;
				#-->
				<tr class="hd">
					<th class="b">{{ $v }}</th>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

@foreach ($userGroups[$k] as $group)
$gid = $group['gid'];
					#-->
				<tr>
					<th><label><input class="J_check_all" data-direction="x" data-checklist="J_check_permission_{{ $gid }}" name="" type="checkbox" value="">{{ $group['name'] }}</label></th>
					<td><input class="J_check" data-yid="J_check_allowvisit" data-xid="J_check_permission_{{ $gid }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($gid, $p['allow_visit'])) }} name="allowvisit[]" type="checkbox" value="{{ $gid }}"></td>
					<td><input class="J_check" data-yid="J_check_allowread" data-xid="J_check_permission_{{ $gid }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($gid, $p['allow_read'])) }} name="allowread[]" type="checkbox" value="{{ $gid }}"></td>
					<td><input class="J_check" data-yid="J_check_allowpost" data-xid="J_check_permission_{{ $gid }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($gid, $p['allow_post'])) }} name="allowpost[]" type="checkbox" value="{{ $gid }}"></td>
					<td><input class="J_check" data-yid="J_check_allowreply" data-xid="J_check_permission_{{ $gid }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($gid, $p['allow_reply'])) }} name="allowreply[]" type="checkbox" value="{{ $gid }}"></td>
					<td><input class="J_check" data-yid="J_check_allowupload" data-xid="J_check_permission_{{ $gid }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($gid, $p['allow_upload'])) }} name="allowupload[]" type="checkbox" value="{{ $gid }}"></td>
					<td><input class="J_check" data-yid="J_check_allowdownload" data-xid="J_check_permission_{{ $gid }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($gid, $p['allow_download'])) }} name="allowdownload[]" type="checkbox" value="{{ $gid }}"></td>
				</tr>
					<!--# } #-->
				<!--# } #-->
			</table>
		</div>
	</div>

	<!--=============积分相关===============-->

@if (!$isCate)

	<div>
		<div class="h_a"><span class="fr"><input type="checkbox" name="copyitems[]" class="J_line_highlight" style="display:none;" value="creditset"></span>版块积分策略特殊设置</div>
		<div class="table_purview">
			<table width="100%">
				<col width="160">

@foreach ($credittype as $key => $value)

				<col width="105">
				<!--# } #-->
				<tr class="hd">
					<th>用户行为</th>
					<td>每日奖惩上限次数</td>

@foreach ($credittype as $key => $value)

					<td>{{ $value }}</td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>发布主题</th>
					<td><input name="creditset[post_topic][limit]" value="{{ $creditset['post_topic']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[post_topic][credit][{{ $key }}]" value="{{ $creditset['post_topic']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>删除主题</th>
					<td><input name="creditset[delete_topic][limit]" value="{{ $creditset['delete_topic']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[delete_topic][credit][{{ $key }}]" value="{{ $creditset['delete_topic']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>发布回复</th>
					<td><input name="creditset[post_reply][limit]" value="{{ $creditset['post_reply']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[post_reply][credit][{{ $key }}]" value="{{ $creditset['post_reply']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>删除回复</th>
					<td><input name="creditset[delete_reply][limit]" value="{{ $creditset['delete_reply']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[delete_reply][credit][{{ $key }}]" value="{{ $creditset['delete_reply']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>精华主题</th>
					<td><input name="creditset[digest_topic][limit]" value="{{ $creditset['digest_topic']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[digest_topic][credit][{{ $key }}]" value="{{ $creditset['digest_topic']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>取消精华</th>
					<td><input name="creditset[remove_digest][limit]" value="{{ $creditset['remove_digest']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[remove_digest][credit][{{ $key }}]" value="{{ $creditset['remove_digest']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>帖子推送</th>
					<td><input name="creditset[push_thread][limit]" value="{{ $creditset['push_thread']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[push_thread][credit][{{ $key }}]" value="{{ $creditset['push_thread']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>上传附件</th>
					<td><input name="creditset[upload_att][limit]" value="{{ $creditset['upload_att']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[upload_att][credit][{{ $key }}]" value="{{ $creditset['upload_att']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>下载附件</th>
					<td><input name="creditset[download_att][limit]" value="{{ $creditset['download_att']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[download_att][credit][{{ $key }}]" value="{{ $creditset['download_att']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>被喜欢</th>
					<td><input name="creditset[belike][limit]" value="{{ $creditset['belike']['limit'] }}" type="number" class="input length_1"></td>

@foreach ($credittype as $key => $value)

					<td><input name="creditset[belike][credit][{{ $key }}]" value="{{ $creditset['belike']['credit'][$key] }}" type="number" class="input length_1"></td>
					<!--# } #-->
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
	</div>
	<!--# } #-->
</div>

<div class="core_pop_wrap pop_expand" style="display:none;" id="J_copy_pop">
	<div class="core_pop">
		<div class="pop_cont">
			<a href="" class="pop_close" id="J_copy_close">关闭</a>
			<div class="mb15 s6">勾选上述设置，并批量复制到其它版块</div>
			<div class="cc shift">
				<div class="fl">
					<h4>选择版块</h4>
					<select size="10" name="" id="J_copy_select_all" multiple="true"> {!! $forumList !!}
					</select>
				</div>
				<div class="fl shift_operate">
					<p class="mb10"><a href="" class="btn" id="J_copy_add">添加 &gt;&gt;</a></p>
					<p><a href="" class="btn" id="J_copy_del">&lt;&lt; 移除</a></p>
				</div>
				<div class="fr">
					<h4>已选版块</h4>
					<select id="J_copy_select_sub" name="copy_fids[]" size="10" multiple="true">
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="core_arrow_bottom"><em></em><span></span></div>
</div>


<div class="btn_wrap">
	<div class="btn_wrap_pd">
		<button type="submit" class="btn btn_submit mr20" id="J_rights_form_btn">提交</button><a href="javascript:;" id="J_copy_rights">复制设置版块权限</a>
	</div>
</div>
</form>


</div>
@include('admin.common.footer')
<script>
/*
root_tr_html 为“添加分类”html
child_tr_html 为“添加二级分类”html
*/
var root_tr_html = '<tr class="ct"><td>\
							<input type="number" name="t_new_vieworder[root_]" class="input length_1 mr5">\
							<input type="text" name="t_new_name[root_]" class="input length_4 mr5">\
							<a class="link_add J_addChild" data-id="root_" data-html="tr" data-type="topic_child" href="" style="display:none;">添加二级分类</a>\
						</td>\
						<td class="tac"><input class="J_issys_par" type="checkbox" value="1" name="t_new_issys[root_]"></td>\
						<td><a href="" class="mr5 J_newRow_del">[删除]</a></td>\
					</tr>',
	child_tr_html = '<tr class="ct">\
                                    <td><span class="plus_icon"></span>\
                                        <input type="number" name="t_new_sub_vieworder[id_][]" class="input length_1 mr5">\
                                        <input type="text" name="t_new_sub_name[id_][]" class="input length_4 mr5">\
                                    </td>\
                                    <td class="tac"><input class="J_issys_child" type="checkbox" value="1" name="t_new_sub_issys[id_][]"></td>\
                                    <td><a href="" class="mr5 J_newRow_del">[删除]</a></td>\
                                </tr>';


Wind.use('ajaxForm', GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' +GV.JS_VERSION, function() {

	

	

	//管理专用
	$('#J_table_list').on('change', 'input.J_issys_par', function(){
		var $this = $(this),
			tr_par = $this.parents('tr'),
			tr_bro = tr_par.siblings('tr');

		if(tr_bro.length) {
			var issys_child = tr_bro.find('input.J_issys_child');
			if(this.checked) {
				issys_child.prop('checked', true);
			}else{
				issys_child.prop('checked', false);
			}
		}
		
	});
	
	//新插入行 绑定预览
	$('#J_add_root').on('click', function(){
		setTimeout(function(){
			Wind.use('uploadPreview',function() {
				$("input.J_preview_new").uploadPreview();
			});
		}, 100);
	});

	Wind.js(GV.JS_ROOT+ 'pages/pwadmin/rightsCpoy.js?v=' +GV.JS_VERSION);

});
</script>
</body>
</html>
