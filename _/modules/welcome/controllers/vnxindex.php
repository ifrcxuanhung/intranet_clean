<?php

class Vnxindex extends Welcome{
    public function __construct() {
        parent::__construct();
		$this->load->model('Table_model', 'table');
    }
    
    public function index($table ='summary', $category = "") {
		
		$arr_table_sys = $this->table->get_summary($table);
		//echo "<pre>";print_r($arr_table_sys);exit;
		$table_sys = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
		$headers = $this->table->get_headers($table_sys);
		//var_export($headers);
		foreach ($headers as $key => $value) {
            if(($value['type_search'] == 1)) {
				if(isset($_GET[$value['field']]) && $_GET[$value['field']]) { $value_filter = $_GET[$value['field']];}
				
				else 
				$value_filter='';
				if((strpos(strtolower($value['type']),'list')!==false)){
					$headers[$key]['filter'] = $this->table->append_select_vnxindex(strtolower($value['field']),$table_sys,'',$value_filter!='' ? $value_filter  : ($value['field']=='category' ? $category :'' ));
				}
				else {
					switch(strtolower($value['type'])) {
						case 'varchar':
						case 'longtext':
						case 'int':
						case 'link':
						if($value['field']=='title'){
							$fi1='';
							if(isset($_GET[$value['field']]) && $_GET[$value['field']]) { $fil1 = $_GET[$value['field']];}
							elseif(isset($this->session->userdata['title']) && $this->session->userdata['title']) { 
								$fi1 = $this->session->userdata['title'];
							}
							$headers[$key]['filter'] = $this->table->append_input_title(strtolower($value['field']), $fi1);
						}
						if($value['field']=='clean_order'){
							$fi2='';
							if(isset($_GET[$value['field']]) && $_GET[$value['field']]) { $fil2 = $_GET[$value['field']];}
							elseif(isset($this->session->userdata['clean_order']) && $this->session->userdata['clean_order']) { 
								$fi2 = $this->session->userdata['clean_order'];
							}
							$headers[$key]['filter'] = $this->table->append_input_clean_order(strtolower($value['field']), $fi2);
						}
						else{
							$headers[$key]['filter'] = $this->table->append_input(strtolower($value['field']), $value_filter);	
						}
						
							break;
						case 'html':
						case 'info':
							$headers[$key]['filter'] = $this->table->append_html(strtolower($value['field']), $value_filter);
							break;
						case 'date':
							$headers[$key]['filter'] = $this->table->append_date(strtolower($value['field']));
							break;
						case 'range':
							$headers[$key]['filter'] = $this->table->append_range(strtolower($value['field']));
							break;
						default:
							$headers[$key]['filter'] = '';
					}
                }
            } else {
                $headers[$key]['filter'] = '';
            }
        }
		
		$this->data->headers = $headers;
        $this->data->table = $table;
		
		$this->data->text_note = '';
        $this->data->category = (isset($_GET["category"]) && $_GET["category"]!='') ? $_GET["category"] : ($category == "" ? "all" : $category);
		$this ->data->value_filter =parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		
        $this->template->write_view('content', 'vnxindex/index', $this->data);
        $this->template->render();
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
						print_r($sql);die(); 
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
                if($table == 'query'){
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
						$image =$arr_data[$x][strtolower($item['field'])]!=''? '<img height="'.$height.'" src="'.base_url().$arr_data[$x][strtolower($item['field'])].'" class="thumb" data-height="'.$heightMax.'" >' :'';
						
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
							
				     $records["data"][$key][] .= '<center><div class="align-center">'
											.'<a class="btn btn-icon-only green show-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'"  data-target="#modal" data-toggle="modal">
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
       
        $this->load->view('table/modal', $this->data);
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
	
	
    
    
        
    
}