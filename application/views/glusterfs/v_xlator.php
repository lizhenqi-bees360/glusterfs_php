
         <style type="text/css">
                .xlator-active{
                    color: #dadada;
                    background: #293846;
                }
                .xlator-active ul{
                    height: auto;
                }
        </style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<!-- performance  -->
 		<div class="main vol-main" >
 			<div class="vol-main-t">
 				自定义Xlator
 			</div> 
 			<div class="vol-main-w mt20">
 				<div class="vol-main-w-con">
 					<table class="table">
						        <thead>
						          <tr>
						            <th>Xlator名字</th>
						            <th>开启指令</th>
						            <th>关闭指令</th>
						            <th>操作</th>
						          </tr>
						        </thead>
						        <tbody>
						        	<?php
						        		for ($i=0; $i < count($xlator_list); $i++) { 
						        			echo '<tr>';
						        			echo '<td>'.$xlator_list[$i]->name.'</td>';
						        			echo '<td>';
						        			for ($j=0; $j < count($xlator_list[$i]->open_command); $j++) { 
						        				echo $xlator_list[$i]->open_command[$j].'<br>';
						        			}
						        			echo '</td>';
						        			echo '<td>';
						        			for ($j=0; $j < count($xlator_list[$i]->close_command); $j++) { 
						        				echo $xlator_list[$i]->close_command[$j].'<br>';
						        			}
						        			echo '</td>';
						        			echo '<td style="min-width:200px;">';
						        			echo '<button type="button" class="btn btn-primary"><a href="'.site_url('xlator/xlator_do/open/'.$xlator_list[$i]->name).'">开启</a></button>';
						        			echo '<button type="button" class="btn btn-default ml10"><a href="'.site_url('xlator/xlator_do/close/'.$xlator_list[$i]->name).'">关闭</a></button>';
						        			echo '<button type="button" class="btn btn-danger ml10"><a href="'.site_url('xlator/delete/'.$xlator_list[$i]->name).'">删除</a></button>';
						        			echo '</td>';
						        		}
						        	 ?>
						        </tbody>
					      </table>
					      <!-- 新建指令 -->
					       <div>
					      		<button type="button" class="btn btn-primary ml20 mb20" data-toggle="modal" data-target="#addXlatorModal" data-whatever="@mdo">新建指令</button>
					      		<div class="modal fade" id="addXlatorModal" tabindex="-1" role="dialog" aria-labelledby="addXlatorModalLabel">
								  <div class="modal-dialog" role="document">
									    <form class="modal-content"  method="post" action="<?php echo site_url('xlator/add_xlator'); ?>">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="addXlatorModalLabel">新建指令</h4>
										      </div>
										      <div class="modal-body">
											       <div class="form-group">
											            <label for="brick_dir" class="control-label">Xlator名字:</label>
											            <input type="text" class="form-control" name="name">
											        </div>
											         <div class="form-group">
											            <label for="brick_dir" class="control-label">开启指令:</label>
											            <textarea type="text" class="form-control" name="open_command" placeholder="请与逗号分隔"></textarea>
											        </div>
											         <div class="form-group">
											            <label for="brick_dir" class="control-label">关闭指令:</label>
											            <textarea type="text" class="form-control" name="close_command" placeholder="请与逗号分隔"></textarea>
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

 	</div>
 </body>
</html>