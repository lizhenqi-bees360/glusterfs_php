<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Volume extends CI_Controller { 
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

	$this->load->library('form_validation');
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
		$this->data['ctr_con_show'] = [1, 0, 0, 0, 0];
		$this->load->view('glusterfs/v_volume', $this->data);
	}
	public function add(){
		$com  = 'gluster volume create ';
		$post_data = array();
		$post_data['name'] = $this->input->post('name');
		$post_data['type_str'] = $this->input->post('type_str');
		$post_data['brick_count'] = $this->input->post('brick_count');
		$post_data['transport'] = $this->input->post('transport');
		$post_data['ip_list'] = array();
		if(isset($_POST['ip_list'])){
			$ip_list = $_POST['ip_list'];
			for ($i=0; $i < count($ip_list); $i++) { 
				if(!is_null($ip_list[$i])) {
					array_push($post_data['ip_list'], $ip_list[$i]);
				}
			}
			$com .= $post_data['name'].' ';
			if($post_data['type_str'] != 'distributed'){
				$com .= $post_data['type_str'].' '.$post_data['brick_count'].' ';
			}
			$com .= 'transport ';
			if($post_data['transport'] == 'tcprdma'){
				$com .= 'tcp,rdma ';
			}else{
				$com .= $post_data['transport'].' ';
			}
			for ($i=0; $i < count($post_data['ip_list']); $i++) { 
				$com .= $post_data['ip_list'][$i].' ';
			}
			$com .= 'force';

			$add_res = $this->m_volume->add_volume($com);
			if($add_res){
				$this->m_volume->up_volume_brick();
				//update storage use status
				for ($i=0; $i < count($post_data['ip_list']); $i++) { 
					$this->m_cluster->update_storage_status($post_data['ip_list'][$i],  1);
				}
				
				redirect("volume/volume_info/".$post_data['name']);
			}
			$post_data['add_res'] = $add_res;
			$post_data['com'] = $com;
		}
		
		$this->data['post_data'] = $post_data;

		$this->data['storage_list'] = $this->m_cluster->get_usable_storage();
		$this->data['ctr_con_show'] = [0, 1, 0, 0, 0];
		$this->data['volume_name'] = 'test';
		$this->load->view('glusterfs/v_volume', $this->data);
	}
	public function update($v_name){
		$this->data = $this->m_volume->get_volume_info($v_name);
		$this->data['storage_list'] = $this->m_cluster->get_usable_storage();
		$this->data['ctr_con_show'] = [0, 0, 1, 0, 0];
		$this->load->view('glusterfs/v_volume', $this->data);
	}
	public function replace($v_name){
		if( isset($_POST['repalce_list']) ){
			// gluster peer probe 10.0.21.245 # 将10.0.21.246数据迁移到10.0.21.245先将10.0.21.245加入集群 
			// gluster volume replace-brick gv0 10.0.21.246:/data/glusterfs 10.0.21.245:/data/glusterfs start # 开始迁移 
			// gluster volume replace-brick gv0 10.0.21.246:/data/glusterfs 10.0.21.245:/data/glusterfs status # 查看迁移状态 
			// gluster volume replace-brick gv0 10.0.21.246:/data/glusterfs 10.0.21.245:/data/glusterfs commit # 数据迁移完毕后提交 
			// gluster volume replace-brick gv0 10.0.21.246:/data/glusterfs 10.0.21.245:/data/glusterfs commit -force # 如果机器10.0.21.246出现故障已经不能运行,执行强制提交 
			// gluster volume heal gv0 full # 同步整个卷
			var_dump( $_POST['repalce_list']);
		}
		$this->data = $this->m_volume->get_volume_info($v_name);
		$this->data['storage_list'] = $this->m_cluster->get_usable_storage();
		$this->data['ctr_con_show'] = [0, 0, 0, 1, 0];
		$this->load->view('glusterfs/v_volume', $this->data);
	}
	public function volume_info($v_name){
		$this->data = $this->m_volume->get_volume_info($v_name);
		$this->data['ctr_con_show'] = [1, 0, 0, 0, 0];
		$this->load->view('glusterfs/v_volume', $this->data);
	}
	public function update_status($volume_name, $action){
		$com = 'gluster volume '.$action.' '.$volume_name;
		// delete volume
		if($action == 'delete'){
			$com_stop = 'gluster volume stop '.$volume_name.' force';
			// @important
			$res_stop = $this->m_volume->handle_ask_command($com_stop);
			$res_delete = $this->m_volume->handle_ask_command($com);
			if($res_delete){
				$this->m_volume->handle_delete_volume($volume_name, $this->m_cluster);
			}
			redirect('volume/update/0');
		}else{ // start or stop volume

			// @important
			$com .= ' force';
			if($action == 'stop'){
				$res_change = $this->m_volume->handle_ask_command($com);
				$status_str = 'Stopped';
			}else{
				$res_change = $this->m_volume->handle_common_command($com);
				$status_str = 'Started';
			}
			if($res_change){
				$this->m_volume->update_volume_status($volume_name, $status_str);
			}
			redirect('/volume/update/'.$volume_name);
		}
	}
	public function reduce_dir(){
		$com_start = 'gluster volume remove-brick ';
		$com_status = 'gluster volume remove-brick ';
		$com_commit = 'gluster volume remove-brick ';
		// gluster volume remove-brick gv0 10.0.21.243:/data/glusterfs 10.0.21.244:/data/glusterfs start # 开始迁移 
		// gluster volume remove-brick gv0 10.0.21.243:/data/glusterfs 10.0.21.244:/data/glusterfs status # 查看迁移状态 
		// gluster volume remove-brick gv0 10.0.21.243:/data/glusterfs 10.0.21.244:/data/glusterfs commit # 迁移完成后提交
		$post_data = array();
		if(isset($_POST['brick_list'])){
			$brick_list = $_POST['brick_list'];
			$volume_name = $_POST['volume_name'];
			$com_start .= $volume_name . ' ';
			$com_status .= $volume_name . ' ';
			$com_commit .= $volume_name . ' ';
			for ($i=0; $i < count($brick_list); $i++) { 
				$com_start .= $brick_list[$i] . ' ';
				$com_status .= $brick_list[$i] . ' ';
				$com_commit .= $brick_list[$i] . ' ';
			}
			$com_start .=  'start';
			$com_status .=   'status';
			$com_commit .=  'force';
			//@important
			// $this->m_volume->handle_common_command($com_start);
			// $this->m_volume->handle_common_command($com_status);
			$res = $this->m_volume->handle_ask_command($com_commit);
			if($res){
				for ($i=0; $i < count($brick_list); $i++) { 
					$this->m_volume->delete_brick_list($brick_list[$i]);
					$this->m_cluster->update_storage_status($brick_list[$i],  0);
				}
			}
			redirect('/volume/update/'.$volume_name);
		}
		
	}
	public function add_dir(){
		//gluster volume add-brick gv0 10.0.21.243:/data/glusterfs 10.0.21.244:/data/glusterfs # 合并卷
		$com = 'gluster volume add-brick ';
		if(isset($_POST['brick_dir'])){
			$brick_dir = $_POST['brick_dir'];
			$volume_name = $_POST['volume_name'];
			$com .= $volume_name.' ';
			for ($i=0; $i < count($brick_dir); $i++) { 
				$com .= $brick_dir[$i].' ';
			}
			$com .= 'force';
			$res = $this->m_volume->handle_common_command($com);
			if($res){
				//update storage use status
				for ($i=0; $i < count($brick_dir); $i++) { 
					$this->m_cluster->update_storage_status($brick_dir[$i],  1);
				}
				$this->m_volume->up_volume_brick();
			}			
			redirect('/volume/update/'.$volume_name);
		}else{
			redirect('/volume/update/0');
		}
		
	}
	public function volume_refresh(){
		$this->m_volume->up_volume_brick();
		redirect('volume');
	}


}
