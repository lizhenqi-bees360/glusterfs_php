<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_account extends CI_Model {

	public $user_table = 'users';
	public $volume_owner_table = 'volume_owner';
	public $max_idle_time = 300; // allowed idle time in secs, 300 secs = 5 minute
	
	function __construct(){
	        // Call the Model constructor
	        parent::__construct();
	}	
	function get_user_all(){
		$query = $this->db->query("SELECT * FROM $this->user_table;");
		$data = $query->result();
		return $data;
	}
	
	function delete_user($id){
		$this->db->where('id', $id);
		$this->db->delete($this->user_table); 
	}
	function add_volume_owner($data){
		$this->db->insert($this->volume_owner_table , $data); 
		return $this->db->insert_id();
	}
}

/* End of file m_account.php */
/* Location: ./application/models/m_account.php */