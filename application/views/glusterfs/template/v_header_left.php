<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GlusterFS前端管理工具PHP开发</title>
    <link href="<?php echo base_url('css/bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('css/home.css');?>" rel="stylesheet">
    <script src="<?php echo base_url('js/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('js/bootstrap.min.js');?>"></script>
  </head>
<body class="my-body">
	<!-- feedback msg -->
	<div>
		<button style="display:none;" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm">Small modal</button>
		<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
			 <div class="modal-dialog modal-sm" role="document">
			       <div class="modal-content">
			     		<div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Error Tip</h4>
				      </div>
				      <div class="modal-body"  style="min-height:60px;color:red;">
				        	<?php  echo $feedback['msg'];?>
				      </div>
			       </div>
			  </div>
		</div>
	</div>
	<!-- sider bar -->
	<div class="sider-bar">
 		<div class="sider-profile">
 			<div>
 				<img class="profile-img" src="<?php echo base_url('img/admin.png');?>">
 				</img>
 				<div class="profile-name"><?php echo $user['username']?></div>
 				<div class="profile-role">
 				<?php 
 					$role = $user['role'];
 					if($role == 1){
 						echo "集群管理员";
 					}elseif($role == 2){
 						echo "设备管理员";
 					}elseif($role == 3){
 						echo "卷管理员";
 					}else{
 						echo "普通用户";
 					}
 				?>
 				</div>
 			</div>
 		</div>
 		<div class="sider-fun-w">
 			<div class="home-active">
 				<div class="sider-title">
				  	<a class="other-nv-a" href="<?php echo site_url('home'); ?>">统计面板</a>
				</div>
 			</div>
 			<div class="cluster-active">
 				<div class="sider-title" role="button" data-toggle="collapse" href="#cluster-m" aria-expanded="false" aria-controls="collapseExample">
				  集群储存管理		
				</div>
				<ul class="collapse" id="cluster-m">
					<li class="sider-subtitle">
						<a href="<?php echo site_url('cluster'); ?>">集群管理</a>
					</li>
					<li class="sider-subtitle">
						<a href="<?php echo site_url('cluster/storage'); ?>">存储空间和目录管理</a>
					</li>
				</ul>
 			</div>
 			<div class="volume-active">
 				<div class="sider-title" role="button" data-toggle="collapse" href="#volume-m" aria-expanded="false" aria-controls="collapseExample">
				  卷管理
				</div>
				<ul class="collapse" id="volume-m">
					<li class="sider-subtitle">
						<a href="<?php echo site_url('volume'); ?>">查看所有卷</a>
					</li>
					<li class="sider-subtitle">
						<a href="<?php echo site_url('volume/add'); ?>">新建卷</a>
					</li>
					<li class="sider-subtitle">
						<a href="<?php echo site_url('volume/update/0'); ?>">修改卷</a>
					</li>
					<!-- <li class="sider-subtitle">
						<a href="<?php echo site_url('volume/replace/0'); ?>">迁移卷</a>
					</li> -->
					<!-- <li class="sider-subtitle">
						<a href="<?php echo site_url('volume/xlator'); ?>">自定义Xlator</a>
					</li>  -->
				</ul>
 			</div>
 			<div class="account-active">
 				<div class="sider-title" role="button" data-toggle="collapse" href="#role-m" aria-expanded="false" aria-controls="collapseExample">
				  帐号管理
				</div>
				<ul class="collapse" id="role-m">
					<li class="sider-subtitle">
						<a href="<?php echo site_url('account'); ?>">帐号列表</a>
					</li>
					<li class="sider-subtitle">
						<a href="<?php echo site_url('account/volume'); ?>">分配卷</a>
					</li>
					<!-- <li class="sider-subtitle">
						<a href="<?php echo site_url('account/equipment'); ?>">分配设备</a>
					</li> -->
				</ul>
 			</div>
 			<div class="performance-active">
 				<div class="sider-title">
				  	<a class="other-nv-a" href="<?php echo site_url('performance/index/0'); ?>">性能监控分析</a>
				</div>
 			</div>
 			<div class="breakdown-active">
 				<div class="sider-title">
				  	<a class="other-nv-a" href="<?php echo site_url('breakdown'); ?>">故障与恢复</a>
				</div>
 			</div>
 			<div class="xlator-active">
 				<div class="sider-title">
				  	<a class="other-nv-a" href="<?php echo site_url('xlator'); ?>">自定义Xlator</a>
				</div>
 			</div>
 			<div class="initialization-active">
 				<div class="sider-title">
				  	<a class="other-nv-a" href="<?php echo site_url('init'); ?>">初始设置</a>
				</div>
 			</div>
 		</div>
 	</div>
 	<script type="text/javascript">
 		// show failed tips
		<?php if($feedback['is_show']){ ?>
			$('.bs-example-modal-sm').modal('show');
		<?php } ?>
 	</script>