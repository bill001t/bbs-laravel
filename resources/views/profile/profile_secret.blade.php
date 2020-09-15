<div class="content">
	<div class="profile_nav">
		<ul>
			<li class="current"><a href="{{ url('profile/secret/run?_left=secret') }}">空间隐私</a></li>
			<li><a href="{{ url('profile/secret/black?_left=secret') }}">黑名单</a></li>
		</ul>
	</div>
	<form id="J_secret_form" action="{{ url('profile/secret/dorun') }}" method="post">
	<div class="profile_ct">
		<div class="tips mb15"><span class="tips_icon">设置可以浏览我空间的人，能查看我的哪些资料</span></div>
		<dl class="cc">
			<dt>谁能浏览我的空间：</dt>
			<dd>
				<select class="select_5" name="space">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['space']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
		  </dd>
		</dl>
		<h3>基本资料</h3>
		<dl class="cc">
			<dt>星座：</dt>
			<dd>
				<select class="select_5" name="constellation">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['constellation']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<dl class="cc">
			<dt>现居住地：</dt>
			<dd>
				<select class="select_5" name="local">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['local']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<dl class="cc">
			<dt>家乡：</dt>
			<dd>
				<select class="select_5" name="nation">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['nation']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<h3>联系方式</h3>
		<dl class="cc">
			<dt>阿里旺旺：</dt>
			<dd>
				<select class="select_5" name="aliwangwang">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['aliwangwang']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<dl class="cc">
			<dt>QQ：</dt>
			<dd>
				<select class="select_5" name="qq">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['qq']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<dl class="cc">
			<dt>MSN：</dt>
			<dd>
				<select class="select_5" name="msn">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['msn']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<dl class="cc">
			<dt>手机：</dt>
			<dd>
				<select class="select_5" name="mobile">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret['mobile']) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
	
		<h3>其它</h3>

@foreach($model AS $k=>$m)

		<dl class="cc">
			<dt>{{ $m['title'] }}：</dt>
			<dd>
				<select class="select_5" name="{{ $k }}">

@foreach($option AS $key=>$value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $secret[$k]) }}>{{ $value }}</option>
				<!--# } #-->
				</select>
			</dd>
		</dl>
		<!--# } #-->
		
		<dl class="cc">
			<dt>&nbsp;</dt>
			<dd><button type="submit" class="btn btn_submit btn_big">提交</button></dd>
		</dl>
	</div>
	</form>

</div>
<script>
Wind.ready(document, function(){
	Wind.use('jquery', 'global', 'ajaxForm', function(){
		//隐私提交
		$('#J_secret_form').ajaxForm({
			dataType : 'json',
			success : function(data){
				if(data.state == 'success') {
					Wind.Util.resultTip({
						msg : '修改成功'
					})
				}else if(data.state == 'fail') {
					Wind.Util.resultTip({
						error : true,
						msg : data.message
					});
				}
			}
		});
	});
});
</script>