<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Breakdown extends CI_Controller {
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
		$this->data = $this->m_volume->get_volume_info(0);
		$this->data['storage_list'] = $this->m_cluster->get_usable_storage();
		$this->load->view('glusterfs/v_breakdown', $this->data);
	}
	public function volume_info($v_name){
		$this->data = $this->m_volume->get_volume_info($v_name);
		$this->data['storage_list'] = $this->m_cluster->get_usable_storage();
		$this->load->view('glusterfs/v_breakdown', $this->data);
	}
	public function replace(){
		//gluster volume replace-brick VOLNAME BRICK NEW-BRICK start/pause/abort/status/commit
		if(isset($_POST['brick_name'])){
			$com = 'gluster volume replace-brick ';
			$brick_name = $_POST['brick_name'];
			$place = $_POST['storage_id'];
			$volume_name = $_POST['volume_name'];
			$com .=  $volume_name.' ';
			$com .= $brick_name.' ';
			$com .= $place.' ';
			$com .= 'commit force';
			$res = $this->m_volume->handle_common_command($com);
			if($res){
				$this->m_cluster->update_storage_status($brick_name,  1);
				$this->m_cluster->update_storage_status($place,  0);
				$this->m_volume->up_volume_brick();
			}
			redirect('breakdown/volume_info/'.$volume_name);
		}else{
			redirect('breakdown/index');
		}
	}
}
