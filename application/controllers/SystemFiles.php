<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class SystemFiles extends REST_Controller{

	public function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
        $this->load->model('m_system');
        $this->load->model('Ftp');
        $this->Ftp->connect("127.0.0.1","ftpuser","test");
    }


	function index_get()
	{   
		$this->Ftp->file_write("pub/test.txt","hello world");
		$res = array("a",$this->Ftp->file_read("pub/test.txt"),$this->m_system->getDefaultFtpIp(), (array)array(1, 2));
		$this->response($res, 200);
	}

}	