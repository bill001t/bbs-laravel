<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>此功能允许管理员手动维护地区的联动关系及新增区域，用户在编辑个人资料时更准确地定义归属区域。</li>
			<li>此功能的维护涉及全站所有的地域联动调用，请管理员根据实际的行政地域调整变化修改。</li>
		</ul>
	</div>
	<div class="h_a">地区管理</div><div class="pop_loading" id="J_loading"></div>
	<div class="table_full J_loading_show" style="display:none;">

		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>选择地区</th>
				<td>
					<div class="yarnball mr20 fl">
						<ul class="cc">
							<li id="J_yarnball_all" class="{{ $route['all']['disable'] }}"><a class="J_yarnball" href="#" data-type="all" data-id="">全部</a><em></em></li>
							<li id="J_yarnball_province" class="{{ $route['province']['disable'] }}" style="{{ $route['province']['display'] }}"><a class="J_yarnball" href="" data-type="province" data-id="{{ $route['province']['areaid'] }}">{{ $route['province']['name'] }}</a><em></em></li>
							<li id="J_yarnball_city" class="li_disabled" style="{{ $route['city']['display'] }}"><a class="J_yarnball" href="" data-id="{{ $route['city']['areaid'] }}">{{ $route['city']['name'] }}</a><em></em></li>
						</ul>
					</div>

@if ($hasLevel)

					<a href="#" id="J_region_set" data-change="change" style="float:left;margin-top:7px;">更换省市&gt;&gt;</a>

@else

					<a href="#" id="J_region_set" style="float:left;margin-top:7px;">请选择&gt;&gt;</a>
<!--#}#-->
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
		</table>
	</div>
	
	<form class="J_ajaxForm J_loading_show" action="{{ url('windidclient/areadata/update') }}" method="post" style="display:none;">
	<input id="J_parentid" type="hidden" value="{{ $areaid }}" name="parentid" />
	<div class="table_full">
		<table id="J_table_list" width="100%">
		<col width="160">
		<thead>
			<tr class="h_a">
				<th>双击名称进行编辑</th>
				<td>操作</td>
			</tr>
		</thead>
		 <tbody id="J_region_list">
<!--#
$data_role = '';
$data_name = '';
switch($hasLevel) {
	case 2:
		$data_role = 'district';
		$data_name = '';
		break;
	case 1:
		$data_role = 'city';
		$data_name = '下级区县';
		break;
	case 0:
		$data_role = 'province';
		$data_name = '下级城市';
		break;
}
foreach ($list as $_i) {#-->
			<tr>
				<th data-id="{{ $_i['areaid'] }}"><div class="J_items">{{ $_i['name'] }}</div></th>
				<td>
@if ($hasLevel < 2)

					<a href="{{ url('windidclient/areadata/run?parentid=' . $_i['areaid']) }}" class="mr10 J_region_next" data-name="{{ $_i['name'] }}" data-type="{{ $data_role }}" data-id="{{ $_i['areaid'] }}">[{{ $data_name }}]</a>
					<!--#}#-->
					<a data-id="{{ $_i['areaid'] }}" data-role="{{ $data_role }}" href="{{ url('windidclient/areadata/delete?areaid=' . $_i['areaid']) }}" class="J_region_del" data-pdata="{'areaid': {{ $_i['areaid'] }}}">[删除]</a></td>
			</tr>
<!--#}#-->
		</tbody>
		</table>
		<div class="p10"><a id="J_add_root" data-html="tbody" data-type="" href="" class="link_add">添加</a></div>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd"><button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button></div>
	</div>
	</form>

<!--=========================弹窗============================-->
<div class="core_pop_wrap" id="J_region_pop" style="display:none;">
	<div class="core_pop">
		<form action="{{ url('windidclient/areadata/run') }}" method="post">
		<div style="width:600px;">
			<div class="pop_top">
				<a href="" id="J_region_pop_x" class="pop_close">关闭</a>
				<strong>选择地区</strong>
			</div>
			<div class="pop_cont">
				<div class="pop_region_list">
					<ul id="J_region_pop_province" class="cc"></ul>
					<div class="hr"></div>
					<ul id="J_region_pop_city" class="cc">
						<li><span>请选择</span></li>
					</ul>
				</div>
			</div>
			<div class="pop_bottom tac">
				<button type="submit" class="btn btn_submit mr10" id="J_region_pop_sub">确认</button><button id="J_region_pop_close" type="submit" class="btn">关闭</button>
			</div>
		</div>
		</form>
	</div>
</div>
<!--===========================结束==========================-->
	
	
</div>
@include('admin.common.footer')
<script>
//GV.REGION_CONFIG, GV.URL.REGION由footer定义

Wind.use('dialog', 'draggable', function() {

	$.post(GV.URL.REGION, function(data){
		//获取地区数据后显示内容
		$('#J_loading').remove();
		$('.J_loading_show').show();
		if(data) {
			GV.REGION_CONFIG = data;
		}
	}, 'json');
	
	var region_set = $('#J_region_set'), 										//“请选择”按钮
			region_pop = $('#J_region_pop'), 										//地区弹窗
			region_pop_province = $('#J_region_pop_province'),	//地区弹窗_国家&省行
			region_pop_city = $('#J_region_pop_city'),					//地区弹窗_城市行
			region_list = $('#J_region_list'),									//地区编辑列表
			parentid = $('#J_parentid');												//提交父id
		//city_set; 																					//城市是否已选择
	
	//引入弹窗拖动组件
	region_pop.draggable( { handle : '.pop_top'} );
	
	var yarnball_all = $('#J_yarnball_all'),								//面包屑_全部
			yarnball_province = $('#J_yarnball_province'),			//面包屑_省
			yarnball_city = $('#J_yarnball_city');							//面包屑_市
		
	//table列表中省市区的html
	var tr_province = '<tr><th data-id="_ID"><div class="J_items">_TEXT</div></th><td><a data-id="_ID" data-type="province" class="mr10 J_region_next" data-name="_PNAME" href="#">[下级城市]</a><a class="J_region_del" data-pdata="{{ \'areaid\': \'_ID\' }}" data-role="province" href="{{ url('windidclient/areadata/delete?areaid=_ID') }}" data-id="_ID">[删除]</a></td></tr>',
		tr_city = '<tr><th data-id="_ID"><div class="J_items">_TEXT</div></th><td><a data-id="_ID" data-type="city" class="mr10 J_region_next" data-name="_CNAME" href="#">[下级区县]</a><a class="J_region_del" data-pdata="{{ \'areaid\': \'_ID\' }}"  data-role="city" href="{{ url('windidclient/areadata/delete?areaid=_ID') }}" data-id="_ID">[删除]</a></td></tr>',
		tr_district = '<tr><th data-id="_ID"><div class="J_items">_TEXT</div></th><td><a class="J_region_del" data-pdata="{{ \'areaid\': \'_ID\' }}"  data-role="district" href="{{ url('windidclient/areadata/delete?areaid=_ID') }}" data-id="_ID">[删除]</a></td></tr>';
	
	$('#J_yarnball_list').children().hover(function(){
		$(this).addClass('hover');
	}, function(){
		$(this).removeClass('hover');
	});
	
	//点击“请选择”&“更换省市”按钮
	region_set.on('click', function(e){
		e.preventDefault();
		var $this = $(this), province_arr = [];
		
		if(!GV.REGION_CONFIG) {
			return false;
		}
		//循环省数据进数组
		$.each(GV.REGION_CONFIG, function(i, o){
			province_arr.push('<li id="J_p_'+ i +'" data-id="'+ i +'"><a href="#" class="J_province">'+ o.name +'</a></li>');
		});
		
		//写入国家&省的html
		region_pop_province.html(province_arr.join(''));
		
		//初始化城市列表的html
		region_pop_city.html('<li><span>请选择</span></li>');
		
		
		//“更换省市”
		if($this.data('change')) {
			var current_p_id = yarnball_province.children('a.J_yarnball').data('id'),
				current_p_name = yarnball_province.children('a.J_yarnball').text(),
				current_c_id = yarnball_city.children('a.J_yarnball').data('id'),
				current_city_arr = [];

			//当前省状态
			$('#J_p_'+current_p_id).addClass('current').siblings('li.current').removeClass('current');

			//循环城市数据进数组
			var items = GV.REGION_CONFIG[current_p_id]['items'];
			if(!items) {
				region_pop_city.html('<li><em class="gray">没有城市数据</em></li>');
			}else{
				$.each(items, function(i, o){
					current_city_arr.push('<li id="J_c_'+ i +'" data-id="'+ i +'"><a href="#" class="J_city">'+ o.name +'</a></li>');
				});
				region_pop_city.html('<li class="current" data-cname=""><a href="#" class="J_city">请选择</a></li>'+ current_city_arr.join(''));
			}

			//当前城市
			if(current_c_id) {
				$('#J_c_'+current_c_id).addClass('current').siblings('li.current').removeClass('current');
			}
			
		}
		
		
		//显示弹窗
		region_pop.show().css({
			left : ($(window).width() - region_pop.outerWidth())/2,
			top : ($(window).height() - region_pop.outerHeight())/2 + $(document).scrollTop()
		});
		
	});
	
	
	//点击弹窗省
	region_pop_province.on('click', 'a.J_province', function(e){
		e.preventDefault();
		var city_arr = [],
			$this = $(this),
			$li = $this.parent(),
			id = $li.data('id'),
			itmes = GV.REGION_CONFIG[id]['items'];
		
		$li.addClass('current').siblings('li.current').removeClass('current');
			
		if(!itmes) {
			region_pop_city.html('<li><em class="gray">没有城市数据</em></li>');
			return;
		}

		$.each(itmes, function(i, o){
			city_arr.push('<li id="J_c_'+ i +'" data-id="'+ i +'"><a href="#" class="J_city">'+ o.name +'</a></li>');
		});
			
		//写入城市的html
		region_pop_city.html('<li class="current" data-id=""><a href="#" class="J_city">请选择</a></li>'+ city_arr.join(''));

	});
	
	
	//点击弹窗的城市
	region_pop_city.on('click', 'a.J_city', function(e){
		e.preventDefault();
		var $li = $(this).parent();
		
		//切换点击状态
		$li.addClass('current').siblings('li.current').removeClass('current');
		
	});
	
	
	//地区弹窗_提交
	$('#J_region_pop_sub').on('click', function(e){
		e.preventDefault();
		var p_li = $('#J_region_pop_province > li.current'),	//选择的省
			p_name = p_li.data('pname'),												//选择的省名
			p_id = p_li.data('id'),															//选择的省id
			c_li = $('#J_region_pop_city > li.current'),				//选择的城市项
			c_name = c_li.data('cname'),												//选择的城市名
			c_id = c_li.data('id'),															//选择的城市id
			region_arr = [],																		//地区数组
			p_c_set = false;																		//省&市未选择，用于判断显示“请选择”或“更换省市”按钮
			
		if(c_id) {
			//选择了城市
			var items = GV.REGION_CONFIG[p_id]['items'][c_id]['items'];

			if(!items) {
				region_arr = [];
			}else{
				//循环区县数据
				$.each(items, function(i, o){
					region_arr.push( tr_district.replace(/_ID/g, i).replace(/_TEXT/, o) );
				});
			}
				
			yarnball_province.show().removeClass('li_disabled').find('a.J_yarnball').text(p_li.text()).data('id', p_id); //面包屑_显示省且可点
			yarnball_city.show().find('a.J_yarnball').text(c_li.text()).data('id', c_id); //面包屑_显示城市
			parentid.val(c_id);
		}else{
			//仅选择了省
			var items = GV.REGION_CONFIG[p_id]['items'];

			if(!items) {
				region_arr = [];
			}else{
				//循环城市数据
				$.each(items, function(i, o){
					region_arr.push( tr_city.replace(/_ID/g, i).replace(/_TEXT|_CNAME/g, o.name) );
				});
			}
			
				
			yarnball_province.show().addClass('li_disabled').find('a.J_yarnball').text(p_li.text()).data('id', p_id); //面包屑_显示省且不可点
			yarnball_city.hide().children('a.J_yarnball').removeData('id'); //面包屑_隐藏城市
			parentid.val(p_id);
		}
		
		yarnball_all.removeClass('li_disabled');
		p_c_set = true; //省&市已选择

		
		//写入列表
		region_list.html(region_arr.join(''));
		
		//隐藏地区弹窗
		region_pop.hide(0, function(){
			if(p_c_set) {
				region_set.data('change', 'change').text('更换省市>>');
			}else{
				region_set.removeData('change').text('请选择>>');
			}
		});
		
	});
	
	
	//关闭地图弹窗
	$('#J_region_pop_x, #J_region_pop_close').on('click', function(e){
		e.preventDefault();
		region_pop.hide();
	});

	
	//点击面包屑（城市不可点）
	$('a.J_yarnball').on('click', function(e){
		e.preventDefault();
		var $this = $(this);

		//不可点状态
		if($this.parent().hasClass('li_disabled')) {
			return false;
		}
		
		//调用changeRegionList()方法，更换地区列表
		changeRegionList($this.data('type'), $this.text(), $this.data('id'));
	});

	
	//点“下级城市”&“下级区县”
	region_list.on('click', 'a.J_region_next', function(e){
		e.preventDefault();
		var $this = $(this);
		changeRegionList($this.data('type'), $this.data('name'), $this.data('id'));
	});

	
	region_list.on('dblclick', '.J_items', function(e){
		//双击编辑
		var items_edit = $(this).siblings('input.J_items_edit');
		if(items_edit.length) {
			//显示编辑
			$(this).hide();
			items_edit.show().focus();
		}else{
			//插入编辑
			$(this).hide().after('<input type="text" class="input length_2 J_items_edit" value="'+ $(this).text() +'" name="update['+ $(this).parent().data('id') +']">');
			$(this).siblings('input.J_items_edit').focus();
		}
		
	});

	//删除
	region_list.on('click', 'a.J_region_del', function(e){
		e.preventDefault();
		var $this = $(this),
				role = $this.data('role'),
				id = $this.data('id'),
				pdata = $this.data('pdata');

		//dialog
		Wind.dialog({
			message	: '确定删除该地区？', 
			type	: 'confirm', 
			isMask	: false,
			follow	: $this,//跟随触发事件的元素显示
			onOk	: function() {

				$.ajax({
					url: $this.attr('href'),
					type : 'post',
					dataType: 'json',
					data: function(){
						if(pdata) {
							return $.parseJSON(pdata.replace(/'/g, '"'));
						}
					}(),
					success: function(data){
						if(data.state == 'success') {
							$this.parents('tr').slideUp('slow', function(){
								$(this).remove();
							});
							if(role == 'province') {
								delete GV.REGION_CONFIG[id];
							}else if(role == 'city') {
								delete GV.REGION_CONFIG[$('#J_yarnball_province a.J_yarnball').data('id')]['items'][id];
							}else if(role == 'district') {
								delete GV.REGION_CONFIG[$('#J_yarnball_province a.J_yarnball').data('id')]['items'][$('#J_yarnball_city a.J_yarnball').data('id')]['items'][id];
							}
						}else if(data.state == 'fail'){
							Wind.dialog.alert(data.message[0]);
						}
					}
				});

			}
		});

	});
	
	
	//点击面包屑或下级地区 列出相应省或城市
	function changeRegionList(type, name, id){
		var arr = [];

		if(!GV.REGION_CONFIG) {
			return false;
		}

		if(type === 'all') {
			//全部
			
			//循环省数据进数组
			$.each(GV.REGION_CONFIG, function(i, o){
				arr.push( tr_province.replace(/_ID/g, i).replace(/_PNAME|_TEXT/g, o.name) );
			});
			
			yarnball_all.addClass('li_disabled');										//面包屑_全部不可点
			yarnball_province.hide().children('a.J_yarnball');			//面包屑_隐藏省
			yarnball_city.hide().children('a.J_yarnball');					//面包屑_隐藏城市
			region_set.removeData('change').text('请选择>>');
			parentid.val('0');
		}if(type === 'province') {
			//点面包屑上的省或“下级城市”
			var data = GV.REGION_CONFIG[id]['items'];
			
			if(data) {
				$.each(data, function(i, o){
					arr.push( tr_city.replace(/_ID/g, i).replace(/_TEXT|_CNAME/g, o.name) );
				});
			}
			

			yarnball_all.removeClass('li_disabled');										//面包屑_全部不可点
			yarnball_province.show().addClass('li_disabled').children('a.J_yarnball').text(name).data('id', id);	//面包屑_省不可点
			yarnball_city.hide().children('a.J_yarnball').text('').removeData('id');						//面包屑_隐藏城市
			region_set.data('change', 'change').text('更换省市>>');
			parentid.val(id);
		}else if(type === 'city'){
			//点击“下级区县”
			var data = GV.REGION_CONFIG[yarnball_province.children('a.J_yarnball').data('id')]['items'][id]['items'];

			if(data) {
				$.each(data, function(i, o){
					arr.push( tr_district.replace(/_ID/g, i).replace(/_TEXT/, o) );
				});
			}
			
			yarnball_all.removeClass('li_disabled');										//面包屑_全部不可点
			yarnball_province.removeClass('li_disabled');	//面包屑_省不可点
			yarnball_city.show().children('a.J_yarnball').text(name).data('id', id);
			region_set.data('change', 'change').text('更换省市>>');
			parentid.val(id);
		}
		
		//写入html
		region_list.html(arr.join(''));
		
	}

	Wind.js(GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' +GV.JS_VERSION);

});

//添加 模板 forumTree_table.js
var root_tr_html = '<tr><th data-id=""><input type="text" name="add[]" value="" class="input length_2"></th><td><a class="J_newRow_del" href="#">[删除]</a></td></tr>';
</script>
</body>
</html>
