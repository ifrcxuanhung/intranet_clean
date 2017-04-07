<?php
class Table extends Welcome{
    public function __construct() {
        parent::__construct();
		$this->load->model('Table_model', 'table');
    }
	
    public function index($table ='summary', $category = "") {
        
        if(isset($_REQUEST['actexport'])&&($_REQUEST['actexport']=='exportCsv'||$_REQUEST['actexport']=='exportTxt')){
			$this->export();
		}
		else if(isset($_REQUEST['actexport'])&&($_REQUEST['actexport']=='exportXls')){
			$this->exportXls();
		}
		$arr_table_sys = $this->table->get_summary($table);
		$table_sys = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
		$headers = $this->table->get_headers($table_sys);
		foreach ($headers as $key => $value) {
            if(($value['type_search'] == 1)) {
				if(isset($_GET[$value['field']]) && $_GET[$value['field']]) { $value_filter = $_GET[$value['field']];}
				else 
				$value_filter='';
				if((strpos(strtolower($value['type']),'list')!==false)){
					$headers[$key]['filter'] = $this->table->append_select(strtolower($value['field']),$table_sys,'',$value_filter!='' ? $value_filter  : ($value['field']=='category' ? $category :''));
				}
				else {
					switch(strtolower($value['type'])){
						case 'varchar':
						case 'longtext':
						case 'int':
						case 'link':
							$headers[$key]['filter'] = $this->table->append_input(strtolower($value['field']), $value_filter);
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
		$this->data->text_note = isset($arr_table_sys["note"]) ? $arr_table_sys["note"] : '';
        $this->data->category = (isset($_GET["category"]) && $_GET["category"]!='') ? $_GET["category"] : ($category == "" ? "all" : $category);
		
		$this ->data->value_filter =parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		//print_r($this->data->value_filter);
	
        $this->template->write_view('content', 'table/index', $this->data);
        $this->template->render();
    }
	    
    function export() {
		$table = isset($_REQUEST['table_name_export'])? $_REQUEST['table_name_export'] :'';
        $iDisplayStart = intval($_REQUEST['iDisplaystart']);
		$arr_table_sys = $this->table->get_tab($table);
		$arr_table_sys = $this->table->get_summary($table);
		$table_sys = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
		
		$headers = $this->table->get_headers($table_sys);
        // select columns
        $where = "where 1=1";
        $aColumns = array();
		$aColumnsHeader = array();
		foreach ($headers as $item) {
			$aColumnsHeader[]=$item['title'];
		}
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
			}else if($this->input->post('date_'.strtolower($item['field'].'_start')) and strtotime($this->input->post('date_'.strtolower($item['field'].'_start')))) {
				$where .= " and `{$item['field']}` >= '".real_escape_string($this->input->post('date_'.strtolower($item['field'].'_start')))."'";
			} 
			 else if($this->input->post(strtolower($item['field'].'_from'))) {
				$where .= " and `{$item['field']}` >= ".(int)($this->input->post(strtolower($item['field'].'_from')))."";
			}
			if($this->input->post('date_'.strtolower($item['field'].'_end')) and strtotime($this->input->post('date_'.strtolower($item['field'].'_end')))){
				 $where .= " and `{$item['field']}` <= '".real_escape_string($this->input->post('date_'.strtolower($item['field'].'_end')))."'";
			}
			if($this->input->post(strtolower($item['field'].'_to'))) {
				$where .= " and `{$item['field']}` <= ".(int)($this->input->post(strtolower($item['field'].'_to')))."";
			}
             
		}
        unset($headers);

        
		$sTable = $table_sys; //$category == 'all' ? "efrc_".$table : "(SELECT * FROM efrc_".$table." where category = '".$category."') as sTable " ;		
		 if($sTable=='int_summary'){
			 $where .=' and `active` <= '.(($this->session->userdata('user_level')) ? $this->session->userdata('user_level'):0);
		 };
		 $order_by = (($arr_table_sys['order_by']!='') && (!is_null($arr_table_sys['order_by'])))?('order by '.$arr_table_sys['order_by']):'';
		
		if(is_null($arr_table_sys['limit_export']))
		$sql = "select sql_calc_found_rows " . str_replace(' , ', ' ', implode(', ', $aColumns)) . "
    					from {$sTable} {$where} {$order_by};"; 
						
		else {
			if(($this->session->userdata('user_id'))){
        	$detail_user = $this->user->get_detail_user($this->session->userdata('user_id'));
			$level= $detail_user['user_level'];
			}
			else 
			$level = 0;
		$arrlimit = explode(";",$arr_table_sys['limit_export']);
		$limit = isset($arrlimit[$level]) ? $arrlimit[$level] :10;
		$sql = "select sql_calc_found_rows " . str_replace(' , ', ' ', implode(', ', $aColumns)) . "
                from {$sTable} {$where} {$order_by} limit $iDisplayStart,{$limit};";
		}
       // print_r($sql);exit;
        $this->load->dbutil();
        $query = $this->db->query($sql)->result_array();
		if(isset($_REQUEST['actexport'])&&($_REQUEST['actexport']=='exportCsv')){
		$this->dbutil->export_to_csv("{$table_sys}", $query, $aColumnsHeader, null,",", true);
		}
		else if(isset($_REQUEST['actexport'])&&($_REQUEST['actexport']=='exportTxt')){
		$this->dbutil->export_to_txt("{$table_sys}", $query, $aColumnsHeader, null,chr(9), true);
		}		
		die();
		
    }
	function exportXls(){
		$table = isset($_REQUEST['table_name_export'])? $_REQUEST['table_name_export'] :'';
		$content = file_get_contents(base_url().'assets/download/tab_xls.php');
		$content = $this->bodyReport($content,$table);
		header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT");
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/msexcel");
		header ( "Content-Disposition: inline; filename=\"{$table}_".date("dmYhi").".xls\"");
		print($content);
		die();
	}
	function bodyReport($content,$tab_name){
		$arr_table_sys = $this->table->get_summary($tab_name);
		$table_sys = isset($arr_table_sys["tab"]) ? $arr_table_sys["tab"] : 'int_'.$table;
		
		$headers = $this->table->get_headers($table_sys,$tab['query']);
		$this->load->dbutil();
		$arrBody = $this->dbutil->PartitionString('<!--s_heading-->', '<!--e_heading-->', $content);
		$rowInfo = '';
        foreach ($headers as $item) {
			$rowInfo .=$arrBody[1];
			switch($item['align']) {
					case 'L':
						$align = 'align="left"';
						break;
					case 'R';
						$align = ' align="right"';
						break;
					default:
						$align = ' align="center"';
						break;
				}
			$rowInfo = str_replace('{width}', $item['width'], $rowInfo);
			$rowInfo = str_replace('{align}', $align, $rowInfo);
			$rowInfo = str_replace('{title}', $item['title'], $rowInfo);
		}
		$content = $arrBody[0].$rowInfo.$arrBody[2];
		
		$where = "where 1=1";
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
			}else if($this->input->post('date_'.strtolower($item['field'].'_start')) and strtotime($this->input->post('date_'.strtolower($item['field'].'_start')))) {
				$where .= " and `{$item['field']}` >= '".real_escape_string($this->input->post('date_'.strtolower($item['field'].'_start')))."'";
			} 
			 else if($this->input->post(strtolower($item['field'].'_from'))) {
				$where .= " and `{$item['field']}` >= ".(int)($this->input->post(strtolower($item['field'].'_from')))."";
			}
			if($this->input->post('date_'.strtolower($item['field'].'_end')) and strtotime($this->input->post('date_'.strtolower($item['field'].'_end')))){
				 $where .= " and `{$item['field']}` <= '".real_escape_string($this->input->post('date_'.strtolower($item['field'].'_end')))."'";
			}
			if($this->input->post(strtolower($item['field'].'_to'))) {
				$where .= " and `{$item['field']}` <= ".(int)($this->input->post(strtolower($item['field'].'_to')))."";
			}
             
		}
        
		$sTable = $table_sys; 
		 if($sTable=='int_summary'){
			 $where .=' and `active` <= '.(($this->session->userdata('user_level')) ? $this->session->userdata('user_level'):0);
		 };
		 $order_by = (($tab['order_by']!='') && (!is_null($tab['order_by'])))?('order by '.$tab['order_by']):'';
		 
		if(is_null($tab['limit_export']))
        $sql = "select sql_calc_found_rows " . str_replace(' , ', ' ', implode(', ', $aColumns)) . "
                from {$sTable} {$where} {$order_by};";
		else {
			if(($this->session->userdata('user_id'))){
        	$detail_user = $this->user->get_detail_user($this->session->userdata('user_id'));
			$level= $detail_user['user_level'];
			}
			else 
			$level = 0;
		$arrlimit = explode(";",$tab['limit_export']);
		$limit = isset($arrlimit[$level]) ? $arrlimit[$level] :10;
		$sql = "select sql_calc_found_rows " . str_replace(' , ', ' ', implode(', ', $aColumns)) . "
                from {$sTable} {$where} {$order_by} limit {$limit};";
		}
        $data = $this->db->query($sql)->result_array();
		$arrBody = $this->dbutil->PartitionString('<!--s_body-->', '<!--e_body-->', $content);
		$rowInfo = '';
		$i= 0;
		foreach ($data as $key => $value) {
			$rowInfo .='<tr>';
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
				$rowInfo .=$arrBody[1];
				if ($i % 2) 
				$rowInfo = str_replace('{color}', '#f7f7f7', $rowInfo);
				else
				$rowInfo = str_replace('{color}', '#fffff', $rowInfo);
				if(($item['hidden']>= $this->session->userdata('user_level')) && ($item['hidden']!=0)){
						$pattern = '/[\S]/';
						$replacement = '*';
					$rowInfo = str_replace('{body}', '<div'.$align.'>'. preg_replace($pattern, $replacement,  $value[strtolower($item['field'])]).'</div>', $rowInfo);
				}
				else
				$rowInfo = str_replace('{body}', '<div'.$align.'>'.$value[strtolower($item['field'])].'</div>', $rowInfo);
				$i ++;
            }
			$rowInfo .='</tr>';
        }
		$content = $arrBody[0].$rowInfo.$arrBody[2];
		return $content;
	}

    
}