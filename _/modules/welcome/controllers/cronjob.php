<?php
require('_/modules/welcome/controllers/block.php');

class Cronjob extends Welcome{
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
       $sql = "CREATE TABLE ifrc_article_".date("Gi", time())." AS SELECT * FROM ifrc_articles";
	   $data = $this->db->query($sql);
    }
	public function data_report(){
		if(isset($_POST['dataArr'])){
			$idArr = implode(',',$_POST['dataArr']);
			$sql = "Select dr.*, cn.`user`, cn.`pass`,cn.`database`, cn.`ip_host` FROM data_report as dr LEFT JOIN connection as cn ON dr.connection= cn.domain and dr.type=cn
.type  WHERE dr.id in ({$idArr})";
		}
		else {
			$sql = "Select dr.*, cn.`user`, cn.`pass`,cn.`database`, cn.`ip_host` FROM data_report as dr LEFT JOIN connection as cn ON dr.connection= cn.domain and dr.type=cn
.type ";
		}
		

        $data = $this->db->query($sql)->result_array();
		
		foreach($data as $value){
			if(strtoupper($value["type"])=='FTP'){
				$id = $value["id"];
				$user = base64_decode($value["user"]);
				$password = base64_decode($value["pass"]);
				$path_name = $value["location"].'/'.$value["name"];
				$connection = $value["connection"];
				$curl = curl_init("http://{$user}:{$password}@{$connection}{$path_name}");
				$start_time_calcul =microtime(true);
				//don't fetch the actual page, you only want headers
				curl_setopt($curl, CURLOPT_NOBODY, true);
				
				//stop it from outputting stuff to stdout
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				
				// attempt to retrieve the modification date
				curl_setopt($curl, CURLOPT_FILETIME, true);
				
				$result = curl_exec($curl);
				
				if ($result === false) {
					die (curl_error($curl)); 
				}
				
				$timestamp = curl_getinfo($curl, CURLINFO_FILETIME);
				$size = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				curl_close($curl);
				if ($timestamp != -1) { //otherwise unknown
					$date = date("Y-m-d H:i:s", $timestamp); //etc
				} 
				$diff_days= $this->number_of_working_days($date,date('Y-m-d',strtotime("now"))) -1;				
				if($value["execution"]=='E' && ("17:00" >= date('H:i'))) 
				$delay =  (int)$value["delay"] + 1;
				else $delay = (int)$value["delay"];
				if($diff_days <= $delay) $status = 1;
				else $status =0;
				
				$update = date('Y-m-d H:i:s',strtotime("now"));
				$sql = "update data_report set `size` = '$size', `date` = '$date', `mindate`=NULL, `update` = '$update', `status`= '$status', `time_update`='".round((microtime(true) - $start_time_calcul), 3)."' where `id` = '$id'";
        		$result = $this->db->query($sql);
			}
			else if(strtoupper($value["type"])=='TABLE'){
				$id = $value["id"];
				/*$user = base64_decode($value["user"]);
				$password = base64_decode($value["pass"]);
				$database = base64_decode($value["database"]);*/
				$table = $value["name"];
				$connection = $value["connection"];
				$host = $value["ip_host"]; // If you don't know what your host is, it's safe to leave it localhost
				$dbName =base64_decode($value["database"]); // Database name
				$dbUser = base64_decode($value["user"]); // Username
				$dbPass = base64_decode($value["pass"]); // Password
				$dbh = new PDO("mysql:host={$host};dbname={$dbName}", $dbUser, $dbPass,array( PDO::ATTR_PERSISTENT => false, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true, PDO::MYSQL_ATTR_LOCAL_INFILE =>true));
				$dbh->exec("set names utf8");
				$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
				//max date
				$start_time_calcul =microtime(true);
				$data = $dbh->query("select max(`date`) as date, min(`date`) as mindate from ".$table." where `date` !='0000-00-00'");
				$arr_date = array();
				foreach ($data as $row) {  
					 //print_r($row)."\n";
					 $arr_date[]=$row;
				} 
				$data = null; // doing this is mandatory for connection to get closed 
				$date = $arr_date[0]["date"];
				$date_min = $arr_date[0]["mindate"];
				//total record
				$data = $dbh->query("SELECT COUNT(*) as total FROM  ".$table);
				
				$arr_record = array();
				foreach ($data as $row) {  
					 //print_r($row)."\n";
					 $arr_record[]=$row;
				} 
				$data = null; // doing this is mandatory for connection to get closed
				$total = $arr_record[0]["total"];
				//number recodr by query
				$number ='';
				if($value["query"]!='' && !is_null($value["query"])){
					$data = $dbh->query("SELECT COUNT(*) as total FROM ( ".$value["query"]." ) as a");					
					$arr_number = array();
					foreach ($data as $row) {  
						 //print_r($row)."\n";
						 $arr_number[]=$row;
					} 
					$data = null; // doing this is mandatory for connection to get closed
					$number = "`number`='".$arr_number[0]["total"]."'," ;
				}
				else {
					$number = "`number`= NULL," ;
				}
				//status
				$diff_days= $this->number_of_working_days($date,date('Y-m-d',strtotime("now"))) -1;
				if($value["execution"]=='E' && ("17:00" >= date('H:i'))) 
				$delay =  (int)$value["delay"] + 1;
				else $delay = (int)$value["delay"];
				if($diff_days <= $delay) $status = 1;
				else $status =0;
				/*if ($date + $value["delay"] >= date('Y-m-d',strtotime("now"))) $status = 1;
				else $status =0;*/
				//update table data_report
				$update = date('Y-m-d H:i:s',strtotime("now"));
				$sql = "update data_report set `records` = '$total', $number `date` = '$date', `mindate`='$date_min', `update` = '$update', `status`= '$status' , `time_update`='".round((microtime(true) - $start_time_calcul), 3)."' where `id` = '$id'";				
				unset($dbh);
				$dbh = null;
				sleep(3);
        		$result = $this->db->query($sql);
			}
			/*else if (strtoupper($value["type"])=='FILE') {
				
				$id = $value["id"];
				$user = base64_decode($value["user"]);
				$password = base64_decode($value["pass"]);
				$path_name = $value["location"].'/'.$value["name"];
				$connection = $value["connection"];
				//$handle = "U:\EFRC\WEBSITE\UPLOAD\STK_MDATA_QUARTER.TXT";
				
				$drive_letter = "M";
				
				system("net use ".$drive_letter.": \"".$connection."\" ".$password." /user:".$password." /persistent:no>nul 2>&1");
				$location = $drive_letter.":$path_name";
				
				$handle = fopen($location,'r');
				if (file_exists($location)) {
					
					$date = date ("Y-m-d", filemtime($location));
					$size = filesize($location). ' KB';
					if ($date + $value["delay"] >= date('Y-m-d',strtotime("now"))) $status = 1;
					else $status =0;
					$update = date('Y-m-d H:i:s',strtotime("now"));
					$sql = "update data_report set `size` = '$size', `date` = '$date', `update` = '$update', `status`= '$status' where `id` = '$id'";
					$result = $this->db->query($sql);
				}
			
			}*/
            
        }
		return $result;
		 
	}
	 function runQuery(){
        
        $idArr = implode(',',$_POST['dataArr']);
		
        $sql = "Select * FROM int_query WHERE id in ({$idArr})";
		
        $data = $this->db->query($sql)->result_array();
        $query = '';
        foreach($data as $value){
            $query .= $value['tquery'];
        }
		$query = explode(";", $query);
    
        //run commands
        $total = $success = 0;
		$result = array();
        foreach($query as $command){
			$command = trim($command);
            if($command!=''){
				if($this->db->simple_query($command)==false){
					$result["message"] = $command;
				}
				else {
					 $success ++;
				}
                
                $total += 1;
            }
        }
		$result["total"] = $total;
		$result["success"] = $success;
        $this->output->set_output(json_encode($result));
        //print_R($query);
    }
	private function  number_of_working_days($from, $to) {
		$workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
		$holidayDays = array(); # variable and fixed holidays
	
		$from = new DateTime($from);
		$to = new DateTime($to);
		$to->modify('+1 day');
		$interval = new DateInterval('P1D');
		$periods = new DatePeriod($from, $interval, $to);
	
		$days = 0;
		foreach ($periods as $period) {
			if (!in_array($period->format('N'), $workingDays)) continue;
			if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
			if (in_array($period->format('*-m-d'), $holidayDays)) continue;
			$days++;
		}
		return $days;
	}
}
