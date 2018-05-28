<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cluster extends CI_Controller {
	public $data = array();
	public $hldata = array();

    	function __construct(){
	      	// Call the Controller constructor
	        parent::__construct();
			
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");	
		session_start();

		$this->load->library('form_validation');
		$this->load->model('m_user');
		$this->load->model('m_cluster');
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
		// if(isset( $this->session->userdata('feedback')){
		// 	$this->data['feedback'] = $this->session->userdata('feedback');
		// 	var_dump( $this->session->userdata('feedback'));
		// }

		// $host_list = '192.168.150.181,192.168.150.135';
		// $host_arr = explode(',',  $host_list);
		// $this->m_cluster->gluster_add($host_arr);

		//init ctr_con_show
		$this->data['ctr_con_show'] = [1, 0];

		$all_info = $this->m_cluster->get_all_info();
		$this->data['all_info'] = $all_info;

		$this->load->view('glusterfs/v_cluster', $this->data);
	}
	public function auto_ping(){
		$begin_ip = $this->input->post('begin_ip');
		$end_ip = $this->input->post('end_ip');
		$b_arr = explode('.', $begin_ip);
		$e_arr = explode('.', $end_ip);
		if(count($b_arr)  != 4 || count($e_arr) != 4){
			$_SESSION['feedback'] = array('msg' => 'IP错误，请重新输入！','is_show' => true);
		}else{
			$this->m_cluster->auto_ping($begin_ip, $end_ip);
		}
		redirect('cluster');
	}
	public function sigle_ping(){
		$ip = $this->input->post('sigle_ping_ip');
		$ip_arr = explode('.', $ip);
		if(count($ip_arr)  != 4){
			$_SESSION['feedback'] = array('msg' => 'IP错误，请重新输入！','is_show' => true);
		}else{
			$this->m_cluster->sigle_ping($ip);
		}
		redirect('cluster');
	}
	public function file_ping(){
		$filename = $_FILES['ping_file']['tmp_name'];
		$size = $_FILES['ping_file']['size'];
		if($size < 0){
			$_SESSION['feedback'] = array('msg' => '文件为空，请重新选择！','is_show' => true);
		}else{
			$this->m_cluster->file_ping($filename);
		}
		redirect('cluster');
	}
	//错误处理/前端控制为up的才能添加进来 @TODO
	public function host_add(){
		header('Content-type:text/json;charset=utf-8');
		$code = "success";
		$msg = "Add host success!";
		if(isset($_POST['ip_list'])){
			$host_arr = explode(',', $_POST['ip_list']);
			$this->m_cluster->host_add($host_arr);
		}else{
			$code = "failed";
			$msg = "Add host failed!";
		}
		$data = array('code' => $code, 'msg' => $msg);
		$this->output->set_content_type('application/json')
             ->set_output(json_encode($data));
		return; //important
	}
	//@TODO
	public function auto_add(){
		redirect('cluster');
	}
	public function host_delete(){
		header('Content-type:text/json;charset=utf-8');
		$code = "success";
		$msg = "Delete host success!";
		if(isset($_POST['ip_list'])){
			$host_arr = explode(',', $_POST['ip_list']);
			$this->m_cluster->host_delete($host_arr);
		}else{
			$code = "failed";
			$msg = "Add host failed!";
		}
		$data = array('code' => $code, 'msg' => $msg);
		$this->output->set_content_type('application/json')
             ->set_output(json_encode($data));
		return; //important
	}

	/**********************gluster********************/
	public function gluster_delete(){
		header('Content-type:text/json;charset=utf-8');
		$code = "success";
		$msg = "Delete success!";
		if(isset($_POST['ip_list'])){
			$gluster_arr = explode(',', $_POST['ip_list']);
			$this->m_cluster->gluster_delete($gluster_arr);
		}else{
			$code = "failed";
			$msg = "Delete failed!";
		}
		$data = array('code' => $code, 'msg' => $msg);
		$this->output->set_content_type('application/json')
             ->set_output(json_encode($data));
		return; //important
	}
	public function gluster_add(){
		header('Content-type:text/json;charset=utf-8');
		$code = "success";
		$msg = "Add host success!";
		if(isset($_POST['ip_list'])){
			$gluster_arr = explode(',', $_POST['ip_list']);
			$this->m_cluster->gluster_add($gluster_arr);
		}else{
			$code = "failed";
			$msg = "Add host failed!";
		}
		$data = array('code' => $code, 'msg' => $msg);
		$this->output->set_content_type('application/json')
             ->set_output(json_encode($data));
		return; //important
	}
	/**********************node list********************/
	public function node_delete(){
		$code = "success";
		$msg = "Delete node success!";
		if(isset($_POST['ip_list'])){
			$node_arr = explode(',', $_POST['ip_list']);
			$dir_num = 0;
			for ($i=0; $i < count($node_arr); $i++) { 
				$dir_num = $this->m_cluster->get_directory_num($node_arr[$i]);
				if($dir_num){
					break;
				}
			}
			if($dir_num){
				$code = "failed";
				$msg = '主机'.$node_arr[$i].'还有brick，请移除再操作.';
			}else{
				$res =$this->m_cluster->node_delete($node_arr);
			}
		}else{
			$code = "failed";
			$msg = "Delete failed!";
			$res = 'error';
		}
		$data = array('code' => $code, 'msg' => $msg);
		$this->output->set_content_type('application/json')
             ->set_output(json_encode($data));
		return; //important
	}

	/**********************storage refresh********************/
	public function storage(){	
		$this->data['directory'] = $this->m_cluster->get_directory();	
		$this->data['storage_list'] = $this->m_cluster->get_all_storage();
		//init ctr_con_show
		$this->data['ctr_con_show'] = [ 0, 1];
		$this->load->view('glusterfs/v_cluster', $this->data);
	}
	public function storage_new(){
		if(isset($_POST['storage_ip'])){
			$ip = $_POST['storage_ip'];
			$place = $_POST['storage_place'];
			$is_used = 0;
			$id = $ip.':'.$place;
			$command = 'mkdir -p '.$place;
			$res = $this->m_cluster->storage_new($ip, $command);
			if($res){
				$sdata = array('id' => $id, 'ip' => $ip, 'place' => $place, 'is_used' => $is_used);
				$this->m_cluster->save_storage_list($sdata);
			}
		}
		redirect('cluster/storage');
	}

}
