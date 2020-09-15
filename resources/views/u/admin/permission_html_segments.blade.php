<!--
自定义模板的权限点配置文件
 name="权限点标识" args="权限点标识,权限点配置信息array('default'=>'默认值','config'=>'配置信息')"
-->
<hook-action name="content_length_range" args="pKey,pData">
	<input type="text" name="gpermission[{{ $pKey }}][min]" value="{{ $pData['default']['min'] }}" class="input mr5"><span class="mr5">至</span><input type="text" name="gpermission[{{ $pKey }}][max]" value="{{ $pData['default']['max'] }}" class="input mr5">{{ $pData['config'][4] }}
</hook-action>

<hook-action name="sell_credits" args="pKey,pData">
	<!--# 
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();
	#-->
	<ul class="three_list cc">

@foreach ($creditBo->cType as $k => $v)

			<li><label><input{{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($k, $pData['default'])) }} type="checkbox" name="gpermission[{{ $pKey }}][{{ $k }}]" value="{{ $k }}"><span>{{ $v }}</span></label></li>
	<!--# } #-->
	</ul>
</hook-action>

<hook-action name="sell_credit_range" args="pKey,pData">
	<div class="mb5"><input type="text" name="gpermission[{{ $pKey }}][maxprice]" value="{{ $pData['default']['maxprice'] }}" class="input mr5">最高单价限制</div>
	<div><input type="text" name="gpermission[{{ $pKey }}][maxincome]" value="{{ $pData['default']['maxincome'] }}" class="input mr5">最高收入限制</div>
</hook-action>

<hook-action name="sign_cost" args="pKey,pData">
	<!--# 
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();
	#-->
	<input type="text" name="gpermission[{{ $pKey }}][costs]" value="{{ $pData['default']['costs'] }}" class="input length3 mr5">
	<select name="gpermission[{{ $pKey }}][creditid]" class="select_2">

@foreach ($creditBo->cType as $k => $v)

	 	<option value="{{ $k }}"{{ App\Core\Tool::isSelected($k == $pData['default']['creditid']) }}>{{ $v }}</option>
	<!--# } #-->
	</select>
</hook-action>

<hook-action name="enhide_credits" args="pKey,pData">
	<!--# 
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();
	 #-->
	<ul class="three_list cc">

@foreach ($creditBo->cType as $k => $v)

		<li><label><input{{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($k, $pData['default'])) }} type="checkbox" name="gpermission[{{ $pKey }}][{{ $k }}]" value="{{ $k }}"><span>{{ $v }}</span></label></li>
	<!--# } #-->
	</ul>
</hook-action>

<hook-action name="operate_thread" args="pKey,pData">
	<ul class="three_list cc">
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][digest]" {{ App\Core\Tool::ifcheck($pData['default']['digest']) }}><span>精华</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][highlight]" {{ App\Core\Tool::ifcheck($pData['default']['highlight']) }}><span>加亮</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][type]" {{ App\Core\Tool::ifcheck($pData['default']['type']) }}><span>分类</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][move]" {{ App\Core\Tool::ifcheck($pData['default']['move']) }}><span>移动</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][read]" {{ App\Core\Tool::ifcheck($pData['default']['read']) }}><span>已阅</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][edit]" {{ App\Core\Tool::ifcheck($pData['default']['edit']) }}><span>编辑</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][copy]" {{ App\Core\Tool::ifcheck($pData['default']['copy']) }}><span>复制</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][delete]" {{ App\Core\Tool::ifcheck($pData['default']['delete']) }}><span>删除</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][lock]" {{ App\Core\Tool::ifcheck($pData['default']['lock']) }}><span>锁定</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][down]" {{ App\Core\Tool::ifcheck($pData['default']['down']) }}><span>压帖</span></label></li>
		<!-- <li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][unite]" {{ App\Core\Tool::ifcheck($pData['default']['unite']) }}>合并</label></li> -->
		<!-- <li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][split]" {{ App\Core\Tool::ifcheck($pData['default']['split']) }}>拆分</label></li> -->
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][shield]" {{ App\Core\Tool::ifcheck($pData['default']['shield']) }}><span>屏蔽</span></label></li>
		<!-- <li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][remind]" {{ App\Core\Tool::ifcheck($pData['default']['remind']) }}>提醒</label></li> -->
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][deleteatt]" {{ App\Core\Tool::ifcheck($pData['default']['deleteatt']) }}><span>删除附件</span></label></li>
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][toppedreply]" {{ App\Core\Tool::ifcheck($pData['default']['toppedreply']) }}><span>帖内置顶</span></label></li>
		<!-- <li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][print]" {{ App\Core\Tool::ifcheck($pData['default']['print']) }}>帖子印戳</label></li> -->
		<li><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][ban]" {{ App\Core\Tool::ifcheck($pData['default']['ban']) }}><span>禁止</span></label></li>
	</ul>
	<div class="about_list">
		<dl class="cc">
			<dt><label><input type="checkbox" name="gpermission[{{ $pKey }}][up]" value="1" {{ App\Core\Tool::ifcheck($pData['default']['up']) }}><span>提前</span></label></dt>
			<dd><input type="number" class="input length_2 mr5" name="gpermission[{{ $pKey }}][up_time]" value="{{ $pData['default']['up_time'] }}">小时</dd>
		</dl>
		<dl class="cc">
			<dt><label><input type="checkbox" value="1" name="gpermission[{{ $pKey }}][topped]" {{ App\Core\Tool::ifcheck($pData['default']['topped']) }}><span>置顶</span></label></dt>
			<dd class="fl">
				<p><label><input type="radio" name="gpermission[{{ $pKey }}][topped_type]" value="1"
@if($pData['default']['topped_type'] <= 1)
 checked<!--# } #-->><span>版块置顶</span></label>当前版块以及下级版块置顶</p>
				<p><label><input type="radio" name="gpermission[{{ $pKey }}][topped_type]" value="2"
@if($pData['default']['topped_type'] == 2)
 checked<!--# } #-->><span>分类置顶</span></label>当前版块分类以及下级版块置顶</p>
				<p><label><input type="radio" name="gpermission[{{ $pKey }}][topped_type]" value="3"
@if($pData['default']['topped_type'] == 3)
 checked<!--# } #-->><span>全局置顶</span></label>所有版块置顶</p>
			</dd>
		</dl>
	</div>
</hook-action>

<hook-action name="design_allow_manage" args="pKey,pData">
	<ul class="three_list cc">
		<li><label><input type="radio" name="gpermission[{{ $pKey }}][push]" value="4" {{ App\Core\Tool::ifcheck(4 == $pData['default']['push']) }}><span>门户设计</span></label></li>
	</ul>
	<ul class="three_list cc">
		<li><label><input type="radio" name="gpermission[{{ $pKey }}][push]" value="3" {{ App\Core\Tool::ifcheck(3 == $pData['default']['push']) }}><span>编辑模块</span></label></li>
	</ul>
	<ul class="three_list cc">
		<li><label><input type="radio" name="gpermission[{{ $pKey }}][push]" value="2" {{ App\Core\Tool::ifcheck(2 == $pData['default']['push']) }}><span>管理内容</span></label></li>
	</ul>
	<ul class="three_list cc">
		<li><label><input type="radio" name="gpermission[{{ $pKey }}][push]" value="1" {{ App\Core\Tool::ifcheck(1 == $pData['default']['push']) }}><span>推送内容需审核</span></label></li>
	</ul>
	<ul class="three_list cc">
		<li><label><input type="radio" name="gpermission[{{ $pKey }}][push]" value="0" {{ App\Core\Tool::ifcheck(!$pData['default']['push']) }}><span>无操作权限</span></label></li>
	</ul>
</hook-action>

<hook-action name="thread_edit_time" args="pKey,pData">
	<input type="text" class="input length_5 mr5" value="{{ $pData['default'] }}" name="gpermission[{{ $pKey }}]"><span>分钟</span>
</hook-action>

<hook-action name="post_pertime" args="pKey,pData">
	<input type="text" class="input length_5 mr5" value="{{ $pData['default'] }}" name="gpermission[{{ $pKey }}]"><span>秒</span>
</hook-action>

<hook-action name="post_modify_time" args="pKey,pData">
	<input type="text" class="input length_5 mr5" value="{{ $pData['default'] }}" name="gpermission[{{ $pKey }}]"><span>分钟</span>
</hook-action>