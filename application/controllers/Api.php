<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller{
	public $root = "/var/ftp/pub/";
	public $user = "root";	
	public $pass = "123456";	//本机root账号的密码

	function upload_post(){
		$res = array();
		$data = array();
		$name = $this->_post_args['name'];
		try{
			if (mkdir($this->root.$name) == FALSE) {
				$res['path'] = $this->root.$name;
				$res['code'] = 409;
				$res['msg'] = "system name exist";
				$this->response($res, 409);
			} 
		} catch(Exception $e) {
			$res['err'] = $e->getMessage();
			$this->response($res, 409);
		}
		if (move_uploaded_file($_FILES["cfg"]["tmp_name"], $this->root . $name . "/ks.cfg") == FALSE){
			$res['code'] = 409;
			$res['msg'] = "save cfg fail";
			$this->response($res, 409);
		}
		$contents = $this->_post_args['desc'];
		$myfile = fopen($this->root . $name . "/desc.txt", "w");
		fwrite($myfile, $contents);
		fclose($myfile);

		if (move_uploaded_file($_FILES["system"]["tmp_name"], $this->root . $name . "/sourse.iso") == FALSE){
			$res['code'] = 409;
			$res['msg'] = "save system fail";
			$this->response($res, 409);
		}

		// ssh2连接本地
		if(!($connection = ssh2_connect("127.0.0.1",22))){
			$data['ssh_connect'] = "ssh connect fail";
		}
		if (!ssh2_auth_password($connection,$this->user,$this->pass))  
		{
			$res['code'] = 401;
			$res['msg'] = "执行ssh2连接的密码错误";
			$this->response($res, 401);
		}

		// 创建对应的sourse文件夹用来挂载iso文件
		mkdir($this->root.$name."/sourse");
		// 将sourse.iso文件挂载到sourse
		$str = 'mount -o loop '.$this->root.$name.'/sourse.iso  '.$this->root.$name.'/sourse';
		$data['exc'] = $str;
		if(!($stream = ssh2_exec($connection, $str))) {
			$data['mount_res'] = "fail to exe";
		} else {
			stream_set_blocking($stream, true);	
			$data['mount_res'] = "";
			while ($buf = fread ($stream, 4096)){
				$data['mount_res'] .= $buf;
			}
			fclose($stream);
		}

		//成功上传和挂载系统，返回相应信息
		$res['code'] = 200;
		$res['msg'] = "success to save system"; 
		$res['data'] = $data;
		$this->response($res, 200);

	}

}
