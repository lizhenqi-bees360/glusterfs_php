
 	 <style type="text/css">
	    	.cluster-active{
			color: #dadada;
			background: #293846;
		}
		.cluster-active ul{
			height: auto;
		}
	</style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<div class="main clu-main" <?php if(!$ctr_con_show[0]) {echo ' style="display:none;"';}?> >
 			<form   class="clu-main-t" method="post" action="<?php echo site_url('cluster/auto_ping'); ?>">
 				<input type="text" name="begin_ip" class="form-control" placeholder="Begin IP" aria-describedby="basic-addon1" value="">
 				<input type="text" name="end_ip" class="form-control" placeholder="End IP" aria-describedby="basic-addon1" value="">
 				<button type="submit" class="btn btn-primary">范围探测</button>
 			</form>
 			<div class="clu-main-w">
 				<div  class="clu-main-w-con">
 					<div class="panel panel-default">
					      <!-- Default panel contents -->
					      <div class="panel-heading">网内主机列表</div>

					      <!-- Table -->
					      <table class="table">
					        <thead>
					          <tr>
					            <th>主机IP</th>
					            <th>状态</th>
					            <th>选择</th>
					          </tr>
					        </thead>
					        <tbody>
					        	<?php 
					        		foreach ($all_info['host_list'] as $row){
					        			echo "<tr>";
					        			echo "<td>".$row->ip."</td>";
					        			echo "<td>".$row->status."</td>";
					        			echo '<td><input type="checkbox" name="host_list_chk" value="'.$row->ip.'" aria-label=""></td>';
								   	echo "</tr>";
								}
					        	?>
					        </tbody>
					      </table>
					      <button onclick="hostAdd()" type="button" class="btn btn-primary  ml10">加入服务器</button>
					      <!-- <button type="button" class="btn btn-primary">自动加入</button> -->
					      <button onclick="hostDelete()" type="button" class="btn btn-danger">删除所选</button>
					       <!-- 单个探测 -->
					       <div>
					      		<button type="button" class="btn btn-primary mt16 ml10" data-toggle="modal" data-target="#siglePingModal" data-whatever="@mdo">单个探测</button>
					      		<div class="modal fade" id="siglePingModal" tabindex="-1" role="dialog" aria-labelledby="siglePingModalLabel">
							  <div class="modal-dialog" role="document">
							    <form class="modal-content"  method="post" action="<?php echo site_url('cluster/sigle_ping'); ?>">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							        <h4 class="modal-title" id="siglePingModalLabel">单个探测</h4>
							      </div>
							      <div class="modal-body">
								       <div class="form-group">
								            <label for="sigle-ping-ip" class="control-label">IP地址:</label>
								            <input type="text" class="form-control" id="sigle-ping-ip" name="sigle_ping_ip">
								        </div>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-primary">探测</button>
							      </div>
							    </form>
							  </div>
							</div>
					      </div>
					      <!-- 文件导入探测 -->
					      <div>
					      		<button type="button" class="btn btn-primary mt16 ml10" data-toggle="modal" data-target="#filePingModal" data-whatever="@mdo">文件导入</button>
					      		<div class="modal fade" id="filePingModal" tabindex="-1" role="dialog" aria-labelledby="filePingModalLabel">
							  <div class="modal-dialog" role="document">
							    <form  enctype="multipart/form-data" class="modal-content"  method="post" action="<?php echo site_url('cluster/file_ping'); ?>">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							        <h4 class="modal-title" id="filePingModalLabel">文件导入探测</h4>
							      </div>
							      <div class="modal-body">
								       <div class="form-group">
								              	<label>导入文件:</label>
							    			<input type="file" class="form-control-file" name="ping_file"  multiple>
								        </div>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-primary">探测</button>
							      </div>
							    </form>
							  </div>
							</div>
					      </div>
				   	 </div>
 				</div>
 				<div class="vol-main-w-gap"></div>
 				<div  class="clu-main-w-con">
 					<div class="panel panel-default">
					      <!-- Default panel contents -->
					      <div class="panel-heading">当前可用 gluster 服务器节点</div>

					      <!-- Table -->
					      <table class="table">
					        <thead>
					          <tr>
					            <th>主机IP</th>
					            <th>glusterfs版本</th>
					            <th>选择</th>
					          </tr>
					        </thead>
					        <tbody>
					          <?php 
					        		foreach ($all_info['gluster_list'] as $row){
					        			echo "<tr>";
					        			echo "<td>".$row->ip."</td>";
					        			echo "<td>".$row->status."</td>";
					        			echo '<td><input type="checkbox" name="gluster_list_chk" value="'.$row->ip.'" aria-label=""></td>';
								   	echo "</tr>";
								}
					        	?>
					        </tbody>
					      </table>
					      <div>
					     		<button onclick="glusterAdd()" type="button" class="btn btn-primary ml10">加入存储池</button>
					      </div>
					      <button onclick="glusterDelete()" type="button" class="btn btn-danger mt16 ml10">删除所选</button>
				   	 </div>
 				</div>
 				<div class="vol-main-w-gap"></div>
 				<div  class="clu-main-w-con">
 					<div class="panel panel-default">
					      <!-- Default panel contents -->
					      <div class="panel-heading">当前 gluster 存储池节点列表</div>

					      <!-- Table -->
					      <table class="table">
					        <thead>
					          <tr>
					            <th>主机IP</th>
					            <th>状态</th>
					            <th>选择</th>
					          </tr>
					        </thead>
					        <tbody>
					          <?php 
					        		foreach ($all_info['node_list'] as $row){
					        			echo "<tr>";
					        			echo "<td>".$row->ip."</td>";
					        			echo "<td>".$row->status."</td>";
					        			echo '<td><input type="checkbox" name="node_list_chk" value="'.$row->ip.'" aria-label=""></td>';
								   	echo "</tr>";
								}
					        	?>
					        </tbody>
					      </table>
					      <button onclick="nodeDelete()" type="button" class="btn btn-danger ml10">删除所选</button>
				   	 </div>
 				</div>
 			</div>
 		</div>
 		<div class="main clu-main" <?php if(!$ctr_con_show[1]) {echo ' style="display:none;"';}?> >
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#directory" aria-controls="directory" role="tab" data-toggle="tab">目录表</a></li>
			    <li role="presentation"><a href="#devices" aria-controls="devices" role="tab" data-toggle="tab">存储空间表</a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="directory">
					      <table class="table">
						        <thead>
						          <tr>
						            <th>主机名</th>
						            <th>输出目录个数</th>
						            <th>输出目录字符串</th>
						          </tr>
						        </thead>
						        <tbody>
						          	<?php 
						        		for($i = 0; $i < count($directory); $i++){
						        			echo "<tr>";
						        			echo "<td>".$directory[$i]["ip"]."</td>";
						        			echo "<td>".$directory[$i]["count"]."</td>";
						        			echo "<td>".$directory[$i]["name"]."</td>";
									   	echo "</tr>";
									}
						        	?>
						        </tbody>
					      </table>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="devices">
				    		<table class="table">
						        <thead>
						          <tr>
						            <th>主机名</th>
						            <th>目录位置</th>
						            <th>是否使用</th>
						          </tr>
						        </thead>
						        <tbody>
						          	<?php 
						        		for($i = 0; $i < count($storage_list); $i++){
						        			echo "<tr>";
						        			echo "<td>".$storage_list[$i]->ip."</td>";
						        			echo "<td>".$storage_list[$i]->place."</td>";
						        			echo "<td>".$storage_list[$i]->is_used."</td>";
									   	echo "</tr>";
									}
						        	?>
						        </tbody>
					      </table>
					       <!-- 新建存储空间 -->
					       <div>
					      		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#storageNewModal" data-whatever="@mdo">新建存储空间</button>
					      		<div class="modal fade" id="storageNewModal" tabindex="-1" role="dialog" aria-labelledby="storageNewModalLabel">
							  <div class="modal-dialog" role="document">
							    <form class="modal-content" style="margin-bottom:0;" method="post" action="<?php echo site_url('cluster/storage_new'); ?>">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							        <h4 class="modal-title" id="storageNewModalLabel">新建存储空间</h4>
							      </div>
							      <div class="modal-body">
								       <div class="form-group">
								            <label class="control-label">IP地址:</label>
								            <select name="storage_ip" class="form-control">
								            		<?php 
									        		for($i = 0; $i < count($directory); $i++){
									        			echo '<option value="'.$directory[$i]["ip"].'">'.$directory[$i]["ip"].'</option>';
												}
									        	?>
								            </select>
								            <label class="control-label">目录位置:</label>
								            <input type="text" placeholder="/data/brick1/gv0" class="form-control" id="storage_place" name="storage_place">
								        </div>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-primary">新建</button>
							      </div>
							    </form>
							  </div>
							</div>
					      </div>
				    </div>
			  </div>

 		</div>
 	</div>
 </body>
<script type="text/javascript">
	var domain = "<?php echo base_url() ?>index.php";
	function getCheckSel (type) {
		var chk_value =''; 
		var checkedArr = [];
		if(type === 'host'){
			checkedArr = $('input[name="host_list_chk"]:checked');
		}else if(type === 'gluster'){
			checkedArr = $('input[name="gluster_list_chk"]:checked');
		}else if(type === 'node'){
			checkedArr = $('input[name="node_list_chk"]:checked');
		}else{}

		checkedArr.each(function(){
			chk_value +=  $(this).val() + ',';
		});
		if(!chk_value.length){
			window.alert('你还没有选择任何内容！');
			return false;
		}
		return chk_value.substring(0, chk_value.length-1);
	}
	function hostAdd () {
		var ip_list = getCheckSel('host');
		if(!ip_list){return;}
		var data = {"ip_list": ip_list};
		var url = domain +  "/cluster/host_add";
		var type = "POST";
		sendData(data, type, url, function(backData){
			console.log(backData);
			if(backData.code === 'failed'){
				window.alert('加入失败！  ' + backData.msg);
			}else{
				window.location.reload(true);
			}
		});
	}
	function hostDelete () {
		var ip_list = getCheckSel('host');
		if(!ip_list){return;}
		var data = {"ip_list": ip_list};
		var url = domain +  "/cluster/host_delete";
		var type = "POST";
		sendData(data, type, url, function(backData){
			console.log(backData);
			if(backData.code === 'failed'){
				window.alert('删除失败！  ' + backData.msg);
			}else{
				window.location.reload(true);
			}
		});
	}
	function glusterAdd () {
		var ip_list = getCheckSel('gluster');
		if(!ip_list){return;}
		var data = {"ip_list": ip_list};
		var url = domain +  "/cluster/gluster_add";
		var type = "POST";
		sendData(data, type, url, function(backData){
			console.log(backData);
			if(backData.code === 'failed'){
				window.alert('加入失败！  ' + backData.msg);
			}else{
				window.location.reload(true);
			}
		});
	}
	function glusterDelete () {
		var ip_list = getCheckSel('gluster');
		if(!ip_list){return;}
		var data = {"ip_list": ip_list};
		var url = domain +  "/cluster/gluster_delete";
		var type = "POST";
		sendData(data, type, url, function(backData){
			console.log(backData);
			if(backData.code === 'failed'){
				window.alert('删除失败！  ' + backData.msg);
			}else{
				window.location.reload(true);
			}
		});
	}
	function nodeDelete () {
		var ip_list = getCheckSel('node');
		if(!ip_list){return;}
		var data = {"ip_list": ip_list};
		var url = domain + "/cluster/node_delete";
		var type = "POST";
		sendData(data, type, url, function(backData){
			console.log(backData);
			if(backData.code === 'failed'){
				window.alert('删除失败！  ' + backData.msg);
			}else{
				window.location.reload(true);
			}
		});
	}
	function sendData(data, type, url, callback){
		$.ajax({
		    "type": type,
		    "url": url,
		    "data": data,
		    "timeout" : 3600000,//1 hour
		    "dataType": "json",
		    "success": function(backData){   //成功之后执行的函数
		    		console.log("success");
				callback(backData);
		    },
		    "error": function(backData){
		    		console.log("error");
		    		var data={code:"222", msg:''};
		    		callback(data);
		    }
		});
	}
</script>
</html>