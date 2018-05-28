
         <style type="text/css">
                .account-active{
                    color: #dadada;
                    background: #293846;
                }
                .account-active ul{
                    height: auto;
                }
        </style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<form  <?php if(!$ctr_con_show[0]) {echo ' style="display:none;"';}?> class="main account-main" method="post" action="<?php echo site_url('account/delete'); ?>">
 			  <table class="table">
			        <thead>
			          <tr>
			            <th>帐号名</th>
			            <th>角色</th>
			            <th>选择</th>
			          </tr>
			        </thead>
			        <tbody>
			          	<?php 
			        		for($i = 0; $i < count($user_list); $i++){
			        			if($user_list[$i]->role =='1'){
			        				$role = '集群管理员';
			        			}elseif ($user_list[$i]->role =='2') {
			        				$role = '设备管理员';
			        			}elseif ($user_list[$i]->role =='3') {
			        				$role = '卷管理员';
			        			}else{
			        				$role = '普通用户';
			        			}
			        			echo "<tr>";
			        			echo "<td>".$user_list[$i]->username."</td>";
			        			echo "<td>".$role."</td>";
			        			echo '<td><input type="checkbox" name="user_id[]" value="'.$user_list[$i]->id.'"></td>';
						   	echo "</tr>";
						}
			        	?>
			        </tbody>
		      </table>
		      <div>
		      		<button type="submit" class="btn btn-danger">注销所选</button>
		      </div>
		      <div>
		      		<button type="button" class="btn btn-primary mt20">
		      			<a href="<?php echo site_url('login/register'); ?>">新建帐号</a>
		      		</button>
		      </div>
 		</form>
 		<div class="main vol-main" <?php if(!$ctr_con_show[1]) {echo ' style="display:none;"';}?> >
 			<div class="vol-main-t">分配卷管理员</div> 
 			<form class="vol-add-form" method="post" action="<?php echo site_url('account/volume'); ?>">
				<div class="vol-add-form-row">
					<span>卷的名字</span>
					<select name="volume_id" class="form-control">
						<?php 
			        		for($i = 0; $i < count($volume_list); $i++){
			        			echo '<option value="'.$volume_list[$i]->id.'">'.$volume_list[$i]->name.'</option>';
						}
			        	?>
					</select>
				</div>
				<div class="vol-add-form-row">
					<span>帐号名</span>
					<select name="user_id" class="form-control">
						<?php 
			        		for($i = 0; $i < count($user_list); $i++){
			        			echo '<option value="'.$user_list[$i]->id.'">'.$user_list[$i]->username.'</option>';
						}
			        	?>
					</select>
				</div>
				<button type="submit" class="btn btn-primary">分配</button>
 			</form>
 		</div>
 		<div  <?php if(!$ctr_con_show[2]) {echo ' style="display:none;"';}?> class="main account-main">
 			shebei
 			
 		</div>
 	</div>
 </body>
</html>