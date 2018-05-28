<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_cluster extends CI_Model {

	public $host_table = 'host_list';
	public $gluster_table = 'gluster_list';
	public $node_table = 'node_list';
	public $brick_table = 'brick_list';
	public $storage_table = 'storage_list';
	public $max_idle_time = 300; // allowed idle time in secs, 300 secs = 5 minute
	public $client_ip;
	public $account = 'root';
	public $password = '123456';

	function __construct(){
	        // Call the Model constructor
	        parent::__construct();
	        $this->load->library('utilclass');
	        $this->load->model('m_volume');
	        $this->client_ip = $this->config->config['client_ip'];
	}	
	function test_fun(){
	}
	function get_all_info(){
		$query = $this->db->query("SELECT * FROM $this->host_table;");
		$data['host_list'] = $query->result();

		$query = $this->db->query("SELECT * FROM $this->gluster_table;");
		$data['gluster_list'] = $query->result();

		$query = $this->db->query("SELECT * FROM $this->node_table;");
		$data['node_list'] = $query->result();
		
		return $data;
	}
	/*****************host list******************/
	function save_host_list($data){
		//replace
		$this->db->replace($this->host_table , $data); 
	}
	function delete_host_list($ip){
		$this->db->where('ip', $ip);
		$this->db->delete($this->host_table); 
	}
	function auto_ping($begin_ip, $end_ip){
		$b_arr = explode('.', $begin_ip);
		$e_arr = explode('.', $end_ip);
		$pre_ip = $b_arr[0].'.'.$b_arr[1].'.'.$b_arr[2].'.';
		for ($i = (int)$b_arr[3]; $i <= (int) $e_arr[3]; $i++) {
			$tar_ip =  $pre_ip.$i;
			if($this->ping_one_ip($tar_ip)){
				$this->save_host_list(array('ip' => $tar_ip, 'status' => "active"));
			}else{
				$this->save_host_list(array('ip' => $tar_ip, 'status' => "unknown"));
			}
		}

	}
	function sigle_ping($tar_ip){
		if($this->ping_one_ip($tar_ip)){
			$this->save_host_list(array('ip' => $tar_ip, 'status' => "active"));
		}else{
			$this->save_host_list(array('ip' => $tar_ip, 'status' => "unknown"));
		}
	}
	function file_ping($filename){
		$file = fopen($filename, "r") or exit("无法打开文件!");
		while(!feof($file)){
			//去除每行换行符，否则会有异常
			$ip=trim(fgets($file));
			if(!strlen($ip)){
				continue;
			}else{
				$this->sigle_ping($ip);
			}
		}
	}
	function ping_one_ip($target_ip){
		// Starting Nmap 6.40 ( http://nmap.org ) at 2018-03-28 20:17 CST Nmap scan report for 192.168.1.155 Host is up (0.00030s latency). Nmap done: 1 IP address (1 host up) scanned in 0.01 seconds 
		// Starting Nmap 6.40 ( http://nmap.org ) at 2018-03-28 20:20 CST Note: Host seems down. If it is really up, but blocking our ping probes, try -Pn Nmap done: 1 IP address (0 hosts up) scanned in 3.00 seconds 
		$str = "nmap -sP $target_ip";
		$res = shell_exec($str);
		//judge base on : is " hosts up" exit or not, if not ,just can ping, 
		$index = strrpos($res, ' hosts up');
		return !$index;
	}
	function host_add($list){
		for($i = 0; $i < count($list); $i++){
			$command = 'gluster --version';
			$status = "active";
			$file_name = false;
			// $file_name= dirname(dirname(dirname(__FILE__)))."/xml/".$command.$list[$i].".xml"; 
			$res = $this->utilclass->ssh2_do($list[$i], $command, $file_name);
			if($res && $res != 'success'){
				$s=explode(' ', $res, 8);
				if($s[0]=="glusterfs"){
					$status = substr($s[1] , 0 , 6);
					$this->save_gluster_list(array('ip' => $list[$i], 'status' => $status));
				}
			}else{
				$this->save_gluster_list(array('ip' => $list[$i], 'status' => 'inactive'));
			}
		}
	}
	function host_delete($list){
		for($i = 0; $i < count($list); $i++){
			$this->delete_host_list($list[$i]);
		}
	}
	/******************gluster list*******************/
	function save_gluster_list($data){
		//replace
		$this->db->replace($this->gluster_table , $data); 
	}
	function delete_gluster_list($ip){
		$this->db->where('ip', $ip);
		$this->db->delete($this->gluster_table); 
	}
	function gluster_delete($list){
		for($i = 0; $i < count($list); $i++){
			$this->delete_gluster_list($list[$i]);
		}
	}
	function gluster_add($list){
		$ip = $this->client_ip;
		$type = 'probe';
		$status = 'active';
		$res = $this->utilclass->ssh2_peer_do($ip, $list,  $type);
		for($i = 0; $i < count($list); $i++){
			if($res[$i]){
				$this->save_node_list(array('ip' => $list[$i], 'status' => $status));
			}
		}
	}

	/************************node list*************************/
	function get_node_ip(){
		$query = $this->db->query("SELECT `ip` FROM $this->node_table;");
		$ip_list = $query->result();
		return $ip_list;
	}
	function save_node_list($data){
		//replace
		$this->db->replace($this->node_table , $data); 
	}
	function delete_node_list($ip){
		$this->db->where('ip', $ip);
		$this->db->delete($this->node_table); 
	}
	function node_delete($list){
		$ip = $this->client_ip;
		$type = 'detach';
		$res = $this->utilclass->ssh2_peer_do($ip, $list, $type);
		for($i = 0; $i < count($list); $i++){
			if($res[$i]){
				$this->delete_node_list($list[$i]);
			}
		}
		return $res[0];
	}
	/******************************storage***************************/
	function storage_new($ip, $command){
		$file_name = false;
		$res = $this->utilclass->ssh2_do($ip, $command, $file_name);
		return !$res;
	}
	function update_storage_status($id, $is_used){
		$data = array(
		    'is_used' => $is_used
		);

		$this->db->where('id', $id);
		$this->db->update($this->storage_table, $data);
	}
	function save_storage_list($data){
		$this->db->replace($this->storage_table , $data); 
	}
	function get_all_storage(){
		$query = $this->db->query("SELECT * FROM $this->storage_table;");
		return $query->result();
	}
	function get_storage_count(){
		$query = $this->db->query("SELECT * FROM $this->storage_table;");
		$num = count($query->result());
		return $num;
	}
	function get_usable_storage(){
		$sql = "SELECT * FROM $this->storage_table where is_used = ?";
		$query = $this->db->query($sql, array(0) );
		return $query->result();
	}
	function get_directory(){
		$res = array();
		$ip_list = $this->get_node_ip();
		for($i = 0; $i < count($ip_list); $i++){
			$sql = "SELECT `name` FROM $this->brick_table where host_ip = ?";
			$query = $this->db->query($sql, array($ip_list[$i]->ip) );
			$name_arr = $query->result();
			$name_str = '无';
			if(count($name_arr)){
				$name_str = $this->arrToString($name_arr);
			}
			$temp = array('ip' => $ip_list[$i]->ip, 'count' => count($name_arr), 'name' => $name_str);
			array_push($res, $temp);
		}
		return $res;
	}
	function get_directory_num($ip){
		$sql = "SELECT `name` FROM $this->brick_table where host_ip = ?";
		$query = $this->db->query($sql, array($ip) );
		$name_arr = $query->result();
		return count($name_arr);
	}
	function arrToString($name_arr){
		$str = '';
		for($i = 0; $i < count($name_arr) - 1; $i++){
			$s = explode(':', $name_arr[$i]->name);
			if(count($s) ==1){
				$str .= $s[0] . ', ';
			}else{
				$str .= $s[1] . ', ';
			}
			if( !($i % 5)  && $i){
				$str .= '</br>';
			}
		}
		$s = explode(':', $name_arr[$i]->name);
		if(count($s) ==1){
			$str .= $s[0];
		}else{
			$str .= $s[1];
		}
		return $str;
	}
}

/* End of file m_cluster.php */
/* Location: ./application/models/m_cluster.php */