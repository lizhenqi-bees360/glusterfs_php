<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
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
	$this->load->model('m_volume');
	$this->load->model('m_cluster');
	
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
		$this->data['storage_num'] = $this->m_cluster->get_storage_count();
		$this->data['user_num'] = $this->m_user->get_user_count();
		$this->data['volume_num'] = $this->m_volume->get_volume_count();
		$this->data['brick_num'] = $this->m_volume->get_brick_count();
		$this->load->view('glusterfs/v_home', $this->data);
	}
}
