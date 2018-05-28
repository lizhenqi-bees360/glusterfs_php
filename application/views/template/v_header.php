<!DOCTYPE html>
<html lang="en" style="height:100%;">
	<head>
		<meta charset="utf-8">
		<title>GlusterFS前端管理工具PHP开发</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
    	<meta name="author" content="">

		<link href="<?php echo base_url('css/bootstrap.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('css/style.css');?>" rel="stylesheet">
		<style type="text/css">
			*{
				padding: 0;
				margin:0;
			}
			body{
				padding: 0;
				margin:0;
				height: 100%;
				width: 100%;
				background:#f3f3f4;
				position: relative;
			}
			h1{
				position: relative;
				top:60px;
				text-align: center;
				color: #a5a2a2;
				font-size: 42px;
				width: 100%;
			}
			#login-body{
				position: absolute;
				top:120px;
				left: 50%;
    				transform: translateX(-50%);
				width: 48%;
				min-width: 480px;
				max-height: 600px;
				background: #fff;
				padding:0 20px 20px;
				border-radius: 5px;
			}
			legend{
				height: 56px;
				line-height: 56px;
				margin-bottom: 0;
			}
		</style>
	</head>

	<body>
		<h1>GlusterFS WEB</h1>
		<div id="login-body">
