<?php
require('_/modules/welcome/controllers/block.php');

class Createthumb extends Welcome{
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $rootDir = "assets/upload/intranet";
		$allowext = array("jpg","png","JPG");
		$files_array =  $this->scanDirectories($rootDir,$allowext);
		print_r($files_array);
    }
	private function scanDirectories($rootDir, $allowext, $allData=array()) {
		
		$dirContent = scandir($rootDir);
		foreach($dirContent as $key => $content) {
			$path = $rootDir.'/'.$content;
			$ext = substr($content, strrpos($content, '.') + 1);
			if(in_array($ext, $allowext)) {
				if(is_file($path) && is_readable($path)) {
					image_thumb_w($path,65);
					image_thumb_w($path,145);
					image_thumb_w($path,175);
					image_thumb_w($path,255);
					image_thumb_w($path,450);
					image_thumb_w($path,600);
					$allData[] = $path;
				}elseif(is_dir($path) && is_readable($path)) {
					// recursive callback to open new directory
					$allData = scanDirectories($path, $allData);
				}
			}
		}
		return $allData;
	}
}
