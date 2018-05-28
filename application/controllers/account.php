<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
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
		$this->load->model('m_account');
		$this->load->model('m_volume');
		
		if ($this->m_user->is_logged_in() === FALSE) { 
			$this->m_user->remove_pass();
			redirect('login/noaccess');
		} else {
			// is_logged_in also put user data in session
			$this->data['user'] = $this->session->userdata('user');
			$this->hldata['user'] = $this->session->userdata('user');
		}
		//init feedback 
		$this->hldata['feedback'] = array('msg' => '','is_show' => false);

		$this->load->view('glusterfs/template/v_header_left', $this->hldata);

	}

	public function index(){	
		$this->data['user_list'] = $this->m_account->get_user_all();
		$this->data['ctr_con_show'] = [1, 0, 0];
		$this->load->view('glusterfs/v_account', $this->data);
	}
	public function delete(){	
		if(isset($_POST['user_id'])){
			$user_id = $_POST['user_id'];
			for ($i=0; $i < count($user_id); $i++) { 
				$this->m_account->delete_user( $user_id[$i] );
			}
		}
		redirect('account');
	}
	public function volume(){
		if(isset($_POST['user_id'])){
			$v_data = array('volume_id' => $_POST['volume_id'], 'user_id' => $_POST['user_id']);
			$this->m_account->add_volume_owner($v_data);
		}
		$this->data['user_list'] = $this->m_account->get_user_all();
		$volume_data = $this->m_volume->get_volume_all();
		$this->data['volume_list'] = $volume_data['volume_list'];
		$this->data['ctr_con_show'] = [0, 1, 0];
		$this->load->view('glusterfs/v_account', $this->data);
		
	}
	public function equipment(){
		$this->data['ctr_con_show'] = [0, 0, 1];
		$this->load->view('glusterfs/v_account', $this->data);
	}
}
