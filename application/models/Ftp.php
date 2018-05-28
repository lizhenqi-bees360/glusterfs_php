<?php
//这个现在用不着了，
class Ftp extends CI_Model {
	public $conn_id; 

    public function connect($ftp_server = '127.0.0.1', $ftp_user_name = 'Annoymous', $ftp_user_pass = ''){
    	// 建立基础连接
		$conn_id = ftp_connect($ftp_server); 
		$this->conn_id = $conn_id;
		// 使用用户名和口令登录
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

		// 检查是否成功
		if ((!$conn_id) || (!$login_result)) { 
		    return FALSE;
		} else {
		    return TRUE;
		}
    }

   	public function upload(){
   		$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 

		// 检查上传结果
		if (!$upload) { 
		    echo "FTP upload has failed!";
		} else {
		    echo "Uploaded $source_file to $ftp_server as $destination_file";
		}
   	}

   	public function list($dir = "pub"){
   		$contents = ftp_mlsd($conn_id, $dir);

   	}

   	public function file_read($server_file){ 		
		$local_file = '/tmp/ftp_file_tmp';
   		$res = ftp_get($this->conn_id, $local_file, $server_file, FTP_BINARY);
		if($res == FALSE) return "";
		else return file_get_contents($local_file);
   	}

   	public function file_write($server_file, $contents){ 	
   		//firstly save the contents as a file	
		$local_file = '/tmp/ftp_file_tmp';
		$myfile = fopen($local_file, "w");
        fwrite($myfile, $contents);
   		fclose($myfile);

   		//then put the file saved to the ftp server
		$fp = fopen($local_file, 'r');
		if (ftp_fput($this->conn_id, $server_file, $fp, FTP_BINARY)) {
			fclose($fp);
		    return TRUE;
		} else {
		    return FALSE;
		}
   	}

}