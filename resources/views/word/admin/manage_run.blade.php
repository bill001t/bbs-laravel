<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">

@if($syncStatus)

	<div class="tips_light">敏感词已更新到{{ App\Core\Tool::time2str($sync['lasttime'], 'Y-m-d') }}，新增{$sync['syncnum']}个词语，<a href="{{ url('word/manage/sync') }}">立即同步</a></div>
<!--# } #-->
	<div class="h_a">提示信息</div>
	<div class="prompt_text">
		<ul>
			<li>敏感词管理可以有效保证您网站的顺利运营。为了提高发帖效率，建议敏感词不超过3500个</li>
			<li>您可以&nbsp;<a target="_blank" href="{{ url('word/manage/export') }}" class="mr10">导出词库</a><a href="{{ url('word/manage/import') }}" class="J_dialog" title="导入本地词库">导入本地词库</a></li>
		</ul>
	</div>
	<form id="J_word_tip_form" action="{{ url('word/manage/setconfig') }}" method="post">
	<div class="h_a">敏感词设置</div>
	<div class="table_full">
	<table width="100%">
		<colgroup>
			<col class="th">
			<col width="400">
		</colgroup>
		<tr>
			<th>前台文字提示</th>
			<td>
				<ul class="switch_list cc">
					<li><label><input name="config[tip]" value="1" type="radio" {{ App\Core\Tool::ifcheck($config[istip] == 1) }}><span>开启</span></label></li>
					<li><label><input name="config[tip]" value="0" type="radio" {{ App\Core\Tool::ifcheck($config[istip] == 0) }}><span>关闭</span></label></li>
				</ul>
			</td>
			<td><div class="fun_tips">开启后,将会在前台通知用户,发布内容中哪些是敏感词</div></td>
		</tr>
	</table>
	</div>
	<div class="mb10"><button class="btn btn_submit" type="submit">提交</button></div>
	</form>
	
	<div class="h_a">搜索</div>
	<form method="post" action="{{ url('word/manage/search') }}">
	<div class="search_type cc mb10">
		<select name="type" class="select_3 mr10"><option value="-1">所有级别</option>

@foreach($typeList as $key=>$value)

				<!--# $selected = $key == $args[type] ? 'selected' : ''; #-->
				<option value="{{ $key }}"{{ $selected }}>{{ $value }}</option>
			<!--# } #-->
		</select>
		<input name="keyword" type="text" class="input length_3 mr10" value="{{ $args[keyword] }}"  placeholder="敏感词关键字">
		<button class="btn" type="submit">搜索</button>
	</div>
	</form>
	
	<div class="mb10"><a class="btn J_dialog" href="{{ url('word/manage/add') }}" title="添加敏感词"><span class="add"></span>添加敏感词</a></div>
	
	<div class="tips mb10" id="J_check_tip_part" style="display:none;">
		此页中的全部<span class="J_count"></span>项已选中，<a href="" id="J_check_page">同时选择所有页面的<span class="count">{{ $total }}</span>项</a>
	</div>
	<!--# $check_all_display = isset($ischeckAll) && $ischeckAll ? '' : 'display:none;'; #-->
	<div class="tips mb10" id="J_check_tip_all" style="{{ $check_all_display }}">
		已选中所有页面<span class="J_count">{{ $total }}</span>项，<a href="" id="J_check_cancl">取消全选</a>
	</div>
	<form id="J_word_form" class="J_ajaxForm" action="{{ url('word/manage/*') }}" method="post">
		<div class="table_list">
			<table width="100%">
				<colgroup>
					<col width="60">
					<col width="100">
					<col width="200">
					<col width="150">
				</colgroup>
				<thead>
					<tr>
						<!--# $checked = isset($ischeckAll) && $ischeckAll ? 'checked' : ''; #-->
						<td><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"{{ $checked }}>全选</label></td>
						<td>ID(共{$total}个)</td>
						<td>敏感词</td>
						<td><select name="type" id="J_filter_select"><option value="-1">所有级别</option>

@foreach($typeList as $key=>$value)

				<!--# $selected = $key == $args[type] ? 'selected' : ''; #-->
				<option value="{{ $key }}"{{ $selected }}>{{ $value }}</option>
			<!--# } #-->
		</select></td>
						<td>操作</td>
					</tr>
				</thead>
				<tbody id="J_word_list">

@foreach ($wordList as $key => $value)
$value['word_type'] = App\Core\Tool::inArray($value['word_type'], array_keys($typeList)) ? $typeList[$value['word_type']] : '';
				#-->
				<tr>

					<td><input type="checkbox" name="ids[]" value="{{ $value['word_id'] }}" class="J_check" data-yid="J_check_y" data-xid="J_check_x"{{ $checked }}></td>
					<td>{{ $key+1 }}</td>
					<td>{{ $value['word'] }}</td>
					<td>{{ $value['word_type'] }}</td>
					<td><a class="mr5 J_dialog" title="编辑" href="{{ url('word/manage/edit?id=' . $value['word_id']) }}">[编辑]</a><a href="{{ url('word/manage/delete') }}" data-msg="确定删除该敏感词" class="J_ajax_del" data-pdata="{'id': {{ $value['word_id'] }}}">[删除]</a></td>
				</tr>
				<!--# } #-->
				</tbody>
			</table>

@if (!$total && $action == 'search')

			<div class="not_content_mini"><i></i>啊哦，没有符合条件的内容！</div>
			<!--# } #-->
			<div class="p10" id="J_page_wrap">
				<page tpl='TPL:common.page' page='$page' count='$total' per='$perpage' url='word/manage/search' args='$args'/>
			</div>

		</div>

@if ($wordList)

		<div class="btn_wrap">

			<div class="btn_wrap_pd">
				
				<div class="select_pages">
					<a href="{{ url('word/manage/search?perpage=20', $args) }}">20</a><span>|</span>
					<a href="{{ url('word/manage/search?perpage=50', $args) }}">50</a><span>|</span>
					<a href="{{ url('word/manage/search?perpage=100', $args) }}">100</a>
				</div>
				
				<label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>
				<input type="hidden" name="checkall" id="J_all_checked" value="0"/>
				<input type="hidden" name="keyword" value="{{ $args['keyword'] }}"/>
				<button class="btn btn_submit" id="J_edit_btn" type="button">编辑</button>
				<button class="btn J_ajax_delete_btn" type="button">删除</button>
			</div>

		</div>
		<!--# } #-->
	</form>
</div>

@include('admin.common.footer')
<script>
Wind.use('dialog', 'ajaxForm', function(){
	var word_form = $('#J_word_form');
			word_form.resetForm();
	
	//提示开启关闭
	$('#J_word_tip_form').ajaxForm({
		dataType : 'json',
		beforeSubmit: function(arr, $form, options) {
			$('#J_word_tip_error').remove();
		},
		success : function(data, statusText, xhr, $form) {
			var btn = $form.find('button:submit');
			if(data.state == 'success') {
				$( '<span class="tips_success">' + data.message + '</span>' ).insertAfter(btn).fadeIn('slow').delay( 1000 ).fadeOut(function() {
					$(this).remove();
				});
			}else if(data.state == 'fail') {
				$( '<span class="tips_error" id="J_word_tip_error">' + data.message + '</span>' ).insertAfter(btn).fadeIn( 'fast' );
			}
		}
	});

	//到处词库
	$('#J_word_export').on('click', function(e){
		e.preventDefault();
		$.post($(this).attr('href'),function(data){});
	});


	var URL_DELETE = "{{ url('/word/manage/batchdelete') }}",	//删除
			URL_EDIT = "{{ url('/word/manage/batchedit') }}",	//编辑
			word_list = $('#J_word_list');
	
	//点击删除
	$('button.J_ajax_delete_btn').on('click', function(e){
		e.preventDefault();
		var $this = $(this);
		
		$this.parent().find('span').remove();
		if (getCheckedTr()) {
			
			Wind.dialog({
				type : 'confirm',
				message : '确定删除选中的敏感词？',
				isMask : true,
				follow	: $this,
				onOk : function(){
					word_form.ajaxSubmit({
						url : URL_DELETE,
						dataType : 'json',
						beforeSubmit: function(arr, $form, options) { 
							$this.prop('disabled',true).addClass('disabled');
						},
						success : function(data, statusText, xhr, $form) {
							if( data.state === 'success' ) {
								$( '<span class="tips_success">' + data.message + '</span>' ).insertAfter($this).fadeIn('slow').delay( 1000 ).fadeOut(function() {
									reloadPage(window);
								});
							}else if( data.state === 'fail' ) {
								$( '<span class="tips_error">' + data.message + '</span>' ).insertAfter($this).fadeIn( 'fast' );
								$this.removeProp('disabled').removeClass('disabled');
							}
						}
					});
				},
				onCancel : function(){
					$this.focus();
				}
			});
		} else {
			$( '<span class="tips_error">请至少选择一项</span>' ).appendTo($this.parent()).fadeIn( 'fast' );
		}
		
	});
	
	//批量编辑
	$('#J_edit_btn').on('click', function(e){
		e.preventDefault();
		var $this = $(this);
		
		$this.parent().find('span').remove();
		if (getCheckedTr()) {
			word_form.ajaxSubmit({
				url : URL_EDIT,
				dataType : 'html',
				beforeSubmit: function(arr, $form, options) {
				},
				success : function(data, statusText, xhr, $form) {
					if( data ) {
						Wind.dialog.open( unescape(URL_EDIT +'&'+ word_form.formSerialize()) ,{
							onClose : function() {
								$this.focus();//关闭时让触发弹窗的元素获取焦点
							},
							title : '编辑'
						});
					}
				}
			});
		} else {
			$( '<span class="tips_error">请至少选择一项</span>' ).appendTo($this.parent()).fadeIn( 'fast' );
		}
	});
	
	//选择统计
	function getCheckedTr(){
		if(word_list.find('input.J_check:checked').length >= 1) {
			return true;
		}else{
			return false;
		}
	}

	

	//筛选
	$('#J_filter_select').on('change', function(){
		window.location.href = '{{ url('word/manage/search') }}&type='+ $(this).val();
	});

	//选择统计
	var check_tip_part = $('#J_check_tip_part'),
		check_tip_all = $('#J_check_tip_all'),
		all_checked = $('#J_all_checked'),
		checkbox = word_form.find('input:checkbox'),
		page_a = $('#J_page_wrap a');			//分页链接

	checkbox.on('change', function(){
		var checked = $('input.J_check:checked');

		if(checkbox.filter('input.J_check').length === checked.length) {
			check_tip_part.show().children('.J_count').text(checked.length);
		}else{
			check_tip_part.hide();
		}
		check_tip_all.hide();
		all_checked.val('0');
	});

	//选择所有页面
	$('#J_check_page').on('click', function(e){
		e.preventDefault();
		check_tip_part.hide();
		check_tip_all.show();
		checkbox.attr('checked', 'checked');
		all_checked.val('1');

		page_a.each(function(){
			this.href = changeURLPar(this.href, '_check', '1');
		});

	});

	//取消选择所有
	$('#J_check_cancl').on('click', function(e){
		e.preventDefault();
		var checked = $('input.J_check:checked');

		check_tip_part.hide();
		check_tip_all.hide();

		checkbox.removeAttr('checked');
		all_checked.val('0');

		page_a.each(function(){
			this.href = changeURLPar(this.href, '_check', '0');
		});
	});

	//更换url参数
	function changeURLPar(destiny, par, par_value) {
		var pattern = par+'=([^&]*)';
		var replaceText = par+'='+par_value;

		if (destiny.match(pattern)) {
			var tmp = '/\\'+par+'=[^&]*/';
			tmp = destiny.replace(eval(tmp), replaceText);
			return (tmp);
		}else{
			if (destiny.match('[\?]')){
				return destiny+'&'+ replaceText;
			}else{
				return destiny+'?'+replaceText;
			}
		}

		return destiny+'\n'+par+'\n'+par_value;
	}

});
</script>
</body>
</html>