<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xlator extends CI_Controller {
	public $data = array();
	public $client_ip;

    function __construct(){
      // Call the Controller constructor
        parent::__construct();
		
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");		
	
	$this->load->model('m_user');
	$this->load->model('m_xlator');
	$this->load->library('utilclass');
	
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

	public function index(){
		$this->data['xlator_list'] = $this->m_xlator->get_xlator_all();
		$this->load->view('glusterfs/v_xlator', $this->data);
	}
	public function add_xlator(){
		if(isset($_POST['name'])){
			$name = $_POST['name'];
			$open_command = $_POST['open_command'];
			$close_command = $_POST['close_command'];
			$sdata = array('name' => $name, 'open_command' => $open_command, 'close_command' => $close_command);
			$this->m_xlator->save_xlator_list($sdata);
		}
		redirect('/xlator');
	}
	public function xlator_do($type, $name){
		$data = $this->m_xlator->get_xlator_by_name($name);
		if(!count($data)){
			redirect('/xlator');
			return;
		}else{
			$com_arr =array();
			if($type == 'open'){
				$com_arr = explode(';', $data->open_command);
			}else{
				$com_arr = explode(';', $data->close_command);
			}
			$this->utilclass->ssh2_do_arr($this->client_ip, $com_arr);
			redirect('/xlator');
		}
	}
	public function delete($name){
		$this->m_xlator->delete_xlator_list($name);
		redirect('/xlator');
	}
}
