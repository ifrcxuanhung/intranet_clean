<?php
class Ajax extends Welcome{
	
	protected $var_image ='';
	protected $var_files ='';

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('ion_auth');
        $this->load->model('Table_model', 'table');
        $this->load->database();
    }
    
    public function index() {
        //echo "test test";
    }
    
    public function create_session_menu() {
        $id_menu = $_POST['id_menu'];	
        $this->session->set_userdata('id_menu', $id_menu);
        exit();
    }
    
    public function change_language()
    {
        $langcode = $_POST['langcode'];
        $sql = "SELECT * FROM `language` WHERE `code` = '".$langcode."'";
        $data = $this->db->query($sql)->result_array();
        $this->session->set_userdata(array('curent_language'=>$data[0]));
    }
    
    public function loadtable(){
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
		$table = isset($_REQUEST['name_table'])? $_REQUEST['name_table'] :'';
		
		$category = isset($_POST['category'])? strtoupper($_POST['category']) :'';
		$arr_table_sys = $this->table->get_summary($table);
		
		$table_sys = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
		// select columns
		$where = "where 1=1 ";
       
		$headers = $this->table->get_headers($table_sys);
		$aColumns = array();
		foreach ($headers as $item) {
	        if($item['format_n'] == 'group'){
	              $aColumns[] = '`'.strtolower($item['field'].'_frt').'`,`'.strtolower($item['field'].'_bcd').'`,`'.strtolower($item['field'].'_hst').'`';
	        }else{  
			     $aColumns[] = '`'.strtolower($item['field']).'`';
            }
			$field_post = $this->input->post(strtolower($item['field']));
			$field_post_html = $this->input->post(strtolower("html_".$item['field']));
			if(($field_post!='') || ($field_post_html!='')) {
				$field_post = $field_post !='empty' ? $field_post :'';
				if((strpos(strtolower($item['type']),'list')!==false)){
					if($field_post != 'all') {
							$where .= " and `{$item['field']}` = '".$field_post."'";    
						}
				}
				else {
					switch(strtolower($item['type'])) {
						case 'varchar':
						case 'longtext':
						case 'int':
						case 'link':
							$where .= " and `{$item['field']}` like '%".$field_post."%'";
							break;
						case 'html':
						case 'info':
							$where .= " and `{$item['field']}` like '%".$field_post_html."%'";
							break;
						case 'list':
							if($field_post != 'all') {
								$where .= " and `{$item['field']}` = '".$field_post."'";    
							}
							break;
						default:
							break;
					}
				}
			}else if($this->input->post(strtolower($item['field'].'_start')) and strtotime($this->input->post(strtolower($item['field'].'_start')))) {
				$where .= " and `{$item['field']}` >= '".real_escape_string($this->input->post(strtolower($item['field'].'_start')))."'";
			} 
			 else if($this->input->post(strtolower($item['field'].'_from'))) {
				$where .= " and `{$item['field']}` >= ".(int)($this->input->post(strtolower($item['field'].'_from')))."";
			}
			if($this->input->post(strtolower($item['field'].'_end')) and strtotime($this->input->post(strtolower($item['field'].'_end')))){
				 $where .= " and `{$item['field']}` <= '".real_escape_string($this->input->post(strtolower($item['field'].'_end')))."'";
			}
			if($this->input->post(strtolower($item['field'].'_to'))) {
				$where .= " and `{$item['field']}` <= ".(int)($this->input->post(strtolower($item['field'].'_to')))."";
			}
             
		}
		if($category != 'ALL' && $category != '') $where .= " and `category` = '".$category."'";
		
		$sTable = $table_sys; //$category == 'all' ? "int_".$table : "(SELECT * FROM int_".$table." where category = '".$category."') as sTable " ;		
		$sqlColumn = "SHOW COLUMNS FROM {$sTable};";  
		$arrColumn = $this->db->query($sqlColumn)->result_array(); 
		foreach ($arrColumn as $item){		 	
			if(!$this->input->post(strtolower($item['Field'])) && isset($_GET[$item['Field']]) && ($_GET[$item['Field']]!='all') && (strtolower($item['Field']!='tab'))){
				 $where .= " and `{$item['Field']}` = '".$_GET[$item['Field']]."'";
				 
			}
		 }
		// order
		$order_by ='';	
		if (isset($_REQUEST['order'][0]['column'])) {
			if(($table =='data_report')|| ($table =='query')){
				$iSortCol_0 = $_REQUEST['order'][0]['column']-1;
				$sSortDir_0 = $_REQUEST['order'][0]['dir'];
			}
			else {
				$iSortCol_0 = $_REQUEST['order'][0]['column'];
				$sSortDir_0 = $_REQUEST['order'][0]['dir'];
			}
			foreach($aColumns as $key => $value) {
				if($iSortCol_0 == $key) {                    
					$order_by = " order by $value ".$sSortDir_0;
					break;
				}
			}
		}
		$order_by .= (($arr_table_sys['order_by']!='') && (!is_null($arr_table_sys['order_by'])))?($order_by =='' ? ('order by '.$arr_table_sys['order_by']):(','.$arr_table_sys['order_by'])):'';
        
        $sqlkey = "SELECT `keys`, `user_level` FROM int_summary WHERE `tab` = '{$table_sys}'";
		$keyARR = $this->db->query($sqlkey)->row_array();
		
        if(isset($keyARR)){
            if(isset($keyARR['keys'])&&$keyARR['keys'] != ''){
        		$keyARR = isset($keyARR) ? $keyARR: array();
        		$arr = explode(',',$keyARR['keys']);
        		foreach($arr as $v){ 
        			$aa[] = '`'.TRIM($v).'`';
        		}   
        		$rs = in_array($aa, $aColumns, TRUE) ?  $aColumns : array_merge((array)$aa, (array)$aColumns);
                $ke = explode(',',$keyARR['keys']);	
            }else{
                $rs = $aColumns;
            }  
        }
        $sql = "select sql_calc_found_rows *
    					from {$sTable} {$where} {$order_by};";    
	//	print_R($order_by);exit;			
		$data = $this->db->query($sql)->result_array();
		
		//$data = $this->db->query($sql)->result_array();  
		$arr_data = array();
		$i =0;
		foreach ($data as $key => $value) {
			$arr_data[$i] = $value;	
			$i++;		
		}
		//var_export($arr_data);
		$iFilteredTotal = $i;
		$iTotalRecords = $iFilteredTotal;
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
           //print_R()
		   
		//$data = $this->db->query($sql)->result_array();   
		$records = array();
		$records["data"] = array();
        //PRINT_r($data);EXIT;
		$key = 0;
		for ($x = $iDisplayStart; $x < $iDisplayStart + $iDisplayLength; $x++) {
			if(isset($arr_data[$x])) {
				$path = base_url().'assets/upload/intranet';
				if(isset($arr_data[$x]['group']) && $arr_data[$x]['group']!='') $path .='/'.strtolower($arr_data[$x]['group']);
				if(isset($arr_data[$x]['owner']) && $arr_data[$x]['owner']!='') $path .='/'.strtolower($arr_data[$x]['owner']);
				if(isset($arr_data[$x]['category']) && $arr_data[$x]['category']!='') $path .='/'.strtolower($arr_data[$x]['category']);
				if(isset($arr_data[$x]['subcat']) && $arr_data[$x]['subcat']!='') $path .='/'.strtolower($arr_data[$x]['subcat']);
                if($table == 'query' || $table == 'data_report'){
			         $records["data"][$key][] = '<input type="checkbox" class="checkboxes" value="'.$arr_data[$x]['id'].'">';	// check s
                }
				
				foreach($headers as $item) {
					if($table == 'data_report' && $item['url']!=''){
						$arr_url = explode("|",$item['url']);			 
						foreach($arr_url as $item_url) {
							$url_task = str_replace(" ","",$item_url);
							$pos_conn = strpos($url_task,$arr_data[$x]["connection"]);
							if ($pos_conn !== false) {
								$pos_inside = strpos($url_task,"INLIST");
								if ($pos_inside !== false) {
									$url_task = substr($url_task,0, $pos_inside);
								}
								break;	
							}
							else {
								$pos_inside = strpos($url_task,"INLIST");
								if ($pos_inside !== false) {
									$url_task = "";
								}
							}
						} 
						if($url_task!='')
						{
							if (strpos($url_task,'http')!==FALSE||strpos($url_task,'https')!==FALSE) {
								$url_task = $url_task;
							}
							else {
								$url_task = base_url().$url_task;
							}
							foreach($headers as $column_task) {
								$url_task = str_replace('@'.strtolower($column_task["field"]).'@',$arr_data[$x][$column_task["field"]],$url_task);
															
							}
							$title_blue = "title-blue";
							$start_href_1 ="<a href='{$url_task}' target='_blank' ";
							$end_href =" </a>";
							$link_url =$start_href_1." >";
							$end_link_url = " </a>";
						}
						else {
							$url_task ='';
							$title_blue='';
							$start_href_1 ="<div ";
							$end_href =" </div>";
							$link_url ="";
							$end_link_url = "";
						}
						
					}
					else if($item['url']!=''){
						if (strpos($item['url'],'http')!==FALSE||strpos($item['url'],'https')!==FALSE) {
							$url_task = $item['url'];
						}
						else {
							$url_task = base_url().$item['url'];
						}
						foreach($headers as $column_task) {
							$url_task = str_replace('@'.strtolower($column_task["field"]).'@',$arr_data[$x][$column_task["field"]],$url_task);
														
						} 
						$title_blue = "title-blue";
						$start_href_1 ="<a href='{$url_task}' ";
						$end_href =" </a>";
						$link_url =$start_href_1." >";
						$end_link_url = " </a>";
						
					}
					else {
						$url_task ='';
						$title_blue='';
						$start_href_1 ="<div ";
						$end_href =" </div>";
						$link_url ="";
						$end_link_url = "";
					}
					switch($item['align']) {
						case 'L':
							$start_href = $start_href_1.'class="align-left '.$title_blue.' " >';
							$align = ' class="align-left"';
							break;
						case 'R';
							$start_href = $start_href_1.'class="align-right '.$title_blue.' " >';
							$align = ' class="align-right"';
							break;
						default:
							$start_href = $start_href_1.'class="align-center '.$title_blue.'" >';
							$align = ' class="align-center"';
							break;
					}
					if(($table =='data_report') && (strtolower($item['field'])=='update') && (date('Y-m-d',strtotime("now")) == date('Y-m-d',strtotime($arr_data[$x][strtolower($item['field'])])))){
						$records["data"][$key][] = $start_href.date('H:i:s',strtotime($arr_data[$x][strtolower($item['field'])])) .$end_href;
					}
					else if(($item['hidden']==1)){
						$pattern = '/[\S]/';
						$replacement = '*';
						$records["data"][$key][] = $start_href. preg_replace($pattern, $replacement,  $arr_data[$x][strtolower($item['field'])]).$start_href;
						$length = strlen($arr_data[$x][strtolower($item['field'])]);
					}
					else if($item['format_n'] == 'group'){	
						if(strpos($arr_data[$x][strtolower($item['field']).'_frt'],'http') !== false){
							$url = 	$arr_data[$x][strtolower($item['field']).'_frt'];
						}
						else{
							$url = 	'http://'.$arr_data[$x][strtolower($item['field']).'_frt'];	
						}
						if(strpos($arr_data[$x][strtolower($item['field']).'_bcd'],'http') !== false){
							$url2 = $arr_data[$x][strtolower($item['field']).'_bcd'];
						}
						else{
							$url2 = 'http://'.$arr_data[$x][strtolower($item['field']).'_bcd'];	
						}
							$records["data"][$key][] ='<div'.$align.'>
							
							<a href="'.$url.'" '.($arr_data[$x][strtolower($item['field']).'_frt'] != '' ? '' : 'disabled') .' target="_blank" class="btn btn-sm default btn-'.(isset($arr_data[$x][strtolower($item['field']).'_hst']) ? strtolower(str_replace(' ','',$arr_data[$x][strtolower($item['field']).'_hst'])) :"local.itvn.fr").' '.($arr_data[$x][strtolower($item['field']).'_frt'] != '' ? '' : 'hide' ).'" >F</a>
								
								<a href="'.$url2.'"'.($arr_data[$x][strtolower($item['field']).'_bcd'] == '#' ? 'disabled' : '' ).' target="_blank" class="btn btn-sm blue '.($arr_data[$x][strtolower($item['field']).'_bcd'] != '' ? '' : 'hide' ).'">B </a></div>';
						
					
					}else if($item['format_n'] == 'image'){
						$imgsize = @getimagesize(base_url().'assets/upload/images/'.$arr_data[$x][strtolower($item['field'])]);
						if (!is_array($imgsize)){ 
							$records["data"][$key][] = '';
						}else{
							$start = strpos(strtolower($item['type']),'(') +1;
							$end = strpos(strtolower($item['type']),')');
							$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : '';
							$arrHeight = explode(',', $str);
							$height = (isset($arrHeight[0]) && $arrHeight[0] >0) ?  $arrHeight[0]: 20;
							$heightMax = (isset($arrHeight[1]) && $arrHeight[1] >0 ) ?  $arrHeight[1]: 200;
							$image = $arr_data[$x][strtolower($item['field'])]!=''? '<img height="'.$height.'" src="'.base_url().'assets/upload/images/'.$arr_data[$x][strtolower($item['field'])].'" class="thumb" data-height="'.$heightMax.'" >' :'';
							$records["data"][$key][] = $start_href.$image.$end_href;
						}
					}else if($item['format_n'] == 'button'){
						$records["data"][$key][] = '<div'.$align.'><a href="'.base_url().'table/'.$arr_data[$x][strtolower($item['field'])].'" class="text-uppercase">'.$arr_data[$x][strtolower($item['field'])].'</a></div>';   
					}else if($item['format_n'] == 'popup'){
						 if((strpos(strtolower($item['type']),'image')!==false)){
							$start = strpos(strtolower($item['type']),'(') +1;
							$end = strpos(strtolower($item['type']),')');
							$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : '';
							$arrHeight = explode(',', $str);
							$height = (isset($arrHeight[0]) && $arrHeight[0] >0) ?  $arrHeight[0]: 20;
							$heightMax = (isset($arrHeight[1]) && $arrHeight[1] >0 ) ?  $arrHeight[1]: 200;							
						}
                         if(strtolower($arr_data[$x][strtolower($item['field'])]) == 'x'){
                            $records["data"][$key][]= '<a  class="mix-link" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" download="'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'">
										<i class="fa fa-link"></i> </a>
													<a data-fancybox-group="'.$category.'_'.strtolower($item['field']).'" title="'.$arr_data[$x]['file'].'" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" class="mix-preview fancybox-button">
													<i class="fa fa-search thumb" src="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" data-height="'.$heightMax.'"></i>';
                         }
						  else if(is_file($arr_data[$x][strtolower($item['field'])])){
							 if($arr_data[$x][strtolower($item['field'])] == 'assets/images/no-image.jpg'){
								$records["data"][$key][]= '';
							 }
							 else{
								 
								 $records["data"][$key][]= '<a  class="mix-link" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'">
											<i class="fa fa-link"></i> </a>
														<a data-fancybox-group="'.$category.'_'.strtolower($item['field']).'" title="'.$arr_data[$x][strtolower($item['field'])].'" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" class="mix-preview fancybox-button">
														<i class="fa fa-search thumb" src="'.base_url().$arr_data[$x][strtolower($item['field'])].'" data-height="'.$heightMax.'"></i>';
									 
							}
                         }else{
                            $records["data"][$key][] = $arr_data[$x][strtolower($item['field'])];
                         }
                    }else if((strtolower($item['format_n'])=='info')&& $arr_data[$x][strtolower($item['field'])]!=''){
						$records["data"][$key][] = "<div".$align."><a data-toggle='tooltip' data-placement='top' data-trigger='hover' data-html='true' data-container='body' data-original-title='".html_entity_decode($arr_data[$x][strtolower($item['field'])])."' title='".html_entity_decode($arr_data[$x][strtolower($item['field'])])."' class='btn btn-icon-only blue tooltips'  href='#'>
									<i class='fa fa-question'></i></a></div>";
						
					}
					else if((strpos(strtolower($item['format_n']),'decimal')!==false)&&$arr_data[$x][strtolower($item['field'])]!=''){
						$start = strpos(strtolower($item['format_n']),'(') +1;
						$end = strpos(strtolower($item['format_n']),')');
						$str = ($start!==false && $end!==false) ? intval(substr($item['format_n'],$start,  $end - $start)) : 0;
						$records["data"][$key][] = $start_href.number_format((float)$arr_data[$x][strtolower($item['field'])], intval($str), '.', ',').$end_href;
					}
					else if($item['format_n'] == 'download'){
                         if(strtolower($arr_data[$x][strtolower($item['field'])]) == 'x'){
							 
                            $records["data"][$key][]= '<a title="'.$arr_data[$x]['file'].'" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" download="'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" >
            				   							<i class="fa fa-download"></i>
            											</a>';
                         }else if(is_file($arr_data[$x][strtolower($item['field'])])){
                            $records["data"][$key][]= '<a title="'.$arr_data[$x][strtolower($item['field'])].'" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'" >
            				   							<i class="fa fa-download"></i>
            											</a>';
                         }else{
                            $records["data"][$key][] = $arr_data[$x][strtolower($item['field'])];
                         }
                    }
					else if((strpos(strtolower($item['type']),'image')!==false)){
						$start = strpos(strtolower($item['type']),'(') +1;
						$end = strpos(strtolower($item['type']),')');
						$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : '';
						$arrHeight = explode(',', $str);
						$height = (isset($arrHeight[0]) && $arrHeight[0] >0) ?  $arrHeight[0]: 20;
						$heightMax = (isset($arrHeight[1]) && $arrHeight[1] >0 ) ?  $arrHeight[1]: 200;
						$image =$arr_data[$x][strtolower($item['field'])]!=''? '<img height="'.$height.'" src="'.base_url().$arr_data[$x][strtolower($item['field'])].'" class="thumb" data-height="'.$heightMax.'" >' :'';
						
						$records["data"][$key][] = '<div'.$align.'><a data-fancybox-group="'.$category.'" title="'.$arr_data[$x][strtolower($item['field'])].'" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" class="mix-preview fancybox-button">'.$image.'</a></div>';
						
					}
					else if((strtolower($item['type'])=='file')&& $arr_data[$x][strtolower($item['field'])]!=''){
						
						if(strpos($arr_data[$x][strtolower($item['field'])], 'http')===false && strpos($arr_data[$x][strtolower($item['field'])], 'https')===false)
						$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only blue"  href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'">
									<i class="fa fa-file-pdf-o"></i></a></div>';
						else {
							
							$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only blue" href="'.$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'">
									<i class="fa fa-file-pdf-o"></i></a></div>';
							
						}
						
					}
                    else if((strtolower($item['type'])=='info')&& $arr_data[$x][strtolower($item['field'])]!=''){
						$records["data"][$key][] = '<div'.$align.'><span  style = "position:relative;" class="tooltips" data-toggle="tooltip" data-placement="top" title="'.$arr_data[$x][strtolower($item['field'])].'"><a style = "position:absolute; top:10px; left:20px; height:27px; width:27px; padding:3px;" class="btn btn-icon-only blue"  href="#">
									<i class="fa fa-question"></i></a></span></div>';
						
					}
					else if((strpos(strtolower($item['type']),'link')!==false)&&$arr_data[$x][strtolower($item['field'])]!=''){
						$start = strpos(strtolower($item['type']),'(') +1;
						$end = strpos(strtolower($item['type']),')');
						$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : 'Link';
						if(strpos($arr_data[$x][strtolower($item['field'])], 'http')===false && strpos($arr_data[$x][strtolower($item['field'])], 'https')===false)
						$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only green" href="http://'.$arr_data[$x][strtolower($item['field'])].'" target="_blank">
									<i class="fa fa-globe"></i> '.trim($str).' </a></div>';							
						
						else {
							
							$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only green" href="'.$arr_data[$x][strtolower($item['field'])].'" target="_blank">
									<i class="fa fa-globe"></i> '.trim($str).' </a></div>';
							
						}
					}
					else{// xử lý tô màu background cho report_task
					 		$sql_st = "SELECT `value` FROM setting WHERE `key` = 'color_red'";
							$row_st = $this->db->query($sql_st)->row_array();
						if($sTable == 'report_task'){
							if($arr_data[$x][strtolower($item['field'])] < 3 && $item['field'] =='total'){
								$records["data"][$key][] = '<div'.$align.' style="background: '.$row_st['value'].'; border-radius: 3px;color: #fff; padding: 7px;">'.$link_url.$arr_data[$x][strtolower($item['field'])] .$end_link_url.'</div>';
							}
							elseif($arr_data[$x][strtolower($item['field'])] > 0 && $item['field'] == 'priority0'){
								$records["data"][$key][] = '<div'.$align.' style="background: '.$row_st['value'].'; border-radius: 3px;color: #fff; padding: 7px;">'.$link_url.$arr_data[$x][strtolower($item['field'])] .$end_link_url.'</div>';
							}
							
							elseif($arr_data[$x][strtolower($item['field'])] == 0 && $item['field'] == 'current'){
								$records["data"][$key][] = '<div'.$align.' style="background: '.$row_st['value'].'; border-radius: 3px;color: #fff; padding: 7px;">'.$link_url.$arr_data[$x][strtolower($item['field'])] .$end_link_url.'</div>';
							}
							elseif($arr_data[$x][strtolower($item['field'])] > 3 && $item['field'] == 'done'){
								$records["data"][$key][] = '<div'.$align.' style="background: '.$row_st['value'].'; border-radius: 3px;color: #fff; padding: 7px;">'.$link_url.$arr_data[$x][strtolower($item['field'])] .$end_link_url.'</div>';
							}
							else{
								$records["data"][$key][] = $start_href.$arr_data[$x][strtolower($item['field'])] .$end_href;	
							}
						}
						else{
							$records["data"][$key][] = $start_href.$arr_data[$x][strtolower($item['field'])] .$end_href;		
						}
					}
					
	
				}
				
                if(isset($keyARR['keys'])&&$keyARR['keys'] != ''){
                    $keys = array();
				
    				foreach($ke as $val){
    				   $keys[] = "'".$arr_data[$x][strtolower($val)]."' ";
    			   
    				}
    				//print_r($keys);
    				$k = implode(',',$keys);
                    
    				
                    if($keyARR['user_level']>$this->session->userdata('user_level')){
    					$records["data"][$key][] .='';
						
						 /*  $records["data"][$key][] .= '<center><div class="align-center">'
											.'<a class="btn btn-icon-only green show-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'"  data-target="#modal" data-toggle="modal">
											<i class="fa fa-edit"></i></a>'
											.'<a class="btn btn-icon-only red deleteField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
											<i class="fa fa-trash-o"></i></a>'
											.'</div></center>';*/
    				}else{
						if($table == 'ifrc_articles'){
							 $records["data"][$key][] .= '<center><div class="align-center">'
												.'<a href="'.base_url().'ajax/update_modal_intranet/'.str_replace(" ","",str_replace("'","",$k)).'" class="btn btn-icon-only green show-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'">
											<i class="fa fa-edit"></i></a>'
												.'<a class="btn btn-icon-only red deleteField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
												<i class="fa fa-trash-o"></i></a>'
												
												.'</div></center>';
												
							
						}else{
						 $records["data"][$key][] .= '<center><div class="align-center">'
												.'<a class="btn btn-icon-only green show-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'"  data-target="#modal" data-toggle="modal">
												<i class="fa fa-edit"></i></a>'
												.'<a class="btn btn-icon-only red deleteField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
												<i class="fa fa-trash-o"></i></a>'
												.'<a class="btn btn-icon-only yellow duplicateField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
												<i class="fa fa-copy"></i></a>'
												.'</div></center>';
						}
    				}
                }else
                {
                    $records["data"][$key][] .= '';
                }
			}
			$key ++;
		
		}

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iFilteredTotal;
          
        $this->output->set_output(json_encode($records));
    }
	 public function loadtable_vnxindex(){
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
		$table = isset($_REQUEST['name_table'])? $_REQUEST['name_table'] :'';
		
		$category = isset($_POST['category'])? strtoupper($_POST['category']) :'';
		$arr_table_sys = $this->table->get_summary($table);
		
		$table_sys = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
		// select columns
		$where = "where 1=1 ";
       
		$headers = $this->table->get_headers($table_sys);
		$aColumns = array();
		$arrPost = "";
		foreach ($headers as $item) {
	        if($item['format_n'] == 'group'){
	              $aColumns[] = '`'.strtolower($item['field'].'_frt').'`,`'.strtolower($item['field'].'_bcd').'`,`'.strtolower($item['field'].'_hst').'`';
	        }else{  
			     $aColumns[] = '`'.strtolower($item['field']).'`';
            }
			$field_post = $this->input->post(strtolower($item['field']));
			$arrPost .=isset($_REQUEST[strtolower($item['field'])]) ? "&{$item['field']}=".$_REQUEST[strtolower($item['field'])]:'';
			$field_post_html = $this->input->post(strtolower("html_".$item['field']));
			if(($field_post!='') || ($field_post_html!='')) {
				$field_post = $field_post !='empty' ? $field_post :'';
				if((strpos(strtolower($item['type']),'list')!==false)){
					if($field_post != 'all') {
							$where .= " and `{$item['field']}` = '".$field_post."'";    
						}
						$arrPost .="&{$item['field']}=".$field_post;
				}
				else {
					switch(strtolower($item['type'])) {
						case 'varchar':
						case 'longtext':
						case 'int':
						case 'link':
							$where .= " and `{$item['field']}` like '%".$field_post."%'";
							$arrPost .="&{$item['field']}=".$field_post;
							
							break;
						case 'html':
						case 'info':
							$where .= " and `{$item['field']}` like '%".$field_post_html."%'";
							$arrPost .="&{$item['field']}=".$field_post_html;
							break;
						case 'list':
							if($field_post != 'all') {
								$where .= " and `{$item['field']}` = '".$field_post."'";    
							}
							break;
						default:
							break;
					}
				}
			}else if($this->input->post(strtolower($item['field'].'_start')) and strtotime($this->input->post(strtolower($item['field'].'_start')))) {
				$where .= " and `{$item['field']}` >= '".real_escape_string($this->input->post(strtolower($item['field'].'_start')))."'";
				//$arrPost .="&{$item['field']}_start=".real_escape_string($this->input->post(strtolower($item['field'].'_start')));
				$arrPost .=isset($_REQUEST[strtolower($item['field'].'_start')]) ? "&{$item['field']}_start=".$_REQUEST[strtolower($item['field'].'_start')]:"";
			} 
			 else if($this->input->post(strtolower($item['field'].'_from'))) {
				$where .= " and `{$item['field']}` >= ".(int)($this->input->post(strtolower($item['field'].'_from')))."";
				//$arrPost .="&{$item['field']}_from=".(int)($this->input->post(strtolower($item['field'].'_from')));
				$arrPost .=isset($_REQUEST[strtolower($item['field'].'_from')]) ? "&{$item['field']}_from=".$_REQUEST[strtolower($item['field'].'_from')]:"";
				
			}
			if($this->input->post(strtolower($item['field'].'_end')) and strtotime($this->input->post(strtolower($item['field'].'_end')))){
				 $where .= " and `{$item['field']}` <= '".real_escape_string($this->input->post(strtolower($item['field'].'_end')))."'";
				 //$arrPost .="&{$item['field']}_end=".real_escape_string($this->input->post(strtolower($item['field'].'_end')));
				 $arrPost .=isset($_REQUEST[strtolower($item['field'].'_end')]) ? "&{$item['field']}_end=".$_REQUEST[strtolower($item['field'].'_end')]:"";
				 
			}
			if($this->input->post(strtolower($item['field'].'_to'))) {
				$where .= " and `{$item['field']}` <= ".(int)($this->input->post(strtolower($item['field'].'_to')))."";
				//$arrPost .="&{$item['field']}_to=".(int)($this->input->post(strtolower($item['field'].'_to')));
				$arrPost .=isset($_REQUEST[strtolower($item['field'].'_to')]) ? "&{$item['field']}_to=".$_REQUEST[strtolower($item['field'].'_to')]:"";
			}
             
		}
		if($category != 'ALL' && $category != '') $where .= " and `category` = '".$category."'";
		
		$sTable = $table_sys; //$category == 'all' ? "int_".$table : "(SELECT * FROM int_".$table." where category = '".$category."') as sTable " ;
		
		$sqlColumn = "SHOW COLUMNS FROM {$sTable};";  
		$arrColumn = $this->db->query($sqlColumn)->result_array();
			
		
		foreach ($arrColumn as $item){		 	
			if(!$this->input->post(strtolower($item['Field'])) && isset($_GET[$item['Field']]) && ($_GET[$item['Field']]!='all') && (strtolower($item['Field']!='tab'))){
				 $where .= " and `{$item['Field']}` = '".$_GET[$item['Field']]."'";
				 
			}
		 }
		// order
		$order_by ='';	
		if (isset($_REQUEST['order'][0]['column'])) {
			$iSortCol_0 = $_REQUEST['order'][0]['column'];
			$sSortDir_0 = $_REQUEST['order'][0]['dir'];
			foreach($aColumns as $key => $value) {
				if($iSortCol_0 == $key) {                    
					$order_by = " order by $value ".$sSortDir_0;
					break;
				}
			}
		}
		$order_by .= (($arr_table_sys['order_by']!='') && (!is_null($arr_table_sys['order_by'])))?($order_by =='' ? ('order by '.$arr_table_sys['order_by']):(','.$arr_table_sys['order_by'])):'';
        
        $sqlkey = "SELECT `keys`, `user_level` FROM int_summary WHERE `tab` = '{$table_sys}'";
		$keyARR = $this->db->query($sqlkey)->row_array();
			
        if(isset($keyARR)){
            if(isset($keyARR['keys'])&&$keyARR['keys'] != ''){
        		$keyARR = isset($keyARR) ? $keyARR: array();
        		$arr = explode(',',$keyARR['keys']);
        		foreach($arr as $v){ 
        			$aa[] = '`'.TRIM($v).'`';
        		}   
        		$rs = in_array($aa, $aColumns, TRUE) ?  $aColumns : array_merge((array)$aa, (array)$aColumns);
                $ke = explode(',',$keyARR['keys']);	
            }else{
                $rs = $aColumns;
            }  
        }
        $sql = "select sql_calc_found_rows *
    					from {$sTable} {$where} {$order_by};";    
		$data = $this->db->query($sql)->result_array();
	
		//$data = $this->db->query($sql)->result_array();  
		$arr_data = array();
		$i =0;
		foreach ($data as $key => $value) {
			$arr_data[$i] = $value;	
			$i++;		
		}
		//var_export($arr_data);
		$iFilteredTotal = $i;
		$iTotalRecords = $iFilteredTotal;
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
           //     print_R()
		   
		//$data = $this->db->query($sql)->result_array();   
		$records = array();
		$records["data"] = array();
        //PRINT_r($data);EXIT;
		$key = 0;
		for ($x = $iDisplayStart; $x < $iDisplayStart + $iDisplayLength; $x++) {
			if(isset($arr_data[$x])) {
				$path = base_url().'assets/upload/intranet';
				if(isset($arr_data[$x]['group']) && $arr_data[$x]['group']!='') $path .='/'.strtolower($arr_data[$x]['group']);
				if(isset($arr_data[$x]['owner']) && $arr_data[$x]['owner']!='') $path .='/'.strtolower($arr_data[$x]['owner']);
				if(isset($arr_data[$x]['category']) && $arr_data[$x]['category']!='') $path .='/'.strtolower($arr_data[$x]['category']);
				if(isset($arr_data[$x]['subcat']) && $arr_data[$x]['subcat']!='') $path .='/'.strtolower($arr_data[$x]['subcat']);
                if($table == 'query' || $table == 'data_report'){
			         $records["data"][$key][] = '<input type="checkbox" class="checkboxes" value="'.$arr_data[$x]['id'].'">';	// check s
                }
				foreach($headers as $item) {
					switch($item['align']) {
						case 'L':
							$align = ' class="align-left"';
							break;
						case 'R';
							$align = ' class="align-right"';
							break;
						default:
							$align = ' class="align-center"';
							break;
					}
					if(($item['hidden']==1)){
						$length = strlen($arr_data[$x][strtolower($item['field'])]);
						$records["data"][$key][] = '<div'.$align.'>'.substr(str_repeat('*',$length),0,16).'</div>';
					}
					else if($item['format_n'] == 'group'){	
						$records["data"][$key][] = '<div'.$align.'><a href="http://'.$arr_data[$x][strtolower($item['field']).'_frt'].'" '.($arr_data[$x][strtolower($item['field']).'_frt'] != '' ? '' : 'disabled') .' target="_blank" class="btn btn-sm default btn-'.(isset($arr_data[$x][strtolower($item['field']).'_hst']) ? strtolower(str_replace(' ','',$arr_data[$x][strtolower($item['field']).'_hst'])) :"local.itvn.fr").' '.($arr_data[$x][strtolower($item['field']).'_frt'] != '' ? '' : 'hide' ).'">Front </i><a href="http://'.$arr_data[$x][strtolower($item['field']).'_bcd'].'"'.($arr_data[$x][strtolower($item['field']).'_bcd'] == '#' ? 'disabled' : '' ).' target="_blank" class="btn btn-sm blue '.($arr_data[$x][strtolower($item['field']).'_bcd'] != '' ? '' : 'hide' ).'">Back </div>';
					}else if($item['format_n'] == 'image'){
						$imgsize = @getimagesize(base_url().'assets/upload/images/'.$arr_data[$x][strtolower($item['field'])]);
						if (!is_array($imgsize)){ 
							$records["data"][$key][] = '';
						}else{
							$start = strpos(strtolower($item['type']),'(') +1;
							$end = strpos(strtolower($item['type']),')');
							$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : '';
							$arrHeight = explode(',', $str);
							$height = (isset($arrHeight[0]) && $arrHeight[0] >0) ?  $arrHeight[0]: 20;
							$heightMax = (isset($arrHeight[1]) && $arrHeight[1] >0 ) ?  $arrHeight[1]: 200;
							$image = $arr_data[$x][strtolower($item['field'])]!=''? '<img height="'.$height.'" src="'.base_url().'assets/upload/images/'.$arr_data[$x][strtolower($item['field'])].'" class="thumb" data-height="'.$heightMax.'" >' :'';
							$records["data"][$key][] = '<div'.$align.'>'.$image.'</div>';
						}
					}else if($item['format_n'] == 'button'){
						$records["data"][$key][] = '<div'.$align.'><a href="'.base_url().'table/'.$arr_data[$x][strtolower($item['field'])].'" class="text-uppercase">'.$arr_data[$x][strtolower($item['field'])].'</a></div>';   
					}else if($item['format_n'] == 'popup'){
						 if((strpos(strtolower($item['type']),'image')!==false)){
							$start = strpos(strtolower($item['type']),'(') +1;
							$end = strpos(strtolower($item['type']),')');
							$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : '';
							$arrHeight = explode(',', $str);
							$height = (isset($arrHeight[0]) && $arrHeight[0] >0) ?  $arrHeight[0]: 20;
							$heightMax = (isset($arrHeight[1]) && $arrHeight[1] >0 ) ?  $arrHeight[1]: 200;							
						}
                         if(strtolower($arr_data[$x][strtolower($item['field'])]) == 'x'){
                            $records["data"][$key][]= '<a  class="mix-link" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" download="'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'">
										<i class="fa fa-link"></i> </a>
													<a data-fancybox-group="'.$category.'_'.strtolower($item['field']).'" title="'.$arr_data[$x]['file'].'" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" class="mix-preview fancybox-button">
													<i class="fa fa-search thumb" src="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" data-height="'.$heightMax.'"></i>';
                         }
						  else if(is_file($arr_data[$x][strtolower($item['field'])])){
                            $records["data"][$key][]= '<a  class="mix-link" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'">
										<i class="fa fa-link"></i> </a>
													<a data-fancybox-group="'.$category.'_'.strtolower($item['field']).'" title="'.$arr_data[$x][strtolower($item['field'])].'" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" class="mix-preview fancybox-button">
													<i class="fa fa-search thumb" src="'.base_url().$arr_data[$x][strtolower($item['field'])].'" data-height="'.$heightMax.'"></i>';
                         }else{
                            $records["data"][$key][] = $arr_data[$x][strtolower($item['field'])];
                         }
                    }else if((strtolower($item['format_n'])=='info')&& $arr_data[$x][strtolower($item['field'])]!=''){
						$records["data"][$key][] = "<div".$align."><a data-toggle='tooltip' data-placement='top' data-trigger='hover' data-html='true' data-container='body' data-original-title='".$arr_data[$x][strtolower($item['field'])]."' title='".$arr_data[$x][strtolower($item['field'])]."' class='btn btn-icon-only blue tooltips'  href='#'>
									<i class='fa fa-question'></i></a></div>";
						
					}else if($item['format_n'] == 'download'){
                         if(strtolower($arr_data[$x][strtolower($item['field'])]) == 'x'){
							 
                            $records["data"][$key][]= '<a title="'.$arr_data[$x]['file'].'" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" download="'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" >
            				   							<i class="fa fa-download"></i>
            											</a>';
                         }else if(is_file($arr_data[$x][strtolower($item['field'])])){
                            $records["data"][$key][]= '<a title="'.$arr_data[$x][strtolower($item['field'])].'" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'" >
            				   							<i class="fa fa-download"></i>
            											</a>';
                         }else{
                            $records["data"][$key][] = $arr_data[$x][strtolower($item['field'])];
                         }
                    }
					else if((strpos(strtolower($item['type']),'image')!==false)){
						$start = strpos(strtolower($item['type']),'(') +1;
						$end = strpos(strtolower($item['type']),')');
						$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : '';
						$arrHeight = explode(',', $str);
						$height = (isset($arrHeight[0]) && $arrHeight[0] >0) ?  $arrHeight[0]: 20;
						$heightMax = (isset($arrHeight[1]) && $arrHeight[1] >0 ) ?  $arrHeight[1]: 200;
						$image =$arr_data[$x][strtolower($item['field'])]!=''? '<img height="'.$height.'" src="'.PATH_IFRC_ARTICLE.$arr_data[$x][strtolower($item['field'])].'" class="thumb" data-height="'.$heightMax.'" >' :'';
						
						$records["data"][$key][] = '<div'.$align.'><a data-fancybox-group="'.$category.'" title="'.$arr_data[$x][strtolower($item['field'])].'" href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" class="mix-preview fancybox-button">'.$image.'</a></div>';
						
					}
					else if((strtolower($item['type'])=='file')&& $arr_data[$x][strtolower($item['field'])]!=''){
						$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only blue"  href="'.base_url().$arr_data[$x][strtolower($item['field'])].'" download="'.$arr_data[$x][strtolower($item['field'])].'">
									<i class="fa fa-file-pdf-o"></i></a></div>';
						
					}
                    else if((strtolower($item['type'])=='info')&& $arr_data[$x][strtolower($item['field'])]!=''){
						$records["data"][$key][] = '<div'.$align.'><span style = "position:relative;" class="tooltips" data-toggle="tooltip" data-placement="top" title="'.$arr_data[$x][strtolower($item['field'])].'"><a style = "position:absolute; top:10px; left:20px; height:27px; width:27px; padding:3px;" class="btn btn-icon-only blue"  href="#">
									<i class="fa fa-question"></i></a></span></div>';
						
					}
					else if((strpos(strtolower($item['type']),'link')!==false)&&$arr_data[$x][strtolower($item['field'])]!=''){
						$start = strpos(strtolower($item['type']),'(') +1;
						$end = strpos(strtolower($item['type']),')');
						$str = ($start!==false && $end!==false) ? substr($item['type'],$start,  $end - $start) : 'Link';
						if(strpos($arr_data[$x][strtolower($item['field'])], 'http')===false && strpos($arr_data[$x][strtolower($item['field'])], 'https')===false)
						$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only green" href="http://'.$arr_data[$x][strtolower($item['field'])].'" target="_blank">
									<i class="fa fa-globe"></i> '.trim($str).' </a></div>';							
						
						else {
							
							$records["data"][$key][] = '<div'.$align.'><a class="btn btn-icon-only green" href="'.$arr_data[$x][strtolower($item['field'])].'" target="_blank">
									<i class="fa fa-globe"></i> '.trim($str).' </a></div>';
							
						}
					}
					else{
						$records["data"][$key][] = '<div'.$align.'>'.$arr_data[$x][strtolower($item['field'])] .'</div>';
					}
					
	
				}
				
                if(isset($keyARR['keys'])&&$keyARR['keys'] != ''){
                    $keys = array();
				
    				foreach($ke as $val){
    				   $keys[] = "'".$arr_data[$x][strtolower($val)]."' ";
    			   
    				}
    				//print_r($keys);
    				$k = implode(',',$keys);
                    
    				
                    if($keyARR['user_level']>$this->session->userdata('user_level')){
    					$records["data"][$key][] .='';
						
						 /*  $records["data"][$key][] .= '<center><div class="align-center">'
											.'<a class="btn btn-icon-only green show-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'"  data-target="#modal" data-toggle="modal">
											<i class="fa fa-edit"></i></a>'
											.'<a class="btn btn-icon-only red deleteField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
											<i class="fa fa-trash-o"></i></a>'
											.'</div></center>';*/
    				}else{
						$xq='';
						if(!empty($arrPost)) $xq = "/?";	
				     $records["data"][$key][] .= '<center><div class="align-center">'
											.'<a href="'.base_url().'ajax/update_modal_vnxindex/'.str_replace(" ","",str_replace("'","",$k)).$xq.$arrPost.'" class="btn btn-icon-only green show-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'">
											<i class="fa fa-edit"></i></a>'
											
											.'<a class="btn btn-icon-only red deleteField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
											<i class="fa fa-trash-o"></i></a>'
											.'</div></center>';
    				}
                }else
                {
                    $records["data"][$key][] .= '';
                }
			}
			$key ++;
		
		}
		
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iFilteredTotal;
          
        $this->output->set_output(json_encode($records));
    }
    function show_modal() {
        
        $table_name = isset($_REQUEST['table_name']) ? $_REQUEST['table_name']: '';		
        $table = $this->table->get_tab($table_name);
	
        $keys = isset($_POST['keys_value']) ? strtolower(TRIM($_POST['keys_value'])) : "";
        $headers = $this->table->tab_type($table['tab']);
       //print_r($headers);exit;
	    $sqlkey = "SELECT `keys`,`article` FROM int_summary WHERE table_name = '{$table['table_name']}'";
        $keyARR = $this->db->query($sqlkey)->row_array();
        $where = '1 = 1';
        if($keys != ''){
            $eql = explode(' ,',$keys);
            foreach($eql as $vl){ 
                $eq[] = TRIM($vl);
            }
           // print_r($eq);
            $arr = explode(',',$keyARR['keys']);
    		$aa = array();
            foreach($arr as $v){ 
                $aa[] = TRIM(strtolower($v));
            }
            $wh = array_combine($aa,$eq);  
            foreach($wh as $key=>$value){  
            $where .= " and `$key` = {$value}";
            }
        }
        $sql = "Select * FROM {$table['tab']} WHERE {$where} ;";
        $data = isset($key) ? $this->db->query($sql)->row_array() : "";
		//type in sys_format
		$sqlType = "SELECT `type`,`field` FROM sys_format WHERE `table` = '{$table['tab']}'";
        $typeArr = $this->db->query($sqlType)->result_array();
		$strTypeArr= array();
		foreach ($typeArr as $item=> $value) {
			$strTypeArr[$value['field']] = $value['type'];
		}
      
        foreach ($headers as $key => $value) {
            $readonly = '0';
            $primary = '0';
            if($keys != ''){
				$readonly = isset($data[$value['Field']]) && in_array(strtolower($value['Field']), $aa ) ? '1' : '0' ;
				$primary = in_array(strtolower($value['Field']), $aa ) ? '1' : '0' ;
				$value_field = isset($data[$value['Field']]) ? $data[$value['Field']] : '';					
			}
			else if($keyARR['article']==1 && $value['Field']=='lang_code'){
				$value_field = 'en';		
			}
			else if ($value['Field']=='date_added') {
				$value_field = date('Y-m-d H:i:s');
			} 
			else if(strtolower($value['Field'])=='id'){
				$readonly = '1' ;
				$primary =  '1' ;
				$value_field = isset($data[$value['Field']]) ? $data[$value['Field']] : '';	
			}
			else {
				$value_field ='';
			}
			
			if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'list') !== FALSE)){
				$start = strpos(strtolower($strTypeArr[$value['Field']]),'(') +1;
				$end = strpos(strtolower($strTypeArr[$value['Field']]),')');
				$str = ($start!==false && $end!==false) ? substr($strTypeArr[$value['Field']],$start,  $end - $start) : '';
				if(trim($str)!=''){
					$headers[$key]['filter'] = $this->table->append_list_editon(strtolower($value['Field']),$str,$value_field , $readonly, $primary);
				}
				else{
					$headers[$key]['filter'] = $this->table->append_select_editon(strtolower($value['Field']),$table['tab'],$value_field, $readonly, $primary);
				}

			}
			else if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'image') !== FALSE)){
				$headers[$key]['filter'] = '<div class="col-md-9">';
				$headers[$key]['filter'] .= $this->table->append_image_editon($value_field,strtolower($value['Field']));
				$headers[$key]['filter'] .= '</div>';

			}
			else if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'html') !== FALSE)){
				$headers[$key]['filter'] = '<div class="col-md-9">';
				$headers[$key]['filter'] .= $this->table->append_html_editon(strtolower($value['Field']),$value_field);
				$headers[$key]['filter'] .= '</div>';

			}
			else if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'file') !== FALSE)){
				$headers[$key]['filter'] = '<div class="col-md-9">';
				$headers[$key]['filter'] .= $this->table->append_file_editon($value_field,strtolower($value['Field']));
				$headers[$key]['filter'] .= '</div>';

			}
			
			else {
				$headers[$key]['filter'] = '<div class="col-md-9">';
				if(($value['Field']=='date_update')){
					$headers[$key]['filter'] .= $this->table->append_date_editon(strtolower($value['Field']),  date('Y-m-d H:i:s'), '1');
				}else if(strtolower($value['Field'])=='password'){
					$headers[$key]['filter'] .= $this->table->append_input_editon(strtolower($value['Field']),$value_field, $readonly, $primary, 'password');
				}
				else if ((strpos(strtolower($value['Type']), 'text') !== FALSE)|| (strpos(strtolower($value['Type']), 'longtext') !== FALSE)){
					$headers[$key]['filter'] .= $this->table->append_textarea_editon(strtolower($value['Field']),$value_field);
				}				
				else if((strpos(strtolower($value['Type']), 'varchar') !== FALSE) || (strpos(strtolower($value['Type']), 'int') !== FALSE)||(strpos(strtolower($value['Type']), 'double') !== FALSE)||(strpos(strtolower($value['Type']), 'interger') !== FALSE) ){
					$headers[$key]['filter'] .= $this->table->append_input_editon(strtolower($value['Field']),$value_field, $readonly,$primary);
				}
				else if((strpos(strtolower($value['Type']), 'date') !== FALSE)){
					/*$headers[$key]['filter'] .= $this->table->append_date_editon(strtolower($value['Field']), $value_field, $readonly,$primary);*/
					$headers[$key]['filter'] .= $this->table->append_date_editon(strtolower($value['Field']),  date('Y-m-d H:i:s'), '1');
				}
				else{
					$headers[$key]['filter'] .= $this->table->append_input_editon(strtolower($value['Field']),$value_field, $readonly, $primary);
				}
				$headers[$key]['filter'] .= '</div>';
			}
			
        }
        $this->data->headers = $headers;
        $this->data->name_table = $table['table_name'];
        $this->data->name_desc = $table['description'];
		$this->data->keys = $keys;
       
        $this->load->view('table/modal', $this->data);
    }


	 function show_modal_vnxindex() {
		 
        $table_name = isset($_REQUEST['table_name']) ? $_REQUEST['table_name']: '';		
        $table = $this->table->get_tab($table_name);
	
        $keys = isset($_POST['keys_value']) ? strtolower(TRIM($_POST['keys_value'])) : "";
        $headers = $this->table->tab_type($table['tab']);
       //print_r($headers);exit;
	    $sqlkey = "SELECT `keys`,`article` FROM int_summary WHERE table_name = '{$table['table_name']}'";
        $keyARR = $this->db->query($sqlkey)->row_array();
        $where = '1 = 1';
        if($keys != ''){
            $eql = explode(' ,',$keys);
            foreach($eql as $vl){ 
                $eq[] = TRIM($vl);
            }
           // print_r($eq);
            $arr = explode(',',$keyARR['keys']);
    		$aa = array();
            foreach($arr as $v){ 
                $aa[] = TRIM(strtolower($v));
            }
            $wh = array_combine($aa,$eq);  
            foreach($wh as $key=>$value){  
            $where .= " and `$key` = {$value}";
            }
        }
        $sql = "Select * FROM {$table['tab']} WHERE {$where} ;";
        $data = isset($key) ? $this->db->query($sql)->row_array() : "";
		//type in sys_format
		$sqlType = "SELECT `type`,`field` FROM sys_format WHERE `table` = '{$table['tab']}'";
        $typeArr = $this->db->query($sqlType)->result_array();
		$strTypeArr= array();
		foreach ($typeArr as $item=> $value) {
			$strTypeArr[$value['field']] = $value['type'];
		}
       // print_R($headers);exit;
        foreach ($headers as $key => $value) {
            $readonly = '0';
            $primary = '0';
            if($keys != ''){
				$readonly = isset($data[$value['Field']]) && in_array(strtolower($value['Field']), $aa ) ? '1' : '0' ;
				$primary = in_array(strtolower($value['Field']), $aa ) ? '1' : '0' ;
				$value_field = isset($data[$value['Field']]) ? $data[$value['Field']] : '';					
			}
			else if($keyARR['article']==1 && $value['Field']=='lang_code'){
				$value_field = 'en';		
			}
			else if ($value['Field']=='date_added') {
				$value_field = date('Y-m-d H:i:s');
			} 
			else if(strtolower($value['Field'])=='id'){
				$readonly = '1' ;
				$primary =  '1' ;
				$value_field = isset($data[$value['Field']]) ? $data[$value['Field']] : '';	
			}
			else {
				$value_field ='';
			}
			
			if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'list') !== FALSE)){
				$start = strpos(strtolower($strTypeArr[$value['Field']]),'(') +1;
				$end = strpos(strtolower($strTypeArr[$value['Field']]),')');
				$str = ($start!==false && $end!==false) ? substr($strTypeArr[$value['Field']],$start,  $end - $start) : '';
				if(trim($str)!=''){
					$headers[$key]['filter'] = $this->table->append_list_editon(strtolower($value['Field']),$str,$value_field , $readonly, $primary);
				}
				else{
					$headers[$key]['filter'] = $this->table->append_select_editon(strtolower($value['Field']),$table['tab'],$value_field, $readonly, $primary);
				}

			}
			else if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'image') !== FALSE)){
				$headers[$key]['filter'] = '<div class="col-md-9">';
				$headers[$key]['filter'] .= $this->table->append_image_editon($value_field,strtolower($value['Field']));
				$headers[$key]['filter'] .= '</div>';

			}
			else if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'html') !== FALSE)){
				$headers[$key]['filter'] = '<div class="col-md-9">';
				$headers[$key]['filter'] .= $this->table->append_html_editon(strtolower($value['Field']),$value_field);
				$headers[$key]['filter'] .= '</div>';

			}
			else if (isset($strTypeArr[$value['Field']]) && (strpos(strtolower($strTypeArr[$value['Field']]), 'file') !== FALSE)){
				$headers[$key]['filter'] = '<div class="col-md-9">';
				$headers[$key]['filter'] .= $this->table->append_file_editon($value_field,strtolower($value['Field']));
				$headers[$key]['filter'] .= '</div>';

			}
			
			else {
				$headers[$key]['filter'] = '<div class="col-md-9">';
				if(($value['Field']=='date_update')){
					$headers[$key]['filter'] .= $this->table->append_date_editon(strtolower($value['Field']),  date('Y-m-d H:i:s'), '1');
				}else if(strtolower($value['Field'])=='password'){
					$headers[$key]['filter'] .= $this->table->append_input_editon(strtolower($value['Field']),$value_field, $readonly, $primary, 'password');
				}
				else if ((strpos(strtolower($value['Type']), 'text') !== FALSE)|| (strpos(strtolower($value['Type']), 'longtext') !== FALSE)){
					$headers[$key]['filter'] .= $this->table->append_textarea_editon(strtolower($value['Field']),$value_field);
				}				
				else if((strpos(strtolower($value['Type']), 'varchar') !== FALSE) || (strpos(strtolower($value['Type']), 'int') !== FALSE)||(strpos(strtolower($value['Type']), 'double') !== FALSE)||(strpos(strtolower($value['Type']), 'interger') !== FALSE) ){
					$headers[$key]['filter'] .= $this->table->append_input_editon(strtolower($value['Field']),$value_field, $readonly,$primary);
				}
				else if((strpos(strtolower($value['Type']), 'date') !== FALSE)){
					$headers[$key]['filter'] .= $this->table->append_date_editon(strtolower($value['Field']), $value_field, $readonly,$primary);
				}
				else{
					$headers[$key]['filter'] .= $this->table->append_input_editon(strtolower($value['Field']),$value_field, $readonly, $primary);
				}
				$headers[$key]['filter'] .= '</div>';
			}
			
        }
        $this->data->headers = $headers;
        $this->data->name_table = $table['table_name'];
        $this->data->name_desc = $table['description'];
		$this->data->keys = $keys;
        $this->load->view('vnxindex/modal', $this->data);
    }    
    function update_modal() {
		ini_set('post_max_size', '200M');
		ini_set('upload_max_filesize', '200M');
		$table = $_POST['table_name_parent'];
        $keys = TRIM($_POST['keys_value']);
		$arr_table_sys = $this->table->get_summary($table);
		$table_name = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
        $headers = $this->table->tab_type($table_name);
		$sqlkey = "SELECT `keys` FROM int_summary WHERE table_name = '{$table}'";
		$keyARR = $this->db->query($sqlkey)->row_array();
		$eq = array();
		$respone['error'] = '';
        $respone['success'] = '';
		$respone['status'] = '';
		$allowext = array("jpg","png","jpeg");
		if($keys==''){			
			$column ='';
			$value_column='';
			foreach ($headers as $item) {
				if(isset($_FILES[$item['Field']])){
					if ($_FILES[$item['Field']]["error"] > 0) {
							$respone['error'] = $_FILES[$item['Field']]["error"];
						} else {
							$path ='assets/upload/intranet';
							
							if(isset($_POST['group']) && trim($_POST['group'])!='') $path .='/'.strtolower($_POST['group']);
							if(isset($_POST['owner']) && trim($_POST['owner'])!='') $path .='/'.strtolower($_POST['owner']);
							if(isset($_POST['category']) && trim($_POST['category'])!='') $path .='/'.strtolower($_POST['category']);
							if(isset($_POST['subcat']) && trim($_POST['subcat'])!='') $path .='/'.strtolower($_POST['subcat']);
							if (!file_exists($path)) {
								mkdir($path, 0777, true);
							}
							
							if(move_uploaded_file($_FILES[$item['Field']]["tmp_name"], $path.'/'.basename($_FILES[$item['Field']]["name"]))) {
								$ext = substr($_FILES[$item['Field']]["name"], strrpos($_FILES[$item['Field']]["name"], '.') + 1);
								if(in_array($ext, $allowext)) {
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),65);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),145);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),175);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),255);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),450);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),600);
								}
								$column .= $column==''? "`{$item['Field']}`" : " , `{$item['Field']}`";
								$value_column .=$value_column==''? "'".$path.'/'.$_FILES[$item['Field']]["name"]."'" : " , '".$path.'/'.$_FILES[$item['Field']]["name"]."'";
							} else {
								$respone['error'] = "Can not upload file";
							}
						}
				}
				else if($item['Field']=='password'){
                        $salt_2 = substr(md5(uniqid(rand(), true)), 0, 10);
    					$new_password_db = $salt_2 . substr(sha1($salt_2 . $_POST[$item['Field']]), 0, -10);
    					$count = strlen($_POST[$item['Field']]);
    				//	$data = array(
//    						'count_pass'=>$count,
//    						'password' => $new_password_db,
//    						'remember_code' => NULL,
//    					);
    					// Count number password
    					
    				//	$this->db->where('id', $this->session->userdata('user_id'))
//    						->update('user', $data);
		               $column .= $column==''? "`{$item['Field']}`" : " , `{$item['Field']}`";
					   $value_column .=$value_column==''? "'".real_escape_string($new_password_db)."'" : " , '".real_escape_string($new_password_db)."'";
                }
				else if(isset($_POST[$item['Field']])){
					$column .= $column==''? "`{$item['Field']}`" : " , `{$item['Field']}`";
					$value_column .=$value_column==''? "'".real_escape_string($_POST[$item['Field']])."'" : " , '".real_escape_string($_POST[$item['Field']])."'";
				}
			} 
			$sql = "INSERT  INTO {$table_name} ($column) VALUES ({$value_column});";
			//echo "<pre>"; print_r($sql);exit;
			$respone['status']  = $this->db->query($sql);
		}
		else{
			$eql = explode(' ,',$keys);
			foreach($eql as $vl){ 
				$eq[] = TRIM($vl);
			}
			$arr = explode(',',$keyARR['keys']);
			foreach($arr as $v){ 
				$aa[] = TRIM($v);
			}
			$wh = array_combine($aa,$eq);  
			
			$where = '1 = 1';
			foreach($wh as $key=>$value){  
				$where .= " and `$key` = {$value}";
			}
			$update = '';
			foreach ($headers as $item) {
				if(isset($_FILES[$item['Field']])){
					if ($_FILES[$item['Field']]["error"] > 0) {
							$respone['error'] = $_FILES[$item['Field']]["error"];
						} else {
							$path ='assets/upload/intranet';
							if(isset($_POST['group']) && trim($_POST['group'])!='') $path .='/'.strtolower($_POST['group']);
							if(isset($_POST['owner']) && trim($_POST['owner'])!='') $path .='/'.strtolower($_POST['owner']);
							if(isset($_POST['category']) && trim($_POST['category'])!='') $path .='/'.strtolower($_POST['category']);
							if(isset($_POST['subcat']) && trim($_POST['subcat'])!='') $path .='/'.strtolower($_POST['subcat']);
							if (!file_exists($path)) {
								mkdir($path, 0777, true);
							}
							if(move_uploaded_file($_FILES[$item['Field']]["tmp_name"], $path.'/'.basename($_FILES[$item['Field']]["name"]))) {
								$ext = substr($_FILES[$item['Field']]["name"], strrpos($_FILES[$item['Field']]["name"], '.') + 1);
								if(in_array($ext, $allowext)) {
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),65);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),145);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),175);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),255);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),450);
									image_thumb_w($path.'/'.basename($_FILES[$item['Field']]["name"]),600);
								}
								
								$update .= $update==''? "`{$item['Field']}` = '".$path.'/'.$_FILES[$item['Field']]["name"]."'" : " , `{$item['Field']}` = '".$path.'/'.$_FILES[$item['Field']]["name"]."'";
							} else {
								$respone['error'] = "Can not upload file";
							}
						}
				}
				else if(isset($_POST[$item['Field']]))
				$update .= $update==''? "`{$item['Field']}` = '".real_escape_string($_POST[$item['Field']])."'" : " , `{$item['Field']}` = '".real_escape_string($_POST[$item['Field']])."'";
				if($item['Field']=='password'){
                        $salt_2 = substr(md5(uniqid(rand(), true)), 0, 10);
    					$new_password_db = $salt_2 . substr(sha1($salt_2 . $_POST[$item['Field']]), 0, -10);
    					$count = strlen($_POST[$item['Field']]);
		                $update .= $update==''? "`{$item['Field']}` = '".real_escape_string($new_password_db)."'" : " , `{$item['Field']}` = '".real_escape_string($new_password_db)."'";
                    }
				//else if ()
			}  			
        	$sql = "UPDATE {$table_name} SET {$update} WHERE {$where} ;";
			//echo "<pre>"; print_r($sql);exit;
			$respone['status']  = $this->db->query($sql);
		}    
        $this->output->set_output(json_encode($respone));
    }
	
	
	function delete_row() {   
        $table = $_POST['table_name'];
        $keys = TRIM($_POST['keys_value']);		
        $arr_table_sys = $this->table->get_summary($table);
		$table_name = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
        $headers = $this->table->tab_type($table_name);
		$sqlkey = "SELECT `keys` FROM int_summary WHERE table_name = '{$table}'";
		$keyARR = $this->db->query($sqlkey)->row_array();
		$eql = explode(' ,',$keys);
		foreach($eql as $vl){ 
			$eq[] = TRIM($vl);
		}
		$arr = explode(',',$keyARR['keys']);
		foreach($arr as $v){ 
			$aa[] = TRIM($v);
		}
		$wh = array_combine($aa,$eq);  
		
		$where = '1 = 1';
		foreach($wh as $key=>$value){  
			$where .= " and `$key` = {$value}";
		}
        //$respone ='false';
		$sql = "DELETE FROM {$table_name} WHERE {$where}";
        
        $respone = $this->db->query($sql);        
        $this->output->set_output(json_encode($respone));
    }
	function insert_row() {   
        $table = $_POST['table_name'];
        $keys = TRIM($_POST['keys_value']);		
        $arr_table_sys = $this->table->get_summary($table);
		$table_name = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
        $headers = $this->table->tab_type($table_name);
		$sqlkey = "SELECT `keys` FROM int_summary WHERE table_name = '{$table}'";
		$keyARR = $this->db->query($sqlkey)->row_array();
		$eql = explode(' ,',$keys);
		foreach($eql as $vl){ 
			$eq[] = TRIM($vl);
		}
		$arr = explode(',',$keyARR['keys']);
		foreach($arr as $v){ 
			$aa[] = TRIM($v);
		}
		$wh = array_combine($aa,$eq);  
		
		$where = '1 = 1';
		$arr_tempt = array();
		foreach($wh as $key=>$value){  
			$where .= " and `$key` = {$value}";
			$arr_tempt[$key]=Null;
			
		}
		$sql = "SELECT * FROM {$table_name} WHERE {$where}";
		$valueARR = $this->db->query($sql)->row_array();
		
		$this->db->insert($table_name, $arr_tempt);
        $id = $this->db->insert_id();
		$value_insert ='';
		$i = 0;
		foreach($wh as $key=>$value){  
		$value_insert .= $i==0 ? "{$key}={$id}" : ",{$key}={$id}";
			$i ++;
		}
		$query = "UPDATE {$table_name} SET ";
		  foreach ($valueARR as $key => $value) {
			if (!in_array($key, $keyARR)){
				$query .= '`'.$key.'` = "'.str_replace('"','\"',$value).'", ';
			}
		  }
		  $query = substr($query,0,strlen($query)-2); # lop off the extra trailing comma
		  $query .= " WHERE {$value_insert}";
		  $respone = $this->db->query($query);  
	
		/*$sql = "INSERT  INTO {$table_name} ($column) VALUES ({$value_column});";
		$respone = $this->db->query($sql);  
        //$respone ='false';
		$sql = "DELETE FROM {$table_name} WHERE {$where}";
        
        $respone = $this->db->query($sql);        */
        $this->output->set_output(json_encode($respone));
    }
	
	function delete_row_vnxindex() {   
        $keys = TRIM($_POST['keys_value']);		
        //$respone ='false';
		$sql = "DELETE FROM ifrc_articles WHERE id = $keys";
        $respone = $this->db->query($sql);        
        $this->output->set_output(json_encode($respone));
    }
    
    function checkupdate(){
        $this->db->from('user_log');
//        $this->db->where('user_log.user_id =', $this->session->userdata('user_id'));
        $query = $this->db->get();
        $data = $query->result_array();
        foreach($data as $row) {
          if($row['user_id'] == $this->session->userdata('user_id')){
                $sql = "UPDATE user_log
            			SET `status`=1,
                         lastactive=NOW()   
            			WHERE user_id='".$row['user_id']."' LIMIT 1";
                $this->db->query($sql);
          }else{
        //print_R(date('Y-m-d H:i:s',strtotime("+30 minutes", strtotime($row['lastactive']))));
              if(date('Y-m-d H:i:s',strtotime("+30 minutes", strtotime($row['lastactive']))) < date('Y-m-d H:i:s',strtotime("now"))) { // if lastActivity plus five minutes was longer ago then now
                $sql = "UPDATE user_log
            			SET `status`=0  
            			WHERE user_id='".$row['user_id']."'";
                $this->db->query($sql);
                // Green light
              }else {
                $sql = "UPDATE user_log
            			SET `status`=1 
            			WHERE user_id='".$row['user_id']."'";
                $this->db->query($sql);
              }
          }
        }
        $this->db->from('user_log');
        $this->db->join('user', 'user_log.user_id = user.id');
        $this->db->where('user_log.user_id !=', $this->session->userdata('user_id'));
        $query = $this->db->get();
        $data = $query->result_array();
        $html = $this->setListUser($data);
        $this->output->set_output(json_encode($html));
    }
    function setListUser($data){
        $html='';
        foreach($data as $value){
             $html.='<li class="media">';
			 $html.='<div class="media-status">';
			 $html.='<span class="badge badge-'.($value['status'] == 1 ? "success" : "danger").'">'.($value['status'] == 1 ? "online" : "offline").'</span>';
			 $html.='</div>';
			 $html.='<img class="media-object" height="45px" src="'.base_url().$value['avatar'].'" alt="..."/>';
			 $html.='<div class="media-body">';
			 $html.='<h4 class="media-heading">'.$value['last_name'].' '.$value['first_name'].'</h4>';
			 $html.='<div class="media-heading-sub">';
			 $html.=$value['services']; 
			 $html.='</div>';
			 $html.='</div>';
			 $html.='</li>';
        }
        return $html;
    }
      //log the user out
    function logout() {
        $sql = "UPDATE user_log
    			SET `status`=0  
    			WHERE user_id='".$this->session->userdata('user_id')."'";
        $this->db->query($sql);
        $this->ion_auth->logout();
		if (isset($_SESSION['intranet_clean']['username'])) :
			session_unset();
			session_destroy();
		endif;
        echo (true);
    }
    
 

	
	public function add_intranet_article($param) {
		
		$this->load->model('Vnxindex_model','vnxindexmodel');
		//image
		$id = $this->vnxindexmodel->getArticleFinal_intranet();
		$clean_artid = $this->vnxindexmodel->getCleanartidFinal_intranet();
		
		//echo $id."-".$clean_artid;exit;
		 //add article_clean
		$arr_lang = array($id=>"en",$id+1=>"vn",$id+2=>"fr");
		//echo "<pre>";print_r($param);exit;
	$param['date_update'] = date("Y-m-d H:i:s");
	 $data_article_clean = array(
		  // 'category_id' => $param['category_id'],
		   'status' => $param['status'],
		   'sort_order' => $param['sort_order'],
		   'image' => $this->var_image,
		    'file' => $this->var_files,
		   'url' => $param['url'],
		   //'url_second' => $param['url_second'],
		   //'url_third' => $param['url_third'],
		   'website' => $param['website'],
		   //'par_cat_id' => $param['par_cat_id'],
			'clean_cat' => $param['clean_cat'],
			'clean_scat' => $param['clean_scat'],
		   //'date_creation' => date("Y-m-d H:m:s",now()),
		    'date_creation' => $param['date_add'],
			'date_update' => $param['date_update'],
			'name_user'	=> $this->session->userdata('username'),
		   
	  );
	  /*if(isset($_POST['get_filter'])){
			$count_arr = 11;  
	  }
	  else{
		$count_arr = 10;  		
	  }*/
	 // $cut_array = array_slice($param,-10,9);
	   $cut_array = $param;
	  foreach($cut_array as $k=>$va){
			if(substr($k,0,-3) != 'title' && substr($k,0,-3) != 'description' && substr($k,0,-3) != 'long_description'){
				unset($cut_array[$k]); 
			}
				
		}
		
		for($i=0;$i<3; $i++){
			  $a = $i*3;
			 $b = array_keys(array_slice($cut_array,$a,1));
			 $cut_array_lang[$b[0]][] = array_slice($cut_array,$a,3); 
		}
		
		foreach($arr_lang as $key=>$lang_val){
			$this->vnxindexmodel->add_ifrc_articles_intranet($data_article_clean,$key,$clean_artid,$lang_val,$cut_array_lang['title_'.$lang_val]);	
		}
		
			
			// add_article_desc_clean
			
			
			//$this->vnxindexmodel->addArticleDescClean($cut_array_lang,$param,$id); 
	}
	
	
	function add_vnxindex_article($param) {
		
		$this->load->model('Vnxindex_model','vnxindexmodel');
		//image
		$id = $this->vnxindexmodel->getArticleFinal();
		$clean_artid = $this->vnxindexmodel->getCleanartidFinal();
		
		//echo $id."-".$clean_artid;exit;
		 //add article_clean
		$arr_lang = array($id=>"en",$id+1=>"vn",$id+2=>"fr");
		//
	$param['date_update'] = date("Y-m-d H:i:s");
	 $data_article_clean = array(
		  // 'category_id' => $param['category_id'],
		   'status' => $param['status'],
		   'sort_order' => $param['sort_order'],
		   'image' => $this->var_image,
		   'file' => $this->var_files,
		   'url' => $param['url'],
		   //'url_second' => $param['url_second'],
		   //'url_third' => $param['url_third'],
		   'website' => $param['website'],
		   //'par_cat_id' => $param['par_cat_id'],
			'clean_cat' => $param['clean_cat'],
			'clean_scat' => $param['clean_scat'],
		   //'date_creation' => date("Y-m-d H:m:s",now()),
		    'date_creation' => $param['date_add'],
			'date_update' => $param['date_update'],
			'name_user'	=> $this->session->userdata('username'),
		   
	  );
	  
	 /* if(isset($_POST['get_filter'])){
			$count_arr = 10;  
	  }
	  else{
		$count_arr = 9;  		
	  }*/
	  //$cut_array = array_slice($param,-10,9);
	  $cut_array = $param;
	  foreach($cut_array as $k=>$va){
			if(substr($k,0,-3) != 'title' && substr($k,0,-3) != 'description' && substr($k,0,-3) != 'long_description'){
				unset($cut_array[$k]); 
			}
				
		}
		//echo "<pre>";print_r($cut_array);exit;
		for($i=0;$i<3; $i++){
			  $a = $i*3;
			 $b = array_keys(array_slice($cut_array,$a,1));
			 $cut_array_lang[$b[0]][] = array_slice($cut_array,$a,3); 
		}
		
		foreach($arr_lang as $key=>$lang_val){
			$this->vnxindexmodel->add_ifrc_articles($data_article_clean,$key,$clean_artid,$lang_val,$cut_array_lang['title_'.$lang_val]);	
		}
		
			
			// add_article_desc_clean
			
			
			//$this->vnxindexmodel->addArticleDescClean($cut_array_lang,$param,$id); 
	}
	
	
	
	// DUNG CHO IFRC_ARTICLE
	 public function add_modal_vnxindex() {
		 //xu ly filter
		 if(isset($_POST['dataArr'])){
			 
			 $this->session->set_userdata('clean_artid',$_POST['dataArr'][0]);
			 $this->session->set_userdata('lang_code',$_POST['dataArr'][1]);
			 $this->session->set_userdata('clean_cat',$_POST['dataArr'][2]);
			 $this->session->set_userdata('clean_scat',$_POST['dataArr'][3]);
			 $this->session->set_userdata('title',$_POST['dataArr'][4]);
			 $this->session->set_userdata('website',$_POST['dataArr'][5]);
			 $this->session->set_userdata('clean_order',$_POST['dataArr'][6]);
			 $this->session->set_userdata('status',$_POST['dataArr'][7]);
			 $this->session->set_userdata('html_description',$_POST['dataArr'][8]);
			 $this->session->set_userdata('html_long_description',$_POST['dataArr'][9]);
			 $this->session->set_userdata('date_creation_start',$_POST['dataArr'][10]);
			 $this->session->set_userdata('date_creation_end',$_POST['dataArr'][11]);
		 }
		//end xu ly filter
		 $this->load->model('Vnxindex_model','modelvnxindex');
		
		   //form validate
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title_en', 'Title English', 'required');
			$this->form_validation->set_rules('title_fr', 'Title France', 'required');
			$this->form_validation->set_rules('title_vn', 'Title VN', 'required');
			
			 $list_website = $this->modelvnxindex->list_website();
			
			 $this->data->list_website = isset($list_website) ? $list_website : array();
			 
			 $list_cat = $this->modelvnxindex->list_cat();
		
			$this->data->list_cat = isset($list_cat) ? $list_cat : array();
			
			 $list_scat = $this->modelvnxindex->list_scat();
			
			$this->data->list_scat = isset($list_scat) ? $list_scat : array();
			
			$this->data->int_article_websites = $this->modelvnxindex->get_int_article_website();
			//echo "<pre>";print_r($this->data->int_article_websites);exit;
			// Dung cho khi add xong trở về list vẫn giữ filter
			$str_filter = '';
			
			foreach($_GET as $k=>$v){
				if($v != ''){
					$str_filter .= $k."=".$v."&";
				}
			}
			$this->data->get_filter = $str_filter;
		 	if ($this->form_validation->run() == FALSE)
			{
				
				
				 $this->template->write_view('content', 'vnxindex/add',$this->data);
				 $this->template->render();
			
			}else{
			
			  if (isset($_POST['ok'])) {
				
				//call function insert data 
					  if($_FILES['image']['name'] != NULL){ // Đã chọn file
					// Tiến hành code upload file
					if($_FILES['image']['type'] == "image/jpeg" ||$_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/gif"){
					// là file ảnh
					// Tiến hành code upload    
						if($_FILES['image']['size'] > 1048576){
							echo "File not larger 1mb";
						}else{
							$uploaddir = 'assets/upload/images/';
							$uploadfile = $uploaddir . $_FILES['image']['name'];
							if (!file_exists($uploaddir)) {
								mkdir($uploaddir, 0777, true);
							}
							$tmp_name = $_FILES['image']['tmp_name'];
							
							// Upload file
							if(move_uploaded_file($tmp_name, $uploadfile)) {
								$this->var_image = $uploadfile;
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),65);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),145);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),175);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),255);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),450);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),600);
							} else {
								echo 'Upload directory is not writable, or does not exist.';
							}
							
						
					   }
					}
				  }
				  if($_FILES['files']['name'] != NULL){
							$uploaddir_files = 'assets/upload/images/';
							$uploadfile_files = $uploaddir_files . $_FILES['files']['name'];
							if (!file_exists($uploaddir_files)) {
								mkdir($uploaddir_files, 0777, true);
							}
							$tmp_name_file = $_FILES['files']['tmp_name'];
							
							// Upload file
							if(move_uploaded_file($tmp_name_file, $uploadfile_files)) {
								$this->var_files = $uploadfile_files;
							} else {
								echo 'Upload directory is not writable, or does not exist.';
							}	  
				  }
			  
				
				$this->add_vnxindex_article($_POST,$this->var_image,$this->var_files);
				
				redirect(base_url()."vnxindex/ifrc_articles");
			}
	
				
				//$this->data->info = $_POST['param'];
				 $this->template->write_view('content', 'vnxindex/add',$this->data);
				$this->template->render();
		}
    }
	
	
	public function add_modal_intranet() {
		 //xu ly filter
		 if(isset($_POST['dataArr'])){
			 
			 $this->session->set_userdata('clean_artid',$_POST['dataArr'][0]);
			 $this->session->set_userdata('lang_code',$_POST['dataArr'][1]);
			 $this->session->set_userdata('clean_cat',$_POST['dataArr'][2]);
			 $this->session->set_userdata('clean_scat',$_POST['dataArr'][3]);
			 $this->session->set_userdata('title',$_POST['dataArr'][4]);
			 $this->session->set_userdata('website',$_POST['dataArr'][5]);
			 $this->session->set_userdata('clean_order',$_POST['dataArr'][6]);
			 $this->session->set_userdata('status',$_POST['dataArr'][7]);
			 $this->session->set_userdata('html_description',$_POST['dataArr'][8]);
			 $this->session->set_userdata('html_long_description',$_POST['dataArr'][9]);
			 $this->session->set_userdata('date_creation_start',$_POST['dataArr'][10]);
			 $this->session->set_userdata('date_creation_end',$_POST['dataArr'][11]);
		 }
		//end xu ly filter
		 $this->load->model('Vnxindex_model','modelvnxindex');
		
		   //form validate
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title_en', 'Title English', 'required');
			$this->form_validation->set_rules('title_fr', 'Title France', 'required');
			$this->form_validation->set_rules('title_vn', 'Title VN', 'required');
			
			 $list_website = $this->modelvnxindex->list_website_intranet();
			
			 $this->data->list_website = isset($list_website) ? $list_website : array();
			 
			 $list_cat = $this->modelvnxindex->list_cat_intranet();
		
			$this->data->list_cat = isset($list_cat) ? $list_cat : array();
			
			 $list_scat = $this->modelvnxindex->list_scat_intranet();
			 
			 $this->data->int_article_websites = $this->modelvnxindex->get_int_article_website();
			
			$this->data->list_scat = isset($list_scat) ? $list_scat : array();
			// Dung cho khi add xong trở về list vẫn giữ filter
			$str_filter = '';
			
			foreach($_GET as $k=>$v){
				if($v != ''){
					$str_filter .= $k."=".$v."&";
				}
			}
			$this->data->get_filter = $str_filter;
		 	if ($this->form_validation->run() == FALSE)
			{
				
				
				 $this->template->write_view('content', 'table/add',$this->data);
				 $this->template->render();
			
			}else{
			
			  if (isset($_POST['ok'])) {
				  
				//call function insert data 
					  if($_FILES['image']['name'] != NULL){ // Đã chọn file
					// Tiến hành code upload file
					if($_FILES['image']['type'] == "image/jpeg" ||$_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/gif"){
					// là file ảnh
					// Tiến hành code upload    
						if($_FILES['image']['size'] > 1048576){
							echo "File not larger 1mb";
						}else{
							$uploaddir = 'assets/upload/images/';
							$uploadfile = $uploaddir . $_FILES['image']['name'];
							if (!file_exists($uploaddir)) {
								mkdir($uploaddir, 0777, true);
							}
							$tmp_name = $_FILES['image']['tmp_name'];
							
							// Upload file
							if(move_uploaded_file($tmp_name, $uploadfile)) {
								$this->var_image = $uploadfile;
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),65);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),145);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),175);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),255);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),450);
								image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),600);
							} else {
								echo 'Upload directory is not writable, or does not exist.';
							}
							
						
					   }
					}
				  }
				  
				   if($_FILES['files']['name'] != NULL){
							$uploaddir_files = 'assets/upload/images/';
							$uploadfile_files = $uploaddir_files . $_FILES['files']['name'];
							if (!file_exists($uploaddir_files)) {
								mkdir($uploaddir_files, 0777, true);
							}
							$tmp_name_file = $_FILES['files']['tmp_name'];
							
							// Upload file
							if(move_uploaded_file($tmp_name_file, $uploadfile_files)) {
								$this->var_files = $uploadfile_files;
							} else {
								echo 'Upload directory is not writable, or does not exist.';
							}	  
				  }
			  
				
				$this->add_intranet_article($_POST,$this->var_image,$this->var_files);
				
				redirect(base_url()."table/ifrc_articles");
			}
	
				
				//$this->data->info = $_POST['param'];
				 $this->template->write_view('content', 'table/add',$this->data);
				$this->template->render();
		}
    }
	
	public function update_modal_vnxindex() {
		 
		$image = '';
		$files = '';
		
		// Dung cho khi add xong trở về list vẫn giữ filter
		$str_filter = '';
		foreach($_GET as $k=>$v){
			if($v != ''){
				$str_filter .= $k."=".$v."&";
			}
		}
		$this->data->get_filter = $str_filter;
		
		  if (isset($_POST['ok'])) {
			  //image
				//print_r($_FILES);exit;
				$image = '';
			  if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != NULL){ // Đã chọn file
			
				// Tiến hành code upload file
				if($_FILES['image']['type'] == "image/jpeg" ||$_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/gif"){
				// là file ảnh
				// Tiến hành code upload    
					if($_FILES['image']['size'] > 1048576){
						echo "File not larger 1mb";
					}else{
						$uploaddir = 'assets/upload/images/';
						
						$uploadfile = $uploaddir . $_FILES['image']['name'];
						if (!file_exists($uploaddir)) {
							mkdir($uploaddir, 0777, true);
						}
						$tmp_name = $_FILES['image']['tmp_name'];
						
						// Upload file
						if(move_uploaded_file($tmp_name, $uploadfile)) {
							$image = $uploadfile;
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),65);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),145);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),175);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),255);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),450);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),600);
						} else {
							echo 'Upload directory is not writable, or does not exist.';
						}
						
					
				   }
				}
			  }
			   if(isset($_FILES['files']['name']) && $_FILES['files']['name'] != NULL){ // Đã chọn file
			  		 	$uploaddir_files = 'assets/upload/images/';
						
						$uploadfile_files = $uploaddir_files . $_FILES['files']['name'];
						if (!file_exists($uploaddir_files)) {
							mkdir($uploaddir_files, 0777, true);
						}
						$tmp_name_files = $_FILES['files']['tmp_name'];
						
						// Upload file
						if(move_uploaded_file($tmp_name_files, $uploadfile_files)) {
							$files = $uploadfile_files;
						} else {
							echo 'Upload directory is not writable, or does not exist.';
						}
			  
			   }
			 
            //call function insert data 
			 $this->data->id = $_POST["id"];
			
			
            $this->update_article_vnxindex($_POST,$image, $files);
			if($_POST['get_filter'] !=''){
				redirect(base_url().'vnxindex/ifrc_articles?'.$_POST['get_filter']);
			}else{
				redirect(base_url().'vnxindex/ifrc_articles');	
			}

            //$this->update_article_desc_clean($_POST);
			//redirect(base_url().'cleanarticle');

        }
		else{
			 $id =$this->uri->segment(3);
			 $this->data->hiden = $id;
		}
		 
 		$this->load->model('Vnxindex_model','modelvnxindex');		
        $categories = NULL;
       
        //get all category
       
        // data for dropdown list parent category
		
		 $list_website = $this->modelvnxindex->list_website();
		
		$this->data->list_website = isset($list_website) ? $list_website : array();
		
		 $list_cat = $this->modelvnxindex->list_cat();
		
		$this->data->list_cat = isset($list_cat) ? $list_cat : array();
		
		 $list_scat = $this->modelvnxindex->list_scat();
		
		$this->data->list_scat = isset($list_scat) ? $list_scat : array();
		
		$this->data->int_article_websites = $this->modelvnxindex->get_int_article_website();
	
		 $info = $this->modelvnxindex->show_article_clean_id($id);
		 $result =array();
		 if(count($info) > 0){
		 foreach($info as $val){
			 if($val['lang_code'] == 'en')
				$result['a'] = $val;
			 elseif($val['lang_code'] == 'vn')
				$result['b'] = $val;
			 else $result['c'] = $val;
		}
		
		ksort($result);
		 }
		
		
		
		  if ($result != FALSE) {
            $this->data->input = $result;
			
			//echo "<pre>";print_r($this->data->input);exit;
           
           // $this->data->input['thumb'] = $this->_thumb($result[0]['images']); 
            //  var_export($input['thumb'].'_______________');exit;
          
        }
		
		//$this->data->info = $_POST['param'];
		 $this->template->write_view('content', 'vnxindex/update', $this->data);
        $this->template->render();
     
    }
	
	
	
	public function update_modal_intranet() {
		 
		$image = '';
		$files = '';
		
		// Dung cho khi add xong trở về list vẫn giữ filter
		$str_filter = '';
		foreach($_GET as $k=>$v){
			if($v != ''){
				$str_filter .= $k."=".$v."&";
			}
		}
		$this->data->get_filter = $str_filter;
		
		  if (isset($_POST['ok'])) {
			  //image
				//print_r($_FILES);exit;
				$image = '';
			  if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != NULL){ // Đã chọn file
			
				// Tiến hành code upload file
				if($_FILES['image']['type'] == "image/jpeg" ||$_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/gif"){
				// là file ảnh
				// Tiến hành code upload    
					if($_FILES['image']['size'] > 1048576){
						echo "File not larger 1mb";
					}else{
						$uploaddir = 'assets/upload/images/';
						
						$uploadfile = $uploaddir . $_FILES['image']['name'];
						if (!file_exists($uploaddir)) {
							mkdir($uploaddir, 0777, true);
						}
						$tmp_name = $_FILES['image']['tmp_name'];
						
						// Upload file
						if(move_uploaded_file($tmp_name, $uploadfile)) {
							$image = $uploadfile;
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),65);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),145);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),175);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),255);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),450);
							image_thumb_w($uploaddir.'/'.basename($_FILES['image']['name']),600);
						} else {
							echo 'Upload directory is not writable, or does not exist.';
						}
						
					
				   }
				}
			  }
			  
			  if(isset($_FILES['files']['name']) && $_FILES['files']['name'] != NULL){ // Đã chọn file
			  		 	$uploaddir_files = 'assets/upload/images/';
						
						$uploadfile_files = $uploaddir_files . $_FILES['files']['name'];
						if (!file_exists($uploaddir_files)) {
							mkdir($uploaddir_files, 0777, true);
						}
						$tmp_name_files = $_FILES['files']['tmp_name'];
						
						// Upload file
						if(move_uploaded_file($tmp_name_files, $uploadfile_files)) {
							$files = $uploadfile_files;
						} else {
							echo 'Upload directory is not writable, or does not exist.';
						}
			  
			   }
			
            //call function insert data 
			 $this->data->id = $_POST["id"];
			
			
            $this->update_article_itnranet($_POST,$image, $files);
			if($_POST['get_filter'] !=''){
				redirect(base_url().'table/ifrc_articles?'.$_POST['get_filter']);
			}else{
				redirect(base_url().'table/ifrc_articles');	
			}

            //$this->update_article_desc_clean($_POST);
			//redirect(base_url().'cleanarticle');

        }
		else{
			 $id =$this->uri->segment(3);
			 $this->data->hiden = $id;
		}
		 
 		$this->load->model('Vnxindex_model','modelvnxindex');		
        $categories = NULL;
       
        //get all category
       
        // data for dropdown list parent category
		
		 $list_website = $this->modelvnxindex->list_website_intranet();
		
		$this->data->list_website = isset($list_website) ? $list_website : array();
		
		 $list_cat = $this->modelvnxindex->list_cat_intranet();
		
		$this->data->list_cat = isset($list_cat) ? $list_cat : array();
		
		 $list_scat = $this->modelvnxindex->list_scat_intranet();
		
		$this->data->list_scat = isset($list_scat) ? $list_scat : array();
		
		$this->data->int_article_websites = $this->modelvnxindex->get_int_article_website();
	
		 $info = $this->modelvnxindex->show_article_clean_id_intranet($id);
		 foreach($info as $val){
			 if($val['lang_code'] == 'en')
				$result['a'] = $val;
			 elseif($val['lang_code'] == 'vn')
				$result['b'] = $val;
			 else $result['c'] = $val;
		}
		ksort($result);
		
		
		
		  if ($result != FALSE) {
            $this->data->input = $result;
			
			
			
			//echo "<pre>";print_r($this->data->input);exit;
           
           // $this->data->input['thumb'] = $this->_thumb($result[0]['images']); 
            //  var_export($input['thumb'].'_______________');exit;
          
        }
		
		//$this->data->info = $_POST['param'];
		 $this->template->write_view('content', 'table/update', $this->data);
        $this->template->render();
     
    }
	
	function update_article_vnxindex($param, $image, $files) {
		//echo "<pre>";print_r($this->session->userdata('username'));exit;
		$this->load->model('Vnxindex_model','modelvnxindex');	
	 
			
		$param['date_update'] = date("Y-m-d H:i:s");
		 //add article_clean
		
		 $data_article_vnxindex = array(
			'status' => $param['status'],
            'clean_order' => $param['sort_order'],
            'url' => $param['url'],
			 'website' => $param['website'],
			 'clean_cat' => $param['clean_cat'],
			 'clean_scat' => $param['clean_scat'],
			 'date_creation' => $param['date_add'],
			'date_update' => $param['date_update'],
			'name_user'	=> $this->session->userdata('username'),
            );
			
			if($image != ''){
				$data_article_vnxindex['images']= $image;
			}
			if($files != ''){
				$data_article_vnxindex['file']= $files;
			}
			// add article_desc_clean
		$cut_array = $param;
	  foreach($cut_array as $k=>$va){
			if(substr($k,0,-3) != 'title' && substr($k,0,-3) != 'description' && substr($k,0,-3) != 'long_description'){
				unset($cut_array[$k]); 
			}
				
		}
			//echo "<pre>";print_r($param);exit;
		  $countlangbyid = $this->modelvnxindex->countLangById($param['id']);
		  $clean_artid = $this->modelvnxindex->getCleanArtidById($param['id']);
		  for($i=0;$i<$countlangbyid; $i++){
			  $a = $i*3;
			 $b = array_keys(array_slice ($cut_array,$a,1));
			
			  $cut_array_lang[$b[0]][] = array_slice ($cut_array,$a,3); 
			}
			foreach($cut_array_lang as $key => $val_lang){
					$lang = explode("_",$key);
					$this->modelvnxindex->updateArticleVnxindex($data_article_vnxindex, $val_lang[0], $clean_artid, $lang[1]);
			}
		  			
			
		 
	}
	
	
	function update_article_itnranet($param, $image, $files) {
		//echo "<pre>";print_r($this->session->userdata('username'));exit;
		$this->load->model('Vnxindex_model','modelvnxindex');	
	
		$param['date_update'] = date("Y-m-d H:i:s");
		 //add article_clean
		
		 $data_article_vnxindex = array(
			'status' => $param['status'],
            'clean_order' => $param['sort_order'],
            'url' => $param['url'],
			 'website' => $param['website'],
			 'clean_cat' => $param['clean_cat'],
			 'clean_scat' => $param['clean_scat'],
			 'date_creation' => $param['date_add'],
			'date_update' => $param['date_update'],
			'name_user'	=> $this->session->userdata('username'),
            );
			
			if($image != ''){
				$data_article_vnxindex['images']= $image;
			}
			if($files != ''){
				$data_article_vnxindex['file']= $files;
			}
			// add article_desc_clean
			//  exit;
		  //$cut_array = array_slice($param,$count_arr,-1); 
		  $cut_array = $param;
		  foreach($cut_array as $k=>$va){
				if(substr($k,0,-3) != 'title' && substr($k,0,-3) != 'description' && substr($k,0,-3) != 'long_description'){
					unset($cut_array[$k]); 
				}
					
			}
		 
			  //echo "<pre>";print_r($cut_array);	
		
		  $countlangbyid = $this->modelvnxindex->countLangById_intranet($param['id']);
		  $clean_artid = $this->modelvnxindex->getCleanArtidById_intranet($param['id']);
		  for($i=0;$i<$countlangbyid; $i++){
			  $a = $i*3;
			 $b = array_keys(array_slice ($cut_array,$a,1));
			
			  $cut_array_lang[$b[0]][] = array_slice ($cut_array,$a,3); 
			}
			foreach($cut_array_lang as $key => $val_lang){
					$lang = explode("_",$key);
					$this->modelvnxindex->updateArticleVnxindex_intranet($data_article_vnxindex, $val_lang[0], $clean_artid, $lang[1]);
			}
		  			
			
		 
	}
	
	public function deleteImage(){
		//print_r($_REQUEST);exit;
		$url = $_POST['attr'];
		$url_image = explode('assets',$url);
		$url_cut_image = "assets".$url_image[1]; 
		$data = array(
               'images' => ''
            );

		$this->db->where('images', $url_cut_image);
		$this->db->update('ifrc_articles', $data);
		$result = true;
		$this->output->set_output(json_encode($result));
	}
	
	public function deleteImage_intranet(){
		//print_r($_REQUEST);exit;
		$url = $_POST['attr'];
		$url_image = explode('assets',$url);
		$url_cut_image = "assets".$url_image[1]; 
		$data = array(
               'images' => ''
            );

		$this->db->where('images', $url_cut_image);
		$this->db->update('ifrc_articles', $data);
		$result = true;
		$this->output->set_output(json_encode($result));
	}
	public function deleteFile(){
		//print_r($_REQUEST);exit;
		$url = $_POST['attr'];
		$url_file = explode('assets',$url);
		$url_cut_file = "assets".$url_file[1]; 
		$data = array(
               'file' => ''
            );

		$this->db->where('file', $url_cut_file);
		$this->db->update('ifrc_articles', $data);
		$result = true;
		$this->output->set_output(json_encode($result));
	}
	public function deleteFile_intranet(){
		//print_r($_REQUEST);exit;
		$url = $_POST['attr'];
		$url_file = explode('assets',$url);
		$url_cut_file = "assets".$url_file[1]; 
		$data = array(
               'file' => ''
            );

		$this->db->where('file', $url_cut_file);
		$this->db->update('ifrc_articles', $data);
		$result = true;
		$this->output->set_output(json_encode($result));
	}
	
	public function remove_images(){
		//print_r($_REQUEST);exit;
		$id = $_REQUEST['id_document'];
		$type = $_REQUEST['type'];
		$data = array(
               $type => ''
            );

		$this->db->where('id', $id);
		$this->db->update('int_documents', $data);
		$result = true;
		$this->output->set_output(json_encode($result));
	}
	
    

      
}
