
         <style type="text/css">
                .home-active{
                    color: #dadada;
                    background: #293846;
                }
                .home-active ul{
                    height: auto;
                }
        </style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<!-- home  -->
 		<div class="main vol-main" >
 			<div class="vol-main-t">
 				统计面板
 				<!-- <button type="button" class="btn btn-primary" style="float:right; margin:-1px 20px 0 0;">
 					<a   href="<?php echo site_url('cluster/storage_refresh'); ?>">
						 刷新
					 </a>
 				</button> -->
 			</div> 
 			<div class="vol-main-w" style="margin-top:20px;">
 				<div class="vol-main-w-con dashboard-con">
 					<div class="dashboard-con-left">
 						<span class="glyphicon glyphicon-scale" aria-hidden="true"></span>
 					</div>
 					<div class="dashboard-con-right">
 						<p>卷Volume</p>
 						<p>数量: <?=$volume_num?></p>
 					</div>
 				</div>
 				<div class="vol-main-w-gap  dashboard-gap"></div>
 				<div class="vol-main-w-con dashboard-con">
 					<div class="dashboard-con-left">
 						<span class="glyphicon glyphicon-gift" aria-hidden="true"></span>
 					</div>
 					<div class="dashboard-con-right">
 						<p>砖Brick</p>
 						<p>数量: <?=$brick_num?></p>
 					</div>
 				</div>
 			</div>
 			<div class="vol-main-w" style="margin-top:20px;">
 				<div class="vol-main-w-con dashboard-con">
 					<div class="dashboard-con-left">
 						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
 					</div>
 					<div class="dashboard-con-right">
 						<p>用户User</p>
 						<p>数量: <?=$user_num?></p>
 					</div>
 				</div>
 				<div class="vol-main-w-gap  dashboard-gap"></div>
 				<div class="vol-main-w-con dashboard-con">
 					<div class="dashboard-con-left">
 						<span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
 					</div>
 					<div class="dashboard-con-right">
 						<p>设备Devdice</p>
 						<p>数量: <?=$storage_num?></p>
 					</div>
 				</div>
 			</div>
 		</div>

 	</div>
 </body>
</html>