<form id="uploadForm" method="post" action="{{ url('appcenter/app/doInstall') }}">
	<div class="table_full mb10">
			<table width="100%">
				<col class="th" />
				<tr>
					<th>选择文件</th>
					<td>
						<!--选择文件-->
						<div class="cc mb10">
							<div id="J_up_wrap" class="single_file_up">
								<a href="">上传文件</a>
								<input id="J_swfupload_btn" name="application" type="file">
							</div>
							<span id="J_up_tip" class="mr20" style="display:none;"></span>
							<span id="J_up_file" class="mr5" style="display:none;"></span>
							<a href="{{ url('appcenter/app/delFile') }}" id="J_up_del" style="display:none;">[删除]</a>
							<!-- <span class="tips_loading">正在上传</span>
							<span class="tips_loading">正在验证</span>
							<span class="tips_error">错误提示</span>
							<span class="tips_success">安装成功</span> -->
						</div>
						<button id="J_upload_btn" type="submit" class="btn btn_submit" style="visibility:hidden;">立即安装</button>
						<!--上传进度-->
						<div id="J_cc" style="display:none;">
							<div class="cc">
								<div class="install_schedule_bg fl mr10"><div id="J_install_schedule" class="install_schedule" style="width:0%;"><span></span></div></div><div id="J_percent" style="line-height:24px;">0%</div>
							</div>
							<div id="loadStep" class="install_load cc"></div>
						</div>
					</td>
				</tr>
			</table>
		<input type="hidden" name="file" id="J_file_input">
		</div>
</form>