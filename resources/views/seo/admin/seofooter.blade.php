<div class="pop_seo" id="J_pop_seo" style="display:none">
	<div class="hd">
		<a href="#" class="close J_pop_close">关闭</a>
			<strong>可以使用的代码（点击插入）：</strong>
		</div>
	<div class="ct" id="J_seo_code"></div>
</div>
@include('admin.common.footer')
<script type="text/javascript">
$(function(){
	//展开显示
	$('span.J_toggle_row').on('click', function(){
		var $this = $(this),
			list = $this.parent().nextAll('.J_child_wrap').filter(':first');

		if($this.hasClass('start_icon')) {
			list.hide();
			$this.removeClass('start_icon').addClass('away_icon');
		}else{
			list.show();
			$this.removeClass('away_icon').addClass('start_icon');
		}
	});
	
	//代码提示
	var SEO_CODE = {$codes|json},
		pop_seo = $('#J_pop_seo'),
		timer;
	
	$('input.J_seo_input').on('focus', function(){
		//聚焦
		var $this = $(this),
			id = $this.data('id');
		
		//先撤销原有的id
		$('#J_focus_input').removeAttr('id');
		$this.attr('id', 'J_focus_input');
		
		if(timer) {
			clearTimeout(timer);
			pop_seo.hide();
		}
		
		try{
			var seo_arr = [];
			$.each(SEO_CODE[id], function(i, o){
				seo_arr.push('<a class="J_insert_input" href="#">'+ o +'</a>');
			});

			if(!seo_arr.length) {
				return false;
			}

			$('#J_seo_code').html(seo_arr.join(''));

			//显示窗口并定位
			pop_seo.show().css({
				left : $this.offset().left + $this.outerWidth(),
				top : $this.offset().top - pop_seo.outerHeight() + $this.outerHeight()
			});

		}catch(err){
			pop_seo.hide();
		}
		
		
	}).on('blur', function(){
		//失焦
		var $this = $(this);
		timer = setTimeout(function(){
			pop_seo.hide();
			
		}, 150);
	});
	
	//点击关闭
	pop_seo.on('click', 'a.J_pop_close', function(e){
		e.preventDefault();
		pop_seo.hide();
	});
	
	//点击代码
	pop_seo.on('click', 'a.J_insert_input', function(e){
		e.preventDefault();
		clearTimeout(timer);
		
		var input = $('#J_focus_input'),
			v = input.val() +' '+ $(this).text();
			
		input.val(v).focus();
	});
	
	//点击弹窗区域
	pop_seo.on('click', function(){
		clearTimeout(timer);
	});

});
</script>