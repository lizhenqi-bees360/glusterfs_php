<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_volume extends CI_Model {

	public $volume_table = 'volume_list';
	public $brick_table = 'brick_list';
	public $owner_table = 'volume_owner';
	public $max_idle_time = 300; // allowed idle time in secs, 300 secs = 5 minute
	public $client_ip;
	public $account = 'root';
	public $password = '123456';

	function __construct(){
	        // Call the Model constructor
	        parent::__construct();
	        $this->load->library('utilclass');
	        $this->client_ip = $this->config->config['client_ip'];
	}	
	function get_volume_all(){		
		$sql = "SELECT * FROM $this->volume_table;";
		$query = $this->db->query($sql);
		$data['volume_list'] = $query->result();
		return $data;
	}
	function get_volume_count(){
		$query = $this->db->query("SELECT * FROM $this->volume_table;");
		$num = count($query->result());
		return $num;
	}
	function get_volume_by_user(){
		$userdata = $this->session->userdata('user');
		$con_data = array();
		if($userdata['role'] == 1){
			$sql = "SELECT * FROM $this->volume_table;";
		}elseif ($userdata['role'] == 3) {
			$sql = "SELECT * FROM $this->volume_table where id in (SELECT `volume_id` FROM $this->owner_table where user_id = ?);" ;
			$con_data = array($userdata['id']);
		}else{
			$data['volume_list'] = array();
			return $data;
		}
		$query = $this->db->query($sql, $con_data);
		$data['volume_list'] = $query->result();
		return $data;
	}
	function save_volume_list($data){
		//replace
		$this->db->replace($this->volume_table , $data); 
	}
	function save_brick_list($data){
		//replace
		$this->db->replace($this->brick_table , $data); 
	}
	function delete_volume_all(){
		$sql = "SELECT * FROM $this->volume_table;";
		$query = $this->db->query($sql);
		$data= $query->result();
		for ($i=0; $i < count($data); $i++) { 
			$this->delete_volume_list($data[$i]->name);
		}
	}
	function delete_brick_all(){
		$sql = "SELECT * FROM $this->brick_table;";
		$query = $this->db->query($sql);
		$data= $query->result();
		for ($i=0; $i < count($data); $i++) { 
			$this->delete_brick_list($data[$i]->name);
		}
	}
	function up_volume_brick(){
		$this->delete_volume_all();
		$this->delete_brick_all();

		$ip = $this->client_ip;
		$str = 'gluster volume info --xml';
		$file_name = dirname(dirname(dirname(__FILE__)))."/xml/".$str.".xml"; 
		$wfile_res = $this->utilclass->ssh2_do($ip, $str, $file_name);

		$xml = simplexml_load_file($file_name);
		$vol_num = $xml->volInfo->volumes->count;
		$volume = $xml->volInfo->volumes->volume;

		for($i = 0; $i < $vol_num; $i++){
			if((String)$volume[$i]->transport =='0'){
				$transport = 'tcp';
			}elseif ((String)$volume[$i]->transport == '1') {
				$transport = 'rdma';
			}else{
				$transport = 'tcp,rdma';
			}
			$vol_data = array(
				'id' => (String)$volume[$i]->id,
				'name' => (String)$volume[$i]->name,
				'status' => (Int)$volume[$i]->status,
				'status_str' => (String)$volume[$i]->statusStr,
				'snapshot_count' => (Int)$volume[$i]->snapshotCount,
				'brick_count' => (Int)$volume[$i]->brickCount,
				'dist_count' => (Int)$volume[$i]->distCount,
				'stripe_count' => (Int)$volume[$i]->stripeCount,
				'replica_count' => (Int)$volume[$i]->replicaCount,
				'arbiter_count' => (Int)$volume[$i]->arbiterCount,
				'disperse_count' => (Int)$volume[$i]->disperseCount,
				'redundancy_count' => (Int)$volume[$i]->redundancyCount,
				'type' => (Int)$volume[$i]->type,
				'type_str' => (String)$volume[$i]->typeStr,
				'transport' => $transport
				);
			//insert into table
			$this->save_volume_list($vol_data);
			
			//`uuid`, `name`, `host_uuid`, `host_ip`, `is_arbiter`, `volume_id`
			$brick = $volume[$i]->bricks->brick;
			for($j = 0; $j < count($brick); $j++){
				//@TODO
				$s = explode(':', (String)$brick[$j]->name);
				$brick_data = array(
					'name' => (String)$brick[$j]->name,
					'host_uuid' => (String)$brick[$j]->hostUuid,
					'host_ip' => $s[0],
					'is_arbiter' => (Int)$brick[$j]->isArbiter,
					'volume_id' =>(String)$volume[$i]->id
					);
				//insert into tabel
				$this->save_brick_list($brick_data);
			}
		}

		//update volume detail info
		$this->update_volume_all();
	}
	function update_volume_all(){
		$volume_all = $this->get_volume_all();
		if(!count($volume_all['volume_list'])){
			return;
		}
		foreach ($volume_all['volume_list'] as $key => $value) {
			$name = $value->name;
			$str = 'gluster volume get '.$name.' all --xml';
			$file_name = dirname(dirname(dirname(__FILE__)))."/xml/".$str.".xml"; 
			$ip = $this->client_ip;
			$this->utilclass->ssh2_do($ip, $str, $file_name);
		}
	}

	function get_volume_info($name){
		$tar_volume_id = '';
		$res = array('vname_arr' => array(), 'brick_arr' => array(), 'tar_volume' => array(), 'tar_index' => 0);
		$volume_all = $this->get_volume_all();
		if(!$name  && count($volume_all['volume_list'])){
			$name = $volume_all['volume_list'][0]->name;
		}
		foreach ($volume_all['volume_list'] as $key => $value) {
			if($value->name == $name){
				$res['tar_index'] = $key;
				$res['tar_volume'] = $value;
				$tar_volume_id = $value->id;
			}
			array_push($res['vname_arr'], $value->name);
		}
		$brick_arr = $this->get_brick_by_vid($tar_volume_id);
		foreach ($brick_arr as $key => $value) {
			array_push($res['brick_arr'], $value->name);
		}
		$detail_res = $this->get_volume_detail($name);
		$res['detail_info1'] = $detail_res['detail_info1'];
		$res['detail_info2'] = $detail_res['detail_info2'];
		$res['volume_name'] = $name;
		
		return $res;
	}
	function get_volume_detail($name){
		$res = array('detail_info1' => array(), 'detail_info2' => array());
		$str = 'gluster volume get '.$name.' all --xml';
		$file_name = dirname(dirname(dirname(__FILE__)))."/xml/".$str.".xml"; 
		if(!file_exists($file_name)){
			$ip = $this->client_ip;
			$this->utilclass->ssh2_do($ip, $str, $file_name);
		}
		$xml = simplexml_load_file($file_name);
		$num = (Int)$xml->volGetopts->count;
		$gap = (Int)($num / 2);
		$vol_opts = $xml->volGetopts;
		for($i = 0; $i < $gap; $i++){
			array_push($res['detail_info1'], 
				array('option' => (String)$vol_opts->Opt[$i]->Option, 
					'value' => (String)$vol_opts->Opt[$i]->Value)
				);
		}
		for($i = $gap; $i < $gap*2; $i++){
			array_push($res['detail_info2'], 
				array('option' => (String)$vol_opts->Opt[$i]->Option, 
					'value' => (String)$vol_opts->Opt[$i]->Value)
				);
		}
		return $res;
	}
	function get_brick_by_vid($volume_id){
		$sql = "SELECT `name`, `host_ip` FROM $this->brick_table where volume_id = ?";
		$query = $this->db->query($sql, array($volume_id) );
		return $query->result();
	}
	function add_volume($command){
		$ip = $this->client_ip;
		$file_name = false; 
		$add_res = $this->utilclass->ssh2_do($ip, $command, $file_name);
		return $this->utilclass->jugde_peer_status($add_res);
	}
	function handle_common_command($command){
		$ip = $this->client_ip;
		$file_name = false; 
		$res = $this->utilclass->ssh2_do($ip, $command, $file_name);
		return $this->utilclass->jugde_peer_status($res);
	}
	function handle_ask_command($command){
		$ip = $this->client_ip;
		$file_name = false; 
		$res = $this->utilclass->ssh2_do_with_ask($ip, $command, $file_name);
		return $this->utilclass->jugde_peer_status($res);
	}
	function get_brick_count(){
		$query = $this->db->query("SELECT * FROM $this->brick_table;");
		$num = count($query->result());
		return $num;
	}
	function handle_delete_volume($volume_name, $m_cluster){
		$sql = "SELECT `name` FROM $this->brick_table where volume_id = (SELECT `id` FROM $this->volume_table where name = ?)";
		$query = $this->db->query($sql, array($volume_name));
		$brick_name_arr = $query->result();
		// var_dump($brick_name_arr);
		for ($i=0; $i < count($brick_name_arr); $i++) { 
			//delete brick
			$this->delete_brick_list($brick_name_arr[$i]->name);
			//set brick unused
			$m_cluster->update_storage_status($brick_name_arr[$i]->name,  0);
		}
		// delete volume
		$this->delete_volume_list($volume_name);
	}
	function delete_volume_list($name){
		$this->db->where('name', $name);
		$this->db->delete($this->volume_table); 
	}
	function delete_brick_list($name){
		$this->db->where('name', $name);
		$this->db->delete($this->brick_table); 
	}
	function update_volume_status($name, $status_str){
		$data = array(
		    'status_str' => $status_str
		);

		$this->db->where('name', $name);
		$this->db->update($this->volume_table, $data);
	}
	
}

/* End of file m_volume.php */
/* Location: ./application/models/m_volume.php */