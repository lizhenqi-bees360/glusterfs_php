<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	public $data = array();

    function __construct(){
      // Call the Controller constructor
        parent::__construct();
		
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");		
	
	$this->load->model('m_user');
	
	if ($this->m_user->is_logged_in() === FALSE) { 
		$this->m_user->remove_pass();
		redirect('login/noaccess');
	} else {
		// is_logged_in also put user data in session
		$this->data['user'] = $this->session->userdata('user');
	}

    }

	public function index(){
		//这个用于连接有nmap工具的linux系统
		if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
		// log in at server1.example.com on port 22
		$ip = '192.168.150.146';
		$username = 'root';
		$password = '123456';
		if(!($con = ssh2_connect($ip, 22))){
			die("fail: unable to establish connection");
		} else {
			// try to authenticate with username root, password secretpassword
			if(!ssh2_auth_password($con, $username, $password)) {
				die("fail: unable to authenticate");
			} else {
				
					// allright, we're in!
					//echo "okay: logged in...\n";     
					
					$str="gluster peer status --xml";  
					$file_name= dirname(dirname(dirname(__FILE__)))."/xml/".$str.".xml"; 
					if (!($stream = ssh2_exec($con, $str ))) {
							echo "fail: unable to execute command\n";
						} else {
							// collect returning vdata from command
							stream_set_blocking($stream, true);
							$vdata = "";
							while ($buf = fread($stream,4096)) {
								$vdata .= $buf;
							}
							$this->data['version'] = $ip."\n".$vdata;
							$myfile = fopen($file_name, "w") or die("Unable to open file!");
							fwrite($myfile, $vdata);					
							fclose($stream);
							//echo 'ok';								
					}		
								
			}		
			
		}
		$this->load->view('glusterfs/v_cluster', $this->data);
	}
	public function storage(){	
		$this->load->view('glusterfs/v_cluster', $this->data);
	}
	function pingAddress($ip) {
		$ip = '172.29.107.14';
		$pingresult = shell_exec("ping  $ip");
		var_dump($pingresult);
		if (0 == $pingresult) {
			echo "The IP address, $ip, is  alive ". $pingresult;
		} else {
			echo "The IP address, $ip, is  dead ". $pingresult;
		}
	}
}
