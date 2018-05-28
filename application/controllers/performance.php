<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Performance extends CI_Controller {
	public $data = array();
	public $client_ip ;

    function __construct(){
      // Call the Controller constructor
        parent::__construct();
		
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");		
	
	$this->load->model('m_user');
	$this->load->model('m_performance');
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
	$this->client_ip = $this->config->config['client_ip'];

    }

	public function index($name){
		$ip = $this->client_ip;
		$this->data = $this->m_volume->get_volume_info($name);
		$vname_arr = $this->data['vname_arr'];
		if(!$name){
			$name = $vname_arr[0];
		}
		$this->data['profile'] = $this->m_performance->get_profile_info($ip, $name);
		//var_dump($this->data['profile']);
		$this->load->view('glusterfs/v_performance', $this->data);
	}
}
