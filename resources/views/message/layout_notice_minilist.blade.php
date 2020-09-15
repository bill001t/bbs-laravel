<!doctype html>
<html>
<head>
@include('common.head')
</head>
<body style="background:#fff">
<div id="J_head_msg" class="my_message_content">
	<!--#$this->content();#-->
</div>
<script>
Wind.use('jquery', 'global','scrollFixed','ajaxForm', function(){
	Wind.js(GV.JS_ROOT +'pages/common/headMsg.js?v='+ GV.JS_VERSION, function(){
		headMsg();
	});
});
</script>
</body>
</html>