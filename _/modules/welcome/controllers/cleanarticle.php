<?php

class Cleanarticle extends Welcome{
	protected $var_image ='';
    public function __construct() {
        parent::__construct();
		$this->load->model('Clean_model', 'table');
		$this->load->helper(array('form', 'url'));
	
    }
    
    public function index($table ='article_clean_2', $category = "") {
		
		$arr_table_sys = $this->table->get_summary($table);
		$arr_table_sys2 = $this->table->get_summary('article_clean_description');
		//echo "<pre>";print_r($arr_table_sys);exit;
		$table_sys = "article_clean";
		
		$headers = $this->table->get_headers("article_clean_2");
		
		foreach ($headers as $key => $value) {
            if(($value['type_search'] == 1)) {
				if(isset($_GET[$value['field']]) && $_GET[$value['field']]) { $value_filter = $_GET[$value['field']];}
				else 
				$value_filter='';
				if((strpos(strtolower($value['type']),'list')!==false)){
					$headers[$key]['filter'] = $this->table->append_select(strtolower($value['field']),$arr_table_sys["query"],'',$value_filter!='' ? $value_filter  : ($value['field']=='category' ? $category :'' ));
				}
				else {
					switch(strtolower($value['type'])) {
						case 'varchar':
						case 'longtext':
						case 'int':
						case 'link':
							$headers[$key]['filter'] = $this->table->append_input(strtolower($value['field']), $value_filter);
							break;
						case 'info':
						case 'html':
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
		//echo "<pre>";print_r($this->data->headers);exit;
        $this->data->table = $table;
		
		$this->data->text_note = $arr_table_sys["note"];
        $this->data->category = (isset($_GET["category"]) && $_GET["category"]!='') ? $_GET["category"] : ($category == "" ? "all" : $category);
		$this->data->value_filter =parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		
		
        $this->template->write_view('content', 'cleanarticle/index', $this->data);
        $this->template->render();
    }
	
	

    
    public function loadcleanarticle(){
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
		
		$sTable =  $arr_table_sys["query"];	
		
		
		   
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
		//$data = $arrColumn;
			
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
				
				  $keys = array();
    				foreach($ke as $val){
    				   $keys[] = "'".$arr_data[$x][strtolower($val)]."' ";
    			   
    				}
    				//print_r($keys);
    				$k = implode(',',$keys);
				
				$path = base_url().'assets/upload/intranet';
				if(isset($arr_data[$x]['group']) && $arr_data[$x]['group']!='') $path .='/'.strtolower($arr_data[$x]['group']);
				if(isset($arr_data[$x]['owner']) && $arr_data[$x]['owner']!='') $path .='/'.strtolower($arr_data[$x]['owner']);
				if(isset($arr_data[$x]['category']) && $arr_data[$x]['category']!='') $path .='/'.strtolower($arr_data[$x]['category']);
				if(isset($arr_data[$x]['subcat']) && $arr_data[$x]['subcat']!='') $path .='/'.strtolower($arr_data[$x]['subcat']);
               // if($table == 'query'){
			         $records["data"][$key][] = '<input type="checkbox" class="checkboxes" value="'.$k.'">';	// check s
              //  }
				
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
					if($item['format_n'] == 'group'){	
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
                         }else{
                            $records["data"][$key][] = $arr_data[$x][strtolower($item['field'])];
                         }
                    }else if($item['format_n'] == 'download'){
                         if(strtolower($arr_data[$x][strtolower($item['field'])]) == 'x'){
							 
                            $records["data"][$key][]= '<a title="'.$arr_data[$x]['file'].'" href="'.$path.'/'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" download="'.$arr_data[$x]['file'].'.'.strtolower($item['field']).'" >
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
						$records["data"][$key][] = "<div".$align."><a data-toggle='tooltip' data-placement='top' data-trigger='hover' data-html='true' data-container='body' data-original-title='".$arr_data[$x][strtolower($item['field'])]."' title='".$arr_data[$x][strtolower($item['field'])]."' class='btn btn-icon-only blue tooltips'  href='#'>
									<i class='fa fa-question'></i></a></div>";
						
					}else if((strpos(strtolower($item['type']),'link')!==false)&&$arr_data[$x][strtolower($item['field'])]!=''){
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
				//echo "<pre>";print_r($keyARR);exit;
                if(isset($keyARR['keys'])&&$keyARR['keys'] != ''){
                    $keys = array();
    				foreach($ke as $val){
    				   $keys[] = "'".$arr_data[$x][strtolower($val)]."' ";
    			   
    				}
    				//print_r($keys);
    				$k = implode(':',$keys);
                    
    				
                    if($keyARR['user_level']>$this->session->userdata('user_level')){
    					$records["data"][$key][] .='';
						
						/* $records["data"][$key][] .= '<center><div class="align-center">'
											.'<a class="btn btn-icon-only green update-modal" type-modal="update" keys_value="'.$k.'" table_name="'.$table.'"  data-target="#modal" data-toggle="modal">
											<i class="fa fa-edit"></i></a>'
											.'<a class="btn btn-icon-only red deleteField" keys_value="'.$k.'" table_name="'.$table.'" href="#">
											<i class="fa fa-trash-o"></i></a>'
											.'</div></center>';*/
						
						
    				}else{
				     $records["data"][$key][] .= '<center><div class="align-center">'
											.'<a href="'.base_url().'cleanarticle/update_modal/'.str_replace("'","",$k).'" class="btn btn-icon-only green " type-modal="update" keys_value="'.$k.'" table_name="'.$table.'" >
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
		 
		$idArr = implode('|',$_POST['dataArr']);
		
		
		foreach($_POST['dataArr'] as $k=>$val){
			$info = explode(",",$val);
			
		   $sql = "Select article_id, lang_code, website FROM article_description_clean WHERE article_id = $info[0] AND website = $info[1] AND lang_code = $info[2] ";
		   $data[$k] = $this->db->query($sql)->result_array();
		}
		$this->data->info = $data;
		$sql = "SELECT DISTINCT website FROM article_description_clean ";
		
        $this->data->getwebsite = $this->db->query($sql)->result_array();
        $this->load->view('cleanarticle/modal', $this->data);
    }
	
	public function check_status_article(){
		//echo "<pre>"; print_r($_POST['dataArr']);
		$even = explode(',',$_POST['dataArr']);
		
		$sql = "UPDATE article_clean  SET status = 0 WHERE article_id = $even[0] AND website = '$even[2]'";
		//echo "<pre>"; print_r($sql);exit;
		if($this->db->query($sql)) echo 1;
		else echo 0;
		
	}
	public function sort_back(){
		$listdata = explode("|,",$_POST['arrayHide']);
		$listdata = array_slice($listdata,0,-1);
		
		$full_array = $listdata;
		
		$dataedit =  explode(",",$_POST['dataEdit']);
		
		$pos = array_search($_POST['dataEdit'],$listdata);
		unset($listdata[$pos]);
		//echo "<pre>"; print_r($listdata);exit;
		// Disable row no select
		foreach($listdata as $disable){
			$value_di = explode(",",$disable);
			$sql = "UPDATE article_clean  SET filter = 9 WHERE article_id = $value_di[0] AND website = '$value_di[2]'";
			$this->db->query($sql);
		}
		
		//echo "<pre>"; print_r($full_array);exit;
		// Update group_news by all goups merge
		foreach($full_array as $val){
			$data = explode(",",$val);
			$sql = "SELECT `group`, website FROM article_clean WHERE  
			 article_id = $data[0] AND website = '$data[2]'";
					
			$result[] = $this->db->query($sql)->row_array();
				
		}
		$groupby ='';
		
		foreach($result as $k=>$value){
			$groupby .= $value['website']."[".$value['group']."],";	
		}
		$groupby = substr($groupby,0,-1);
		
		$sql = "UPDATE article_clean SET `group_news` = '$groupby', `filter` = 1 WHERE  
			 article_id = $dataedit[0] AND website = '$dataedit[2]'";
		 
		$this->db->query($sql);
		echo 1;
	}
	
	 public function add_modal() {
		   $this->load->model('Article_clean_desc_model','modelarticle');
		   
		   $list_website = $this->modelarticle->list_website();
		 //echo "<pre>";print_r($list_website);exit;
			$this->data->list_website = isset($list_website) ? $list_website : array();
		   //form validate
			$this->load->library('form_validation');
			$this->form_validation->set_rules('category_id', 'Category', 'required');
			$this->form_validation->set_rules('title_en', 'Title English', 'required');
			$this->form_validation->set_rules('title_fr', 'Title France', 'required');
			$this->form_validation->set_rules('title_vn', 'Title VN', 'required');
			
		 	if ($this->form_validation->run() == FALSE)
			{
			
				 $this->template->write_view('content', 'cleanarticle/add',$this->data);
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
							} else {
								echo 'Upload directory is not writable, or does not exist.';
							}
							
						
					   }
					}
				  }
			  
				
				$this->add_article_clean($_POST,$this->var_image);
				redirect(base_url().'cleanarticle');
			}
	
		
						//$this->data->info = $_POST['param'];
						 $this->template->write_view('content', 'cleanarticle/add',$this->data);
						$this->template->render();
		}
    }
	
	
	 public function update_modal($table='',$id='') {
		 
		 
		  if (isset($_POST['ok'])) {
			  //image
			
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
						} else {
							echo 'Upload directory is not writable, or does not exist.';
						}
						
					
				   }
				}
			  }
				
					//$this->data->input['image'] = $file;
			 
			  
			  
            //call function insert data 
			 $this->data->hiden = $_POST["article_id"];
			$data = explode(":",$_POST["article_id"]);
		

            $this->update_article_desc_clean($_POST,$this->var_image);
			redirect(base_url().'cleanarticle');

            //$this->update_article_desc_clean($_POST);
			//redirect(base_url().'cleanarticle');

        }
		else{
			 $data = str_replace("%20","",$this->uri->segment(3));
			 $this->data->hiden = $data;
			 $data = explode(":",$data);	
		}
		 
		  $this->load->model('Article_clean_desc_model','modelarticle');
		   $this->load->model('Category_model','category_model');
		
        $categories = NULL;
       
        //get all category
        $list_category = $this->category_model->list_category();
		
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = isset($categories) ? $categories : array();
		
		
		 $list_website = $this->modelarticle->list_website();
		 //echo "<pre>";print_r($list_website);exit;
		$this->data->list_website = isset($list_website) ? $list_website : array();
	
		 
		 
		
		
		
		 
		 $info = $this->modelarticle->show_article_clean_id($data);
	
		  if ($info != FALSE) {
            $this->data->input = $info;
            $this->data->input['url'] = $info['url'];
            $this->data->input['category_id'] =$info['category_id'];
            $this->data->input['sort_order'] = $info['sort_order'];
            $this->data->input['image'] = $info['image'];
			$this->data->input['status'] = $info['status'];
			$this->data->input['website'] = $info['website'];
            $this->data->input['thumb'] = $this->_thumb($info['image']); 
            //  var_export($input['thumb'].'_______________');exit;
          
        }
	
		$this->data->getarticlelang = $this->modelarticle->getAllLangById($data);
		
		
		
		//$this->data->info = $_POST['param'];
		 $this->template->write_view('content', 'cleanarticle/update', $this->data);
        $this->template->render();
     
    }
	
	
	
	
	function update_article_desc_clean($param) {
		 $this->load->model('Article_clean_desc_model','modelarticle');
		
		  
		 //add article_clean
		 $data_article_clean = array(
               'sort_order' => $param['sort_order'],
               'status' => $param['status'],
            	 'image' => $this->var_image,
			   'date_modified' => date("Y-m-d H:m:s",now()),
			   'url' => $param['url'],
			   'group' => $param['group'],
			   'category_id' => $param['category_id'],
			   'website' => $param['website'],
			 
			   'group_news' => $param['group_news'],
			   'filter' => $param['filter'],
			   
            );
			$condition = $param['article_id'];
			
			$this->modelarticle->updateArticleClean($data_article_clean,$condition);
		 
		 // add article_desc_clean
		  $cut_array = array_slice ($param,9,-1); 
		 
		  $data = explode(":",$param["article_id"]);
		  $countlangbyid = $this->modelarticle->countLangById($data);
		  
		  for($i=0;$i<$countlangbyid; $i++){
			  $a = $i*5;
			 $b = array_keys(array_slice ($cut_array,$a,1));
			
			  $cut_array_lang[$b[0]][] = array_slice ($cut_array,$a,5); 
		}
		
		
		$this->modelarticle->updateArticleDescClean($cut_array_lang,$condition);
		
		 
	

	}
	
	function add_article_clean($param) {
		
		 $this->load->model('Article_clean_desc_model','modelarticle');
		//image
		$id = $this->modelarticle->getArticleFinal();
		 //add article_clean
		 $data_article_clean = array(
               'article_id' => $id,
               'category_id' => $param['category_id'],
               'status' => $param['status'],
			   'sort_order' => $param['sort_order'],
			   'image' => $this->var_image,
			   'url' => $param['url'],
			   'group' => $param['group'],
			   'website' => $param['website'],
			 
			   'group_news' => $param['group_news'],
			   'filter' => $param['filter'],
			   'date_added' => date("Y-m-d H:m:s",now()),
			 
			   
            );
			$this->modelarticle->addArticleClean($data_article_clean);
			
			// add_article_desc_clean
			
			$cut_array = array_slice ($param,8,-1);
			
			for($i=0;$i<3; $i++){
				  $a = $i*5;
				 $b = array_keys(array_slice($cut_array,$a,1));
				 $cut_array_lang[$b[0]][] = array_slice($cut_array,$a,5); 
			}
			$this->modelarticle->addArticleDescClean($cut_array_lang,$param,$id); 
			
			

	}
	
	function delete_row() {   
        
        $keys = TRIM($_POST['keys_value']);	
		$cut = explode(":",$keys);
		$cut_array = str_replace("'","",$cut);
			
      //	var_dump($keys);exit;
        //$respone ='false';
		$sql = "DELETE FROM article_description_clean WHERE article_id = $cut_array[0] AND website = '$cut_array[1]' AND lang_code ='$cut_array[2]'";
      
        $respone = $this->db->query($sql);        
        $this->output->set_output(json_encode($respone));
    }
	function clean_daily() {   
        $sql = "update int_actionlist set date= curdate(), status='' where periodic='DAILY';";
        $this->db->query($sql);       
    }
    function clean_monthly() {   
        $sql = "update int_actionlist set date= curdate(), status='' where periodic='MONTHLY';";
        $this->db->query($sql);      
    }


        
    
}