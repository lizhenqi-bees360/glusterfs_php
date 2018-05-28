
         <style type="text/css">
                .performance-active{
                    color: #dadada;
                    background: #293846;
                }
                .performance-active ul{
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
 				性能监控分析
 			</div> 
 			<div class="vol-main-w mt20">
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
					  					echo '<a href="'.site_url("performance/index/$vname_arr[$i]").'">'.$vname_arr[$i].'</a>';
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
					  	<div class="panel-heading">卷 profile</div>
					  	<div class="panel-body" style="padding:20px;">
					  		<?php 
					  			for ($i=0; $i < count($profile); $i++) { 
					  				echo '<div class="performance-profile">';
					  				echo '<h4>Brick: '.$profile[$i]['brick_name'].'</h4>';
					  				// Cumulative Stats
					  				echo '<div class="performance-list">';
					  				echo '<p>Cumulative Stats</p>';
					  				echo '<table class="table">';
					  				echo '<thead>
									          <tr>
									            <th>Avg-Latency</th>
									            <th>Min-Latency</th>
									            <th>Max-Latency</th>
									             <th>No. of calls</th>
									            <th>Fop</th>
									          </tr>
									        </thead>';
									echo '<tbody>';
									$fop_stats = $profile[$i]['cumulative']['fop_stats'];
									for($j = 0; $j < count($fop_stats); $j++){
										echo '<tr>';
										echo '<td>'.$fop_stats[$j]['avg_latency'].'</td>';
										echo '<td>'.$fop_stats[$j]['min_latency'].'</td>';
										echo '<td>'.$fop_stats[$j]['max_latency'].'</td>';
										echo '<td>'.$fop_stats[$j]['hits'].'</td>';
										echo '<td>'.$fop_stats[$j]['name'].'</td>';
										echo '</tr>';
									}
									echo '</tbody>
								      	</table>';
								      	echo '<p>Duration:  '.$profile[$i]['cumulative']['duration'].'</p>';
								      	echo '<p>Data Read:  '.$profile[$i]['cumulative']['total_read'].'</p>';
								      	echo '<p>Rata Written:  '.$profile[$i]['cumulative']['total_write'].'</p>';
								      	echo '</div>';

								      	// Interval Stats
					  				echo '<div class="performance-list">';
					  				echo '<p>Interval Stats</p>';
					  				echo '<table class="table">';
					  				echo '<thead>
									          <tr>
									            <th>Avg-Latency</th>
									            <th>Min-Latency</th>
									            <th>Max-Latency</th>
									             <th>No. of calls</th>
									            <th>Fop</th>
									          </tr>
									        </thead>';
									echo '<tbody>';
									$fop_stats = $profile[$i]['interval']['fop_stats'];
									for($j = 0; $j < count($fop_stats); $j++){
										echo '<tr>';
										echo '<td>'.$fop_stats[$j]['avg_latency'].'</td>';
										echo '<td>'.$fop_stats[$j]['min_latency'].'</td>';
										echo '<td>'.$fop_stats[$j]['max_latency'].'</td>';
										echo '<td>'.$fop_stats[$j]['hits'].'</td>';
										echo '<td>'.$fop_stats[$j]['name'].'</td>';
										echo '</tr>';
									}
									echo '</tbody>
								      	</table>';
								      	echo '<p>Duration:  '.$profile[$i]['interval']['duration'].'</p>';
								      	echo '<p>Data Read:  '.$profile[$i]['interval']['total_read'].'</p>';
								      	echo '<p>Rata Written:  '.$profile[$i]['interval']['total_write'].'</p>';
								      	echo '</div>';//end of Interval Stats

								      	echo '</div>';//end of profile
					  			}
					  			if(!count($profile)){
					  				echo '<p>获取卷profile失败</p>';
					  			}
					  		?>
					      	</div>							  	
				  	</div>
				</div>
			</div>
 		</div>

 	</div>
 </body>
</html>