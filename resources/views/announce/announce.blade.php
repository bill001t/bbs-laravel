
@if($__tpl_data)

<div class="core_announce_wrap">
	<div class="core_announce">
		<ul class="J_slide_auto cc">

@foreach($__tpl_data as $key => $value)
if($value['typeid']){
		 	$url = $value['url']; 
		 	$showTarget = 'target=_blank';
		 }else{
		 	$url = WindUrlHelper::createUrl('announce/index/run',array('aid'=>$key)); 
		 	$showTarget='target=_self';
		 }
		 #-->
		 <li><a href="{{ $url|url }}"{{ $showTarget }}>{{ $value['subject'] }}</a><span>{{ $value['start_date'] }}</span></li>
		 <!--#
		 } #-->
		</ul>
	</div>
</div>
<!--# } #-->