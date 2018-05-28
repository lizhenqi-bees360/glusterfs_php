<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_performance extends CI_Model {

	public $volume_table = 'volume_list';
	public $max_idle_time = 300; // allowed idle time in secs, 300 secs = 5 minute
	
	function __construct(){
	        // Call the Model constructor
	        parent::__construct();
	        $this->load->library('utilclass');
	}	
	
	function get_profile_info($ip, $vol_name){
		$data = array();
		//gluster volume profile vol_name info
		$str = 'gluster volume profile '.$vol_name.' info --xml';
		$file_name = dirname(dirname(dirname(__FILE__)))."/xml/".$str.".xml"; 
		if(!file_exists($file_name)){
			$this->utilclass->ssh2_do($ip, $str, $file_name);
		}
		$xml = simplexml_load_file($file_name);

		//volume profile has not started
		$vol_error = (String)$xml->opErrstr;
		//var_dump($vol_error);
		if(!!strrpos($vol_error, 'not started') && $vol_error){
			$res_start = $this->start_vol_profile($ip, $vol_name);
			if($res_start){
				$this->utilclass->ssh2_do($ip, $str, $file_name);
				$xml = simplexml_load_file($file_name);
			}
		}
		$vol_profile = $xml->volProfile;
		$brick_count = (Int)$vol_profile->brickCount;
		for ($i=0; $i < $brick_count; $i++) { 
			$profile =array('brick_name' => '', 'cumulative' => array('fop_stats' => array(), 'duration' => '', 'total_read' => '', 'total_write' => ''), 'interval' => array('fop_stats' => array(), 'duration' => '', 'total_read' => '', 'total_write' => '') );
			$profile['brick_name'] = (String)$vol_profile->brick[$i]->brickName;
			//cumulativeStats
			$cumu = $vol_profile->brick[$i]->cumulativeStats;
			$fop_stats = $cumu->fopStats;
			for($j = 0; $j < count($fop_stats->fop); $j ++){
				$profile['cumulative']['fop_stats'][$j]['name'] = (String)$fop_stats->fop[$j]->name;
				$profile['cumulative']['fop_stats'][$j]['hits'] = (String)$fop_stats->fop[$j]->hits;
				$profile['cumulative']['fop_stats'][$j]['avg_latency'] = (String)$fop_stats->fop[$j]->avgLatency;
				$profile['cumulative']['fop_stats'][$j]['min_latency'] = (String)$fop_stats->fop[$j]->minLatency;
				$profile['cumulative']['fop_stats'][$j]['max_latency'] = (String)$fop_stats->fop[$j]->maxLatency;
			}
			$profile['cumulative']['duration'] = (String)$cumu->duration;
			$profile['cumulative']['total_read'] = (String)$cumu->totalRead;
			$profile['cumulative']['total_write'] = (String)$cumu->totalWrite;

			//interval
			$interval = $vol_profile->brick[$i]->intervalStats;
			$fop_stats = $interval->fopStats;
			for($j = 0; $j < count($fop_stats->fop); $j ++){
				$profile['interval']['fop_stats'][$j]['name'] = (String)$fop_stats->fop[$j]->name;
				$profile['interval']['fop_stats'][$j]['hits'] = (String)$fop_stats->fop[$j]->hits;
				$profile['interval']['fop_stats'][$j]['avg_latency'] = (String)$fop_stats->fop[$j]->avgLatency;
				$profile['interval']['fop_stats'][$j]['min_latency'] = (String)$fop_stats->fop[$j]->minLatency;
				$profile['interval']['fop_stats'][$j]['max_latency'] = (String)$fop_stats->fop[$j]->maxLatency;
			}
			$profile['interval']['duration'] = (String)$interval->duration;
			$profile['interval']['total_read'] = (String)$interval->totalRead;
			$profile['interval']['total_write'] = (String)$interval->totalWrite;
			array_push($data, $profile);
		}
		return $data;
	}
	function start_vol_profile($ip, $vol_name){
		$str = 'gluster volume profile '.$vol_name.' start';
		$file_name = false; 
		$res = $this->utilclass->ssh2_do($ip, $str, $file_name);
		return $this->utilclass->jugde_peer_status($res);
	}
}

/* End of file m_account.php */
/* Location: ./application/models/m_account.php */