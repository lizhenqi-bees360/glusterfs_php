<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilclass {

	public $client_ip = "192.168.1.155";
	public $username = 'root';
	public $password = '123456';

	function __construct(){

	}

	public function ssh2_do($ip, $command, $file_name){
		if (!function_exists("ssh2_connect")){
			//die("function ssh2_connect doesn't exist");
			return false;
		}
		// log in at server1.example.com on port 22
		if(!($con = ssh2_connect($ip, 22))){
			die("fail: unable to establish connection");
			return false;
		} else {
			// try to authenticate with username root, password secretpassword
			if(!ssh2_auth_password($con, $this->username, $this->password)) {
				return false;
				//die("fail: unable to authenticate");
			} else {
				
				// allright, we're in!
				//echo "okay: logged in...\n";     
				
				if (! ($stream = ssh2_exec($con, $command ) ) ) {
					return false;
					//echo "fail: unable to execute command\n";
				} else {
					// collect returning data from command
					stream_set_blocking($stream, true);
					$data = "";
					while ($buf = fread($stream,4096)) {
						$data .= $buf;
					}
					if($file_name){
						$myfile = fopen($file_name, "w");
						if(!$myfile){
							return false;
						}
						//die("Unable to open file!");
						fwrite($myfile, $data);
						fclose($stream);	
					}else{
						fclose($stream);
						return $data;
					}
					return 'success';
				}		
			}		
		}
	}

	public function ssh2_do_with_ask($ip, $command, $file_name){
		if (!function_exists("ssh2_connect")){
			//die("function ssh2_connect doesn't exist");
			return false;
		}
		// log in at server1.example.com on port 22
		if(!($con = ssh2_connect($ip, 22))){
			die("fail: unable to establish connection");
			return false;
		} else {
			// try to authenticate with username root, password secretpassword
			if(!ssh2_auth_password($con, $this->username, $this->password)) {
				return false;
				//die("fail: unable to authenticate");
			} else {
				
				$shell = ssh2_shell($con, 'xterm');
				fwrite($shell, $command.PHP_EOL);
				sleep(1);//@important
				fwrite($shell, 'y'.PHP_EOL);
				
				return 'ssh do success';
			}		
		}
	}

	public function ssh2_peer_do($ip, $list, $type){
		if (!function_exists("ssh2_connect")){
			//die("function ssh2_connect doesn't exist");
			return false;
		}
		// log in at server1.example.com on port 22
		if(!($con = ssh2_connect($ip, 22))){
			die("fail: unable to establish connection");
			return false;
		} else {
			// try to authenticate with username root, password secretpassword
			if(!ssh2_auth_password($con, $this->username, $this->password)) {
				return false;
				//die("fail: unable to authenticate");
			} else {
				
				// allright, we're in!
				//echo "okay: logged in...\n";     
				$result = array();
				for($i = 0; $i < count($list); $i++){
					if($type == 'probe'){
						$command = 'gluster peer probe '.$list[$i];
					}else{
						$command = 'gluster peer detach '.$list[$i].' force';
					}
					if (!($stream = ssh2_exec($con, $command ))) {
						return false;
						//echo "fail: unable to execute command\n";
					} else {
						// collect returning data from command
						stream_set_blocking($stream, true);
						$data = "";
						while ($buf = fread($stream,4096)) {
							$data .= $buf;
						}
						fclose($stream);
						array_push($result, $this->jugde_peer_status($data));
					}
				}
				return $result;
						
			}		
		}
	}
	public function ssh2_do_arr($ip, $com_list){
		if (!function_exists("ssh2_connect")){
			//die("function ssh2_connect doesn't exist");
			return false;
		}
		// log in at server1.example.com on port 22
		if(!($con = ssh2_connect($ip, 22))){
			die("fail: unable to establish connection");
			return false;
		} else {
			// try to authenticate with username root, password secretpassword
			if(!ssh2_auth_password($con, $this->username, $this->password)) {
				return false;
				//die("fail: unable to authenticate");
			} else {
				
				// allright, we're in!
				//echo "okay: logged in...\n";     
				for($i = 0; $i < count($com_list); $i++){
					ssh2_exec($con, $com_list[$i] );
				}
				return 'ssh success';
						
			}		
		}
	}
	function jugde_peer_status($res){
		$index = strrpos($res, 'success');
		return !!$index;
	}
    
}