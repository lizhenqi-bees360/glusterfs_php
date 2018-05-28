<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_xlator extends CI_Model {

	public $xlator_table = 'xlator_list';
	public $max_idle_time = 300; // allowed idle time in secs, 300 secs = 5 minute
	
	function __construct(){
	        // Call the Model constructor
	        parent::__construct();
	        $this->load->library('utilclass');
	}	
	
	// xlator
	function get_xlator_all(){
		$data = 	array();	
		$sql = "SELECT * FROM $this->xlator_table;";
		$query = $this->db->query($sql);
		$res = $query->result();
		for ($i=0; $i < count($res); $i++) { 
			$res[$i]->open_command = explode(';', $res[$i]->open_command);
			$res[$i]->close_command = explode(';', $res[$i]->close_command);
		}
		//var_dump($res);
		return $res;
	}
	function get_xlator_by_name($name){
		$sql = "SELECT * FROM $this->xlator_table where name = ?;";
		$query = $this->db->query($sql, array($name));
		$res = $query->result();
		if(count($res)){
			return $res[0];
		}
		return array();
	}
	function save_xlator_list($data){
		//replace
		$this->db->replace($this->xlator_table , $data); 
	}
	function delete_xlator_list($name){
		$this->db->where('name', $name);
		$this->db->delete($this->xlator_table); 
	}
}

/* End of file m_account.php */
/* Location: ./application/models/m_account.php */