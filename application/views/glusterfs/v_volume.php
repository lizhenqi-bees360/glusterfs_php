	<!-- important, dont move -->
 	<style type="text/css">
    	.volume-active{
		color: #dadada;
		background: #293846;
	}
	.volume-active ul{
		height: auto;
	}
    </style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<!-- volume look -->
 		<div class="main vol-main" <?php if(!$ctr_con_show[0]) {echo ' style="display:none;"';}?> >
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<button type="button" class="btn btn-primary" style="float:right; margin:-1px 0 0 20px;">
 					<a   href="<?php echo site_url('volume/volume_refresh'); ?>">
						 刷新
					 </a>
 				</button>
				<li role="presentation" class="active"><a href="#basic-info" aria-controls="basic-info" role="tab" data-toggle="tab">基本信息</a></li>
				<li role="presentation"><a href="#detail-info" aria-controls="detail-info" role="tab" data-toggle="tab">详细信息</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
			    	<div role="tabpanel" class="tab-pane active" id="basic-info">
				      	<div  class="vol-main-w">
				      		<div class="vol-main-w-con">
		 					<div class="panel panel-default">
							  	<div class="panel-heading">卷列表</div>
							  	<div class="panel-body">
							  		<?php 
							  			for ($i=0; $i < count($vname_arr); $i++) { 
							  				if($i == $tar_index){
							  					echo '<span class="vol-main-w-list">';
							  					echo '<a href="javascript:;">'.$vname_arr[$i].' (当前卷)</a>';
							  					echo '</span>';
							  				}else{
							  					echo '<span class="vol-main-w-list">';
							  					echo '<a href="'.site_url("volume/volume_info/$vname_arr[$i]").'">'.$vname_arr[$i].'</a>';
							  					echo '</span>';
							  				}
							  			}
							  		?>
							  	</div>
							</div>
		 				</div>
		 				<div class="vol-main-w-gap"></div>
		 				<div class="vol-main-w-con">
		 					<div class="panel panel-default">
							  <div class="panel-heading">卷的brick</div>
							  <div class="panel-body">
							  	<?php 
							  		for ($i=0; $i < count($brick_arr); $i++) { 
							  			echo '<span class="vol-main-w-list">'.$brick_arr[$i].'</span>';
							  		}
							  	?>
							  </div>
							</div>
		 				</div>
		 				<div class="vol-main-w-gap"></div>
		 				<div class="vol-main-w-con">
		 					<div class="panel panel-default">
							  <div class="panel-heading">卷的参数</div>
							  <div class="panel-body">
							    	<span class="vol-main-w-list">type:
							    		<?php echo $tar_volume->type_str; ?>
							    	</span>
							    	<span class="vol-main-w-list">brick num:
							    		<?php echo $tar_volume->brick_count; ?>
							    	</span>
							    	<span class="vol-main-w-list">disk num: <?php echo $tar_volume->dist_count; ?></span>
							    	<span class="vol-main-w-list">strip num: <?php echo $tar_volume->stripe_count; ?></span>
							    	<span class="vol-main-w-list">replica num: <?php echo $tar_volume->replica_count; ?></span>
							    	<span class="vol-main-w-list">传输协议: <?php echo $tar_volume->transport; ?></span>
							  </div>
							</div>
		 				</div>
		 				<div class="vol-main-w-gap"></div>
		 				<div class="vol-main-w-con">
		 					<div class="panel panel-default">
							  <div class="panel-heading">卷的状态</div>
							  <div class="panel-body">
							    	<span class="vol-main-w-list"><?php echo $tar_volume->status_str; ?></span>
							  </div>
							</div>
		 				</div>
				      	</div>
			    	</div>
			    <div role="tabpanel" class="tab-pane" id="detail-info">
			    		<div  class="vol-main-w">
				      		<div class="vol-main-w-con">
		 					<div class="panel panel-default">
		 						<div class="panel-heading">
		 						<?php echo $volume_name; ?>
		 						卷的详细信息</div>
							  	<div class="panel-body">
							  		<?php 
							  		for ($i=0; $i < count($detail_info1); $i++) { 
							  			echo '<span class="vol-main-w-list" title="'.$detail_info1[$i]['option'].': '.$detail_info1[$i]['value'].'">';
							  			echo $detail_info1[$i]['option'].': '.$detail_info1[$i]['value'];
							  			echo '</span>';
							  		}
							  	?>
							  	</div>
							</div>
		 				</div>
		 				<div class="vol-main-w-gap"></div>
		 				<div class="vol-main-w-con">
		 					<div class="panel panel-default">
							  <div class="panel-body">
							  	<?php 
							  		for ($i=0; $i < count($detail_info2); $i++) { 
							  			echo '<span class="vol-main-w-list" title="'.$detail_info2[$i]['option'].': '.$detail_info2[$i]['value'].'">';
							  			echo $detail_info2[$i]['option'].': '.$detail_info2[$i]['value'];
							  			echo '</span>';
							  		}
							  	?>
							  </div>
							</div>
		 				</div>
				      	</div>
			    </div>
			</div>
 		</div>

 		<!-- volume add -->
 		<div class="main vol-main" <?php if(!$ctr_con_show[1]) {echo ' style="display:none;"';}?> >
 			<div class="vol-main-t">新建卷</div> 
 			<form class="vol-add-form" method="post" action="<?php echo site_url('volume/add'); ?>">
				<div class="vol-add-form-row">
					<span>卷的名字</span>
					<input class=" vol-add-form-r" type="text" name="name">
				</div>
				<div class="vol-add-form-row">
					<span>卷的类型</span>
					<div class="mr20 vol-add-form-r">
						<input type="radio" name="type_str" value="distributed">
						分布式卷
					</div>
					<div class="mr20  vol-add-form-r">
						<input type="radio" name="type_str" value="replica">
						复制卷
					</div>
					<div class="mr20  vol-add-form-r">
						<input type="radio" name="type_str" value="stripe">
						条带化卷
					</div>
				</div>
				<div class="vol-add-form-row">
					<span>brick的数量</span>
					<input class=" vol-add-form-r" type="text" name="brick_count">
				</div>
				<div class="vol-add-form-row">
					<span>主机选择</span>
					<div class="vol-add-form-r">
						<?php
							for($i = 0; $i < count($storage_list); $i++){
							 	echo '<div class="mr20">';
							 	echo '<input type="checkbox" name="ip_list[]" value="'.$storage_list[$i]->id.'">';
							 	echo $storage_list[$i]->id;
							 	echo "</div>";
							 } 
						?>
					</div>
				</div>
				<div class="vol-add-form-row">
					<span>传输协议</span>
					<div class="mr20  vol-add-form-r">
						<input type="radio" name="transport" value="tcp">
						tcp
					</div>
					<div class="mr20  vol-add-form-r">
						<input type="radio" name="transport" value="rdma">
						rdma
					</div>
					<div class="mr20  vol-add-form-r">
						<input type="radio" name="transport" value="tcprdma">
						tcp, rdma
					</div>
				</div>
				<button type="submit" class="btn btn-primary">新建</button>
 			</form>
 		</div>
 		<!-- volume update -->
 		<div class="main vol-main" <?php if(!$ctr_con_show[2]) {echo ' style="display:none;"';}?> >
 			<div class="vol-main-t">
 				修改卷
 			</div> 
 			<div class="vol-main-w mt20">
 				<div class="vol-main-w-con">
 					<div class="panel panel-default">
					  	<div class="panel-heading">卷列表</div>
					  	<div class="panel-body">
					  		<?php 
					  			for ($i=0; $i < count($vname_arr); $i++) { 
					  				if($i == $tar_index){
					  					echo '<span class="vol-main-w-list">';
					  					echo '<a href="javascript:;">'.$vname_arr[$i].' (当前卷)</a>';
					  					echo '</span>';
					  				}else{
					  					echo '<span class="vol-main-w-list">';
					  					echo '<a href="'.site_url("volume/update/$vname_arr[$i]").'">'.$vname_arr[$i].'</a>';
					  					echo '</span>';
					  				}
					  			}
					  		?>
					  	</div>
					</div>
 				</div>
 				<div class="vol-main-w-gap"></div>
 				<div class="vol-main-w-con" style="max-width:200px;">
 					<div class="panel panel-default">
					  <div class="panel-heading">卷的参数</div>
					  <div class="panel-body">
					    	<span class="vol-main-w-list">type:
					    		<?php echo $tar_volume->type_str; ?>
					    	</span>
					    	<span class="vol-main-w-list">brick num:
					    		<?php echo $tar_volume->brick_count; ?>
					    	</span>
					    	<span class="vol-main-w-list">disk num: <?php echo $tar_volume->dist_count; ?></span>
					    	<span class="vol-main-w-list">strip num: <?php echo $tar_volume->stripe_count; ?></span>
					    	<span class="vol-main-w-list">replica num: <?php echo $tar_volume->replica_count; ?></span>
					    	<span class="vol-main-w-list">传输协议: <?php echo $tar_volume->transport; ?></span>
					  </div>
					</div>
 				</div>
 				<div class="vol-main-w-gap"></div>
 				<div class="vol-main-w-con">
 					<div class="panel panel-default">
					  	<div class="panel-heading">卷的容量修改</div>
					  	<div class="panel-body">
						  	<form method="post" action="<?php echo site_url('volume/reduce_dir'); ?>">
						  		<?php 
							  		for ($i=0; $i < count($brick_arr); $i++) { 
							  			echo '<span class="vol-main-w-list">';
							  			echo '<input type="checkbox" name="brick_list[]" value="'.$brick_arr[$i].'" >';
							  			echo '&nbsp&nbsp';
							  			echo  $brick_arr[$i];
							  			echo '</span>';
							  		}
							  	?>
							  	<input style="display:none;" type="text" name="volume_name" value="<?=$volume_name?>">
							  	<button type="submit" class="btn btn-danger mt16 ml20">删除所选</button>
						  	</form>
					  	
						  	<!-- 增加目录 -->
						       <div>
						      		<button type="button" class="btn btn-primary ml20 mt16" data-toggle="modal" data-target="#addDirModal" data-whatever="@mdo">增加目录</button>
						      		<div class="modal fade" id="addDirModal" tabindex="-1" role="dialog" aria-labelledby="addDirModalLabel">
									  <div class="modal-dialog" role="document">
										    <form class="modal-content"  method="post" action="<?php echo site_url('volume/add_dir'); ?>">
											      <div class="modal-header">
											        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											        <h4 class="modal-title" id="addDirModalLabel">增加目录</h4>
											      </div>
											      <div class="modal-body">
												       <div class="form-group">
												            <label for="brick_dir" class="control-label">选择brick目录:</label>
													            <?php
																for($i = 0; $i < count($storage_list); $i++){
																 	echo '<div class="mr20">';
																 	echo '<input type="checkbox" name="brick_dir[]" value="'.$storage_list[$i]->id.'">';
																 	echo $storage_list[$i]->id;
																 	echo "</div>";
																 } 
																 if(!count($storage_list)){
																 	echo "<p>没有存储目录，请创建！</p>";
																 }
															?>
												            <input style="display:none;" type="text" name="volume_name" value="<?=$volume_name?>">
												        </div>
											      </div>
											      <div class="modal-footer">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											        <button type="submit" class="btn btn-primary">增加</button>
											      </div>
										    </form>
									 </div>
								</div>
						      	</div>							  	
					  	</div>
					</div>
 				</div>
 				<div class="vol-main-w-gap"></div>
 				<div class="vol-main-w-con">
 					<div class="panel panel-default">
					  <div class="panel-heading">卷的状态修改</div>
					  <div class="panel-body">
					  	<p class="mb20">目前状态: <?php echo $tar_volume->status_str; ?></p>
					  	<button type="button" class="btn btn-primary">
					    			<a href="<?php echo site_url('volume/update_status/'.$volume_name.'/start'); ?>">启动</a>
				    		</button>
				    		<button type="button" class="btn btn-primary ml10">
					    			<a href="<?php echo site_url('volume/update_status/'.$volume_name.'/stop'); ?>">停止</a>
				    		</button>
				    		<button type="button" class="btn btn-danger ml10">
					    			<a href="<?php echo site_url('volume/update_status/'.$volume_name.'/delete'); ?>">删除</a>
				    		</button>
					    	
					  </div>
					</div>
 				</div>
		      	</div>
 		</div>

 		<!-- volume replace -->
 		<div class="main vol-main" <?php if(!$ctr_con_show[3]) {echo ' style="display:none;"';}?> >
 			<div class="vol-main-t">
 				迁移卷
 			</div> 
 			<div class="vol-main-w mt20">
 				<div class="vol-main-w-con">
 					<div class="panel panel-default">
					  	<div class="panel-heading">卷列表</div>
					  	<div class="panel-body">
					  		<?php 
					  			for ($i=0; $i < count($vname_arr); $i++) { 
					  				if($i == $tar_index){
					  					echo '<span class="vol-main-w-list">';
					  					echo '<a href="javascript:;">'.$vname_arr[$i].' (当前卷)</a>';
					  					echo '</span>';
					  				}else{
					  					echo '<span class="vol-main-w-list">';
					  					echo '<a href="'.site_url("volume/replace/$vname_arr[$i]").'">'.$vname_arr[$i].'</a>';
					  					echo '</span>';
					  				}
					  			}
					  		?>
					  	</div>
					</div>
 				</div>
 				<div class="vol-main-w-gap"></div>
 				<div class="vol-main-w-con">
 					<div class="panel panel-default">
					  	<div class="panel-heading">卷的迁移</div>
					  	<div class="panel-body" style="padding:20px;">
						  	<form method="post" action="<?php echo site_url('volume/replace/'.$volume_name); ?>">
						  		<div class="form-group">
							            <label class="control-label">迁移位置:</label>
							            <?php
										for($i = 0; $i < count($storage_list); $i++){
										 	echo '<div class="mr20">';
										 	echo '<input type="checkbox" name="repalce_list[]" value="'.$storage_list[$i]->id.'">';
										 	echo $storage_list[$i]->id;
										 	echo "</div>";
										 } 
									?>
							            <!-- <input type="text" name="repalce_dir"> -->
							            <input style="display:none;" type="text" name="volume_name" value="<?=$volume_name?>">
							       </div>
							  	<button type="submit" class="btn btn-primary mt16">迁移</button>
						  	</form>
					      	</div>							  	
				  	</div>
				</div>
			</div>
 		</div>

 		<!-- volume none -->
 		<div class="main vol-main" <?php if(!$ctr_con_show[4]) {echo ' style="display:none;"';}?> >
 			
 		</div>
 	</div>
 </body>
</html>