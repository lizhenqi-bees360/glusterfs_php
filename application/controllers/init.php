<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Init extends CI_Controller {
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
	$this->load->model('m_system');
	$this->load->helper('url');
	
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
		$this->data['system_list'] = array();
		// $this->data['system_list'] = $this->init_data();
		$this->data['system_list'] = $this->m_system->listSystems();
		$this->load->view('glusterfs/v_init', $this->data);
	}
	public function upload(){
		//$this->data['system_list'] = $this->m_system->listSystems();
		$this->data['system_list'] = $this->init_data();
		redirect('init');
	}

	public function setDefaultAs($system_name)
	{
		//$this->m_system->changeDefault($system_name);
	}
	public function init_data(){
		$list = array();
		for ($i=0; $i < 3; $i++) { 
			# code...
			$list[$i] = array('name' => 'Centos7.'.$i, 'desc' => 'No Description', 'isDefault' => false);
		}
		$list[0]['isDefault'] = true;
		return $list;
	}
}
