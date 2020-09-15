<?php include(PUBLIC_PATH . "template/install/header.htm"); ?>
<section class="section">
	<div class="step">
		<ul>
			<li class="current"><em>1</em>检测环境</li>
			<li><em>2</em>创建数据</li>
			<li><em>3</em>完成安装</li>
		</ul>
	</div>
	<div class="server">
		<table width="100%">
			<tr>
				<td class="td1">环境检测</td>
				<td class="td1" width="25%">推荐配置</td>
				<td class="td1" width="25%">当前状态</td>
				<td class="td1" width="25%">最低要求</td>
			</tr>
			
			<tr>
				<td>操作系统</td>
				<td><?php echo $recommendEnvironment['os'];?></td>
				<td>
<?php if (true ==  $currentEnvironment['os_ischeck']) {?><span class="correct_span">&radic;</span>
<?php }else{?><span class="error_span">&times;</span>
<?php }
 echo $currentEnvironment[os];?></td>
				<td><?php echo $lowestEnvironment['os'];?></td>
			</tr>
			<tr>
				<td>PHP版本</td>
				<td><?php echo $recommendEnvironment['version'];?></td>
				<td>
<?php if (true ==  $currentEnvironment['version_ischeck']) {?><span class="correct_span">&radic;</span>
<?php }else{?><span class="error_span">&times;</span>
<?php }
 echo $currentEnvironment[version];?></td>
				<td><?php echo $lowestEnvironment['version'];?></td>
			</tr>
			<tr>
				<td>Mysql版本（client）</td>
				<td><?php echo $recommendEnvironment['mysql'];?></td>
				<td>
<?php if (true ==  $currentEnvironment['mysql_ischeck']) {?><span class="correct_span">&radic;</span>
<?php }else{?><span class="error_span">&times;</span>
<?php }
 echo $currentEnvironment[mysql];?></td>
				<td><?php echo $lowestEnvironment['mysql'];?></td>
			</tr>
			<tr>
				<td>PDO_Mysql</td>
				<td><?php echo $recommendEnvironment['pdo_mysql'];?></td>
				<td>
<?php if (true ==  $currentEnvironment['pdo_mysql_ischeck']) {?><span class="correct_span">&radic;</span>已安装
<?php }else{?><span class="error_span">&times;</span>未安装
<?php }?></td>
				<td><?php echo $lowestEnvironment['pdo_mysql'];?></td>
			</tr>
			<tr>
				<td>附件上传</td>
				<td><?php echo $recommendEnvironment['upload'];?></td>
				<td>
<?php if (true ==  $currentEnvironment['upload_ischeck']) {?><span class="correct_span">&radic;</span>
<?php }else{?><span class="error_span">&times;</span>
<?php }
 echo $currentEnvironment[upload];?></td>
				<td><?php echo $lowestEnvironment['upload'];?></td>
			</tr>
			<tr>
				<td>磁盘空间</td>
				<td><?php echo $recommendEnvironment['space'];?></td>
				<td>
<?php if (true ==  $currentEnvironment['space_ischeck']) {?><span class="correct_span">&radic;</span>
<?php }else{?><span class="error_span">&times;</span>
<?php }
 echo $currentEnvironment[space];?></td>
				<td><?php echo $lowestEnvironment['space'];?></td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td class="td1">目录、文件权限检查</td>
				<td class="td1" width="25%">当前状态</td>
				<td class="td1" width="25%">所需状态</td>
			</tr>
<?php foreach ($writeAble as $key=>$value){ ?>
			<tr>
				<td><?php echo $key;?></td>
				<td>
<?php if (false == $value) {?> <span class="error_span">&times;</span>不可写或不存在
<?php }else{?> <span class="correct_span">&radic;</span>可写
<?php }?></td>
				<td>可写</td>
			</tr>
<?php }?>
		</table>
	</div>
	<div class="bottom tac">
		<a href="<?php echo WindUrlHelper::createUrl('index/check');?>" class="btn">重新检测</a><a href="<?php echo WindUrlHelper::createUrl('index/info');?>" class="btn">下一步</a>
	</div>
</section>

<?php include(PUBLIC_PATH . "template/install/footer.htm"); ?>