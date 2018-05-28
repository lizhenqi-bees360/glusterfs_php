
         <style type="text/css">
                .breakdown-active{
                    color: #dadada;
                    background: #293846;
                }
                .breakdown-active ul{
                    height: auto;
                }
        </style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<div class="main vol-main">
 				<div  class="vol-main-w">
				      		<div class="vol-main-w-con" style="max-width:200px;">
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
							  					echo '<a href="'.site_url("breakdown/volume_info/$vname_arr[$i]").'">'.$vname_arr[$i].'</a>';
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
							  	<span class="vol-main-w-list">卷的状态: 
							  		<?php echo $tar_volume->status_str; ?>
							  	</span>
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
							  <div class="panel-heading">卷的brick</div>
							  <form class="panel-body" style="margin-bottom:0;" method="post" action="<?php echo site_url('breakdown/replace'); ?>">
							  	<input style="display:none;" type="text" name="volume_name" value="<?=$volume_name?>">
							  	<?php 
							  		for ($i=0; $i < count($brick_arr); $i++) { 
							  			echo '<span class="vol-main-w-list breakdown-main-w-list">'.$brick_arr[$i];
							  			echo '<input name="brick_name" style="margin-left:20px;" type="radio" value="'.$brick_arr[$i].'">';
							  			echo '</span>';
							  		}
							  	?>
							  	 <!--替换 brick -->
							       <div>
							      		<button type="button" class="btn btn-primary ml20" data-toggle="modal" data-target="#brickReplaceModal" data-whatever="@mdo">替换所选</button>
							      		<div class="modal fade" id="brickReplaceModal" tabindex="-1" role="dialog" aria-labelledby="brickReplaceModalLabel">
									  <div class="modal-dialog" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									        <h4 class="modal-title" id="brickReplaceModalLabel">替换 brick </h4>
									      </div>
									      <div class="modal-body">
										       <div class="form-group">
										            <label class="control-label">目标块:</label>
										            <select name="storage_id" class="form-control">
										            		<?php 
											        		for($i = 0; $i < count($storage_list); $i++){
											        			echo '<option value="'.$storage_list[$i]->id.'">'.$storage_list[$i]->id.'</option>';
														}
											        	?>
										            </select>
										        </div>
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									        <button type="submit" class="btn btn-primary">替换</button>
									      </div>
									    </div>
									  </div>
									</div>
							      </div>
							  </form>
							</div>
		 				</div>
				      	</div>
 		</div>
 	</div>
 </body>
</html>