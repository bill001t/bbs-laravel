<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body style="width:330px;background:#fff;">
<div id="J_move_pop" class="">
	<form class="J_ajaxForm" action="{{ url('/tag/manage/domove') }}" method="post" >
		<div class="core_pop">
			<div>
				<div class="pop_cont">
					<p class="mb10">将 <span class="J_move_name"></span></p>
					<p>移动到：<select size="5" style="vertical-align:top;" class="select_4" name="category_ids[]" multiple>
						<option value="0">无</option>

@foreach($categories as $v)

						<option value="{{ $v['category_id'] }}">{{ $v['category_name'] }}</option>
						<!--# } #-->
					</select>
					</p>
				</div>
				<div class="pop_bottom">
					<input class="J_tag_id" type="hidden" name="tag_id" />
					<button type="submit" class="btn btn_submit J_ajax_submit_btn">提交</button>
				</div>
			</div>
		</div>
	</form>
</div>
@include('admin.common.footer')
<script>
$(function(){
	var move_pop = $('#J_move_pop'),
		checked = $(parent.document.body).find('input.J_check:checked'),
		name_arr = [],
		tid_arr = [];
		
		$.each(checked, function(i, o){
			var $this = $(this);
			name_arr.push('<span class="b green">'+ $(this).data('name') +'</span>');
			tid_arr.push($this.data('tid'));
		});
			
		move_pop.find('.J_move_name').html(name_arr.join('、'));
		move_pop.find('input.J_tag_id').val(tid_arr.join(','));

});
</script>
</body>
</html>