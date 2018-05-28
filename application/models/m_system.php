<?php
class M_system extends CI_Model {
    public $ftp_root;
    public $default;
    public $pxelinuxcfg;

    public function __construct()
    {
        parent::__construct();
        $this->ftp_root = "/var/ftp/pub";
        $this->pxelinuxcfg = "/var/lib/tftpboot/pxelinux.cfg/";
        $this->default = $this->getDefault();
    }

    public function listDir() {
        $str = "ls ".$this->ftp_root;
        $dirs = shell_exec($str);
        //把最后的一个换行符去掉，否则界面会多显示一个的
        $dirs = substr($dirs, 0, strlen($dirs)-1);
        $res = explode("\n" ,$dirs);
        return $res;
    }

    public function listSystems(){
        $res = array();
        $dirs = $this->listDir();
        foreach ($dirs as $dirname) {
            $item = array();
            $item['name'] = $dirname;
            if($dirname == $this->default){
                $item['isDefault'] = TRUE;
            }
            else{
                $item['isDefault'] = FALSE;
            }
            $filename = $this->ftp_root."/".$dirname."/desc.txt";
            if(file_exists($filename)){
                $item['desc'] = file_get_contents($filename);
            } else {
                $item['desc'] = "没有相关描述";
            }
            array_push($res,$item);
        }
        return $res;
    }
    
    public function changeDefault($systemName){
        $filename = $this->pxelinuxcfg."default";
        if(!file_exists($filename)){
            return;
        }
        $default = file_get_contents($filename);
        $isMatched = preg_match('{inst.stage2=ftp://.*?/pub/.*?/sourse}', $default, $matches);
        // print_r($matches);echo "<br>";
        $target = '/pub/'.$systemName.'/sourse';
        $tmp = preg_replace('{/pub/.*?/sourse}', $target, $matches[0]);
        // echo $tmp."<br>";
        $default = preg_replace('{inst.stage2=ftp://.*?/pub/.*?/sourse}', $tmp, $default);
        
        $isMatched = preg_match('{ks=ftp://.*?/pub/.*?/ks.cfg}', $default, $matches);
        $target = '/pub/'.$systemName.'/ks.cfg';
        $tmp = preg_replace('{/pub/.*?/ks.cfg}', $target, $matches[0]);
        $default = preg_replace('{ks=ftp://.*?/pub/.*?/ks.cfg}', $tmp, $default);

        $myfile = fopen($this->pxelinuxcfg."default", "w") or die("Unable to open file!");
        fwrite($myfile, $default);
        fclose($myfile);

        header('Location: '. base_url().'index.php/dashboard');
    }

    public function getDefault(){
        $filename = $this->pxelinuxcfg."default";
        if(!file_exists($filename)){
            return;
        }
        $default = file_get_contents($filename);
        preg_match('{inst.stage2=ftp://.*?/pub/.*?/sourse}', $default, $matches);
        preg_match('{pub/.*?/sourse}', $matches[0], $matches);
        preg_match('{/.*?/}', $matches[0], $matches);
        $defaultSystem = substr($matches[0], 1, strlen($matches[0]) - 2);
        return $defaultSystem;
    }

    public function getDefaultFtpIp(){
        $default = file_get_contents($this->pxelinuxcfg."default");
        preg_match('{inst.stage2=ftp://.*?/pub/.*?/sourse}', $default, $matches);
        preg_match('{ftp://.*?/pub}', $matches[0], $matches);
        preg_match('{//.*?/}', $matches[0], $matches);
        $DefaultFtp = substr($matches[0], 2, strlen($matches[0]) - 3);
        return $DefaultFtp;
    }
}