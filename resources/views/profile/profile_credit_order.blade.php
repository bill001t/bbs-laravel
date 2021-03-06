<div class="content">
	@include('profile_credit_tab')

	<div class="content_type">
		<ul class="cc">
			<li><a href="{{ url('profile/credit/recharge') }}">积分充值</a></li>
			<li class="line"></li><li class="current"><a href="{{ url('profile/credit/order') }}">订单记录</a></li>
		</ul>
	</div>
	<div class="order_history">
		<table width="100%">
			<colgroup>
				<col width="65" />
				<col />
				<col width="140" />
				<col width="80" />
				<col width="100" />
			</colgroup>
			<thead>
				<tr>
					<td class="num">序号</td>
					<td>订单号</td>
					<td>充值金额（人民币）</td>
					<td>充值积分</td>
					<td>交易状态</td>
					<td>交易时间</td>
				</tr>
			</thead>

@if ($order)

				<!--# $i=0; #-->

@foreach ($order as $key => $value)

			<tr>
				<td class="num">{{ @++$i }}</td>
				<td>{{ $value['order_no'] }}</td>
				<td>{{ $value['price'] }} 元</td>
				<td>{{ $creditBo->cType[$value['buy']] }}</td>
				<td>
@if ($value['state'])
<span class="green">已完成</span>
@else
<span class="red">未完成</span><!--# } #--></td>
				<td>{{ App\Core\Tool::time2str($value['created_time']) }}</td>
			</tr>
				<!--# } #-->

@else

			<tr>
				<td colspan="6" class="tac">暂无订单记录</td>
			</tr>
			<!--# } #-->
		</table>
	</div>
	<page tpl="TPL:common.page" total="$totalpage" page="$page" per="$perpage" count="$count" url="profile/credit/order" />
	<!--订单结束-->
	
</div>
<script>
Wind.ready(document, function(){
	Wind.use('jquery', 'global');
});
</script>