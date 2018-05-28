	<link href="<?php echo base_url('material/material.min.css');?>" rel="stylesheet">
    	<script src="<?php echo base_url('js/jquery.form.js');?>"></script>
    	<script src="<?php echo base_url('material/material.min.js');?>"></script>
         <style type="text/css">
                .initialization-active{
                    color: #dadada;
                    background: #293846;
                }
                .initialization-active ul{
                    height: auto;
                }
                .upload_page-content{
		        padding: 0 20px;
		    }
		    .my-mdl-card {
		        width: 100%;
		        margin: 20px 0;
		        min-height: 0;
		    }
		    .flex-right{
		        display: flex;
		        flex-direction: row-reverse;
		    }
		    .upload_card-content{
		        padding: 10px 20px;
		        display: flex;
		        flex-direction: column;
		    }
		    .upload_page-content .mdl-textfield{
		        width: 100%;
		    }
		    .mdl-filefield__input {
		        width: 0;
		        height: 0;
		        overflow: hidden;
		        z-index: 1;
		        padding: 0;
		    }
		    .upload_card-content .mdl-progress{
		        width: 100%;
		    }
		    .mdl-layout__content{
		    	width: 100%;
		    }
		    a{
		    	color: #0088cc;
		    }
		    .mdl-dialog{
		    	margin: auto;
		    }
        </style>
 	<div class="my-container">
 		<div class="my-header">
 			<span style="font-size:18px;">欢迎使用GlusterFS前端管理工具</span>
 			<a   class="logoutbtn" style="float:right;" href="<?php echo site_url('logout'); ?>">退出</a>
 		</div>
 		<div class="main clu-main">
 			<!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#set_default" aria-controls="set_default" role="tab" data-toggle="tab">默认系统</a></li>
			    <li role="presentation"><a href="#upload_sys" aria-controls="upload_sys" role="tab" data-toggle="tab">上传系统</a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="set_default">
				    		<?php 
				    			for ($i=0; $i < count($system_list); $i++) {  ?>
				    				 <div class="system-list">
								   		<h3 class="system-list-t"><?=$system_list[$i]['name']?></h3>
								   		<p class="system-list-desc"><?=$system_list[$i]['desc']?></p>
							   			<?php if($system_list[$i]['isDefault']){ ?>
							   				<button type="button" class="btn btn-default">当前默认系统</button>
							   			<?php } else{ ?>
							   				<button type="button" class="btn btn-primary">
							   					<a href="<?php echo site_url('init/setDefaultAs/'.$system_list[$i]['name']); ?>">设置默认</a>
							   				</button>
						   				<?php } ?>
								   </div>
				    		<?php }
				    		?>					 
				    </div>
				    <div role="tabpanel" class="tab-pane" id="upload_sys">
				    		<main class="mdl-layout__content">
						    <div class="upload_page-content">
						        <form id="myForm" class="my-mdl-card mdl-card mdl-shadow--2dp" action="<?php echo site_url('Api/upload'); ?>" method="post" enctype="multipart/form-data">
						            <div class="mdl-card__title">
						                <h2 class="mdl-card__title-text">上传系统</h2>
						            </div>
						            <div class="upload_card-content">
						                <div class="mdl-textfield mdl-js-textfield">
						                    <input class="mdl-textfield__input" type="text" name="name">
						                    <label class="mdl-textfield__label" for="sample1">系统名称</label>
						                </div>
						                <div class="mdl-textfield mdl-js-textfield file-field">
						                    <input class="mdl-textfield__input" type="text" onclick="chooseCfg()" id='configInput'>
						                    <input style="display:none" class="mdl-textfield__input mdl-filefield__input" type="file" name="cfg" id="config">
						                    <label class="mdl-textfield__label" for="configInput"  id='configText'>配置文件ks.cfg</label>
						                </div>
						                <div class="mdl-textfield mdl-js-textfield file-field">
						                    <input class="mdl-textfield__input" type="text" id="systemInput" onclick="chooseSystem()">
						                    <input style="display:none" class="mdl-textfield__input mdl-filefield__input ignore" type="file" id="system" name="system">
						                    <label class="mdl-textfield__label" for="systemInput" id="systemText">选择系统镜像，请选择iso文件</label>
						                </div>
						                <div class="mdl-textfield mdl-js-textfield">
						                    <textarea class="mdl-textfield__input" type="text" rows= "3" name="desc"></textarea>
						                    <label class="mdl-textfield__label" for="sample5">系统相关介绍说明</label>
						                </div>
						                <div>
						                    <div id="progress" class="mdl-progress mdl-js-progress"></div>
						                </div>
						                
						            </div>
						            <div class="flex-right mdl-card__actions mdl-card--border">
						                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit">
						                    上传
						                </button>
						            </div>
						        </form>
						    </div>
						    <dialog class="mdl-dialog">
						        <h4 class="mdl-dialog__title" id="dialog_title"></h4>
						        <div class="mdl-dialog__content"><p id="dialog_content"></p></div>
						        <div class="mdl-dialog__actions">
						            <button type="button" class="mdl-button close" id="agreebtn">好的</button>
						            <button type="button" class="mdl-button close" id="disagreebtn">不用了</button>
						        </div>
						    </dialog>
						    <div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
						        <div class="mdl-snackbar__text"></div>
						        <button class="mdl-snackbar__action" type="button"></button>
						    </div>
						</main>
				    </div>
			  </div>
 		</div>
 	</div>
 </body>
 <script type="text/javascript">
    // 样式显示的辅助逻辑
    // 下面两个只是为了把点击选择系统的fileinput，因为引用的样式中没有fileinput，所以只能自己写一下
    function chooseCfg(){ $("#config").click(); }
    function chooseSystem(){ $("#system").click(); }
    // 将上传文件的名称回显
    $('#config').change(function(){
        var f = document.getElementById("config").files; 
        $('#configText').html(f[0].name)
        $('#configText').css("color","black");
    });
    $('#system').change(function(){
        var f = document.getElementById("system").files; 
        $('#systemText').html(f[0].name)
        $('#systemText').css("color","black");
    });

   var systemname = ""; //用来记录系统的名称，后面设置为默认时候需要该变量，上传前(beforeSubmit)设置该值
   $(function(){
        var dialog = document.querySelector('dialog');
        // ajaxForm把表单变成异步提交
        $('#myForm').ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                systemname = arr[0].value;
                var snackbarContainer = document.querySelector('#demo-snackbar-example');
                var data = {
                    message: '系统上传中，请不要离开此页面',
                    timeout: 2000,
                    actionText: 'GET'
                };
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
            },
            uploadProgress: function (event, position, total, percentComplete ) {
                var percentVal = percentComplete + '%';
                $("#progress .progressbar").css("width",percentVal);
            },
            success: function(data, textStatus, jqXHR, $form) {
                // 提交成功的话展示对话框提示
                $("#dialog_title").html("上传系统成功")
                $("#dialog_content").html("是否将该系统设置为默认")
                dialog.querySelector('#agreebtn').addEventListener('click', function() {
                    // dialog.close();
                    //如果同意就将上传的系统设置为默认系统
                    window.location.href = "<?=base_url()?>"+"index.php/init/setDefaultAs/" + systemname;
                });
                dialog.showModal();
            },
            error: function(data, textStatus, jqXHR, $form) {
                $("#dialog_title").html("上传失败")
                $("#dialog_content").html(data.msg)
                dialog.showModal();
            }
        });
        // 弹框按钮添加弹窗关闭的效果
        dialog.querySelector('#disagreebtn').addEventListener('click', function() { dialog.close() });
        dialog.querySelector('#agreebtn').addEventListener('click', function() { dialog.close() });
    });
</script>
</html>