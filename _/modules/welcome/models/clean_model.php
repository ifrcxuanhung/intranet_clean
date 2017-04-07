<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clean_model extends CI_Model {

    var $table_sys_format = 'sys_format';
    var $table_sys_tab = 'int_summary';

    function __construct() {
        parent::__construct();
    }
    function get_headers ($table='',$field='') {
        $ids = array($table);
        $sql = "select *
                from {$this->table_sys_format} 
                where (`active` <= ".(($this->session->userdata('user_level')) ? $this->session->userdata('user_level'):0). " or `active` =1) and (`active` !=0) and (`table` ='".$table."') order by `order` asc ";
         return $this->db->query($sql)->result_array();
    }
	function get_tab ($tab='') {
		$sql = "select *
                from $this->table_sys_tab 
				where table_name = '$tab' ";
                //where tab = '$tab' and user_level <=".$this->session->userdata('user_level');
		 return $this->db->query($sql)->row_array();       
    }
	function get_summary ($table='') {
		$sql = "select *
                from int_summary 
                where `table_name` = '$table'";
		 return  $this->db->query($sql)->row_array();
    }
	function get_config ($code='') {
		$sql = "select value
                from vdm_config 
                where code = '$code'";
		 return  $this->db->query($sql)->row_array();
    }
    function append_input($field = '', $value_filter='') {
        if($field == '') {
            return '';
        } else {
			if($value_filter!='')
            return '<input type="text" id="'.strtolower($field).'" name="'.strtolower($field).'" placeholder="Search..." class="form-control form-filter input-sm" autocomplete="off" value="'.$value_filter.'"/>';
			else
			return '<input type="text" id="'.strtolower($field).'" name="'.strtolower($field).'" placeholder="Search..." class="form-control form-filter input-sm" autocomplete="off" />';
			
        }
    }
    public function tab_type($table){
        $sql = "SHOW COLUMNS FROM {$table};";  
        return $this->db->query($sql)->result_array(); 
    }    
    
    function append_date($field = '') {
        if($field == '') {
            return '';
        } else {
            $html = '<div class="input-group date date-picker margin-bottom-5">';
            $html .= '<input type="text" id="'.strtolower($field).'_start" name="'.strtolower($field).'_start" placeholder="Choose..." class="form-control form-filter input-sm date-picker" data-date-format="yyyy-mm-dd" autocomplete="off">';
            $html .= '<span class="input-group-btn">';
            $html .= '<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>';
            $html .= '</span>';
            $html .= '</div>';
            $html .= '<div class="input-group date date-picker">';
            $html .= '<input type="text" id="'.strtolower($field).'_end" name="'.strtolower($field).'_end" placeholder="Choose..." class="form-control form-filter input-sm date-picker" data-date-format="yyyy-mm-dd" autocomplete="off">';
            $html .= '<span class="input-group-btn">';
            $html .= '<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>';
            $html .= '</span>';
            $html .= '</div>';
            return $html;
        }
    }
	 function append_range($field = '') {
        if($field == '') {
            return '';
        } else {
            $html = '<div class="margin-bottom-5">';
			$html .='<input type="text" placeholder="From" name="'.strtolower($field).'_from" class="form-control form-filter input-sm">';
            $html .= '</div>';
			$html .= '<input type="text" placeholder="To" name="'.strtolower($field).'_to" class="form-control form-filter input-sm">';
            return $html;
        }
    }
    function append_select($field = '',$arr='', $format_n='', $value_filter='') {
        if($field == '') {
            return '';
        } else {
            $html = '<select id="'.$field.'" name="'.$field.'" class="form-control form-filter input-sm" autocomplete="off">';
            $html .= '<option value="all">All</option>';
            $list_data = $this->db->query("select `$field` from $arr group by `$field` order by `$field` asc;")->result_array();
			//var_export($list_data);
            foreach ($list_data as $item) {
                if($item[$field] !='' ){
    				if((strpos(strtolower($format_n),'decimal')!==false)&&$item[$field]!=''){
    					$start = strpos(strtolower($format_n),'(') +1;
    					$end = strpos(strtolower($format_n),')');
    					$str = ($start!==false && $end!==false) ? intval(substr($format_n,$start,  $end - $start)) : 0;
    					$value = number_format((strtolower($item[$field])), $str, '.', ',');
    				}
    				else if ((strpos(strtolower($format_n),'date')!==false)&&$item[$field]!=''){
    					$start = strpos(strtolower($format_n),'(') +1;
    					$end = strpos(strtolower($format_n),')');
    					$str = ($start!==false && $end!==false) ? substr($format_n,$start,  $end - $start) : 0;
    					$value = date($str,strtotime($item[$field]));
    				}
    				else 
    				$value = $item[$field];
    			//	$item[$field] = $item[$field]!='' ? $item[$field] :'empty';
    				if($value_filter!='' && (strtolower($item[$field])==strtolower($value_filter)))
                    $html .= '<option value="'.$item[$field].'" selected="selected">'.$value.'</option>';
    				else 
    				 $html .= '<option value="'.$item[$field].'">'.$value.'</option>';
                }
            }
            return $html;
        }
    }
    function append_select2($field = '', $table, $where, $selected){
        if($field == '') {
            return '';
        } else {
            $list_data = $this->db->query("select `$field` from $table group by `$field` order by `$field` asc;")->result_array();
         //   $read = $readonly == '1' ? 'disabled' : '' ;
		//	$strPrimary = $primary == '1' ? ' data-required="1" ': '' ;
            $selected = isset($selected) ? $selected :  '';
    		$html  ='<select class="form-control select2me" name="'.$field.'" id="'.$field.'">';
            //print_R($list_data);
    		//$html .='<option value="">Choose...</option>';                           
            foreach ($list_data as $val)
    		{
    		   //print_R($val); exit;
    			$sel = $val[$field] == $selected ? ' selected="selected"' : '';
    			$html .= '<option value="'.$val[$field].'"'.$sel.'>'.(string) $val[$field]."</option>\n";    
    		}        
    		$html .='</select>'; 
        }
        return $html;  
    }
    
    //editon
    function append_input_editon($field = '' ,$value = '', $readonly = '',$primary=0, $type='text') {
        if($field == '') {
            return '';
        } else {
            $read = $readonly == '1' ? 'readonly="readonly"' : '' ;
			$strPrimary = $primary == '1' ? 'data-required="1" ' : '' ;
            return '<input type="'.$type.'" id="'.strtolower($field).'" '.$read.$strPrimary.' name="'.strtolower($field).'" value="'.$value.'" class="form-control"/>';
        }
    }
    function append_date_editon($field = '',$value ='',$readonly = '',$primary=0) {
        if($field == '') {
            return '';
        } else {
			$read = $readonly == '1' ? 'readonly="readonly"' : '' ;
            $html = '<div class="input-icon"><i class="fa fa-calendar"></i><input type="text" data-date-viewmode="years" id="'.strtolower($field).'" name="'.strtolower($field).'"'.$read.'  data-date-format="yyyy-mm-dd" data-date="'.$value.'" value="'.$value.'" size="16" class="form-control date-picker"></div>';
            return $html;
        }
    }
    
    function append_image_editon($image = '', $field='') {
        
        $base_url = base_url();
		$html ='<div class="fileinput ';
		if(isset($image)&&$image!="") 
		$html .= 'fileinput-exists' ;
		else 
		$html .='fileinput-new';
		$html .='  " data-provides="fileinput" data-name="'.$field.'">';
		$html .='<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="max-width: 200px; max-height: 150px;" id="'.$field.'">';
		 
		$html .='<img alt="" src="'. (isset($image)&&$image!="" ? $base_url.$image : $base_url."assets/images/no-image.jpg") .'" class="img_upload_'.$field.'" style="max-height: 100px;" >';
		$html .='</div>';
		$html .='<div id="file">';
		$html .='<span class="btn btn-icon-only blue btn-file">';
		$html .='<span class="fileinput-new">';
		$html .='<i class="fa fa-plus"></i> </span>';
		$html .='<span class="fileinput-exists"><i class="fa fa-edit"></i> </span>';
		$html .='<input type="file" name="'.$field.'" id="'.$field.'" class="file_upload" value="'. (isset($image)&&$image!="" ? $base_url.$image : $base_url."assets/images/no-image.jpg") .'" >';
		$html .='</span>';
		$html .='<a href="javascript:;" class="btn btn-icon-only red fileinput-exists" data-dismiss="fileinput">
                                                        <i class="fa fa-remove"></i> </a>';
		$html .='</div>';
		$html .='</div>';
        return $html;
    }
    function append_textarea_editon($field = '',$value = '', $maxlenght = null, $row = 1){
       // print_r($value);exit;
        $html = '<textarea '. ($maxlenght == null ? '' :  'maxlength="'.$maxlenght.'"').' name="'.$field.'"  id="'.$field.'" rows="'.$row.'" class="form-control' .($maxlenght == null  ? ' ' : ' maxlength-handler').' full">';
        $html .= $value; 
        $html .= '</textarea>';
	//	$html .= $maxlenght == null ? '' : '<span class="help-block"> max '.$maxlenght.' chars </span>';
        return $html;
    }
	function append_html_editon($field = '',$value = '', $maxlenght = null){
		$html = '<textarea class="ckeditor form-control" name="'.$field.'" id="'.$field.'" >';
		$html .= $value; 
		$html .='</textarea>';
        return $html;
    }
    function append_select2_editon($field = '', $options = array(), $selected , $primary = 0){
        if($field == '') {
            return '';
        } else {
            $read = $readonly == '1' ? 'disabled' : '' ;
			$strPrimary = $primary == '1' ? ' data-required="1" ': '' ;
            $selected = isset($selected) ? $selected :  '';
    		$html  ='<select class="form-control select2me" name="'.$field.'" id="'.$field.'" '.$read.$strPrimary.' >';
    		$html .='<option value="">Choose...</option>';                           
            foreach ($options as $key => $val)
    		{
    			$sel = $key == $selected ? ' selected="selected"' : '';
    			$html .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";    
    		}        
    		$html .='</select>'; 
        }  
    }
    
    function append_checkbox_editon($field = '', $options = array(), $checked , $primary = 0){
        if($field == '') {
            return '';
        } else {
            $read = $readonly == '1' ? 'disabled' : '' ;
			$strPrimary = $primary == '1' ? ' data-required="1" ': '' ;
            $check = isset($checked) ? $checked :  '';
    		$html  ='<div class="checkbox-list"> name="'.$field.'" id="'.$field.'" '.$read.$strPrimary.' >';                          
            foreach ($options as $key => $val)
    		{
    			$che = $key == $check ? ' checked' : '';
    			$html .= '<label><input type="checkbox" value="'.$key.'" name="'.$field.'" '.$sel.'/>'.(string) $val."</label>\n";    
    		}        
    		$html .='</div>';
        }  
    }
    function append_select_editon($field = '',$arr='', $value ='', $readonly = '',$primary=0) {
        if($field == '') {
            return '';
        } else {
            $read = $readonly == '1' ? ' disabled' : '' ;
			$strPrimary = $primary == '1' ? 'data-required="1" ': '' ;
			$html ='<div class="col-md-5">';
            $html .= '<select '.$read.' id="'.$field.'" name="'.$field.'" class="form-control select2_'.$field.'" autocomplete="off">';
            $list_data = $this->db->query("select `$field` from $arr group by `$field` order by `$field` asc;")->result_array();
            foreach ($list_data as $item) {
                $select = $item[$field] == $value ? ' selected="selected"' : '';
                $html .= '<option value="'.$item[$field].'"'.$select.'>'.$item[$field].'</option>';
            }
            $html .= '</select></div>';
			if($field!='id')
			$html .='<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control" id="'.$field.'_add_new" name="'.$field.'_add_new">
							<div class="input-group-btn">
								<button class="btn green add-new-item" type="button" value_id="'.$field.'">Add new item</button>
							</div>
							<!-- /btn-group -->
						</div>
						<!-- /input-group -->
					</div>';
            return $html;
        }
    }
	function append_list_editon($field = '',$arr='', $value ='', $readonly = '',$primary=0) {
		
        if($field == '') {
            return '';
        } else {
            $read = $readonly == '1' ? ' disabled' : '' ;
			$strPrimary = $primary == '1' ? 'data-required="1" ': '' ;
			$html ='<div class="col-md-5">';
            $html = '<select '.$read.' id="'.$field.'" name="'.$field.'" class="form-control select2_'.$field.'" autocomplete="off">';
            $list_data = explode(',',$arr);
            foreach ($list_data as $item) {				
				$item = str_replace('"','',$item);
                $select = trim($item) == trim($value) ? ' selected="selected"' : '';
                $html .= '<option value="'.$item.'"'.$select.'>'.$item.'</option>';
            }
            $html .= '</select></div>';
			$html .='<div class="col-md-4">
					<div class="input-group">
						<input type="text" class="form-control" id="'.$field.'_add_new" name="'.$field.'_add_new">
						<div class="input-group-btn">
							<button class="btn green add-new-item" type="button" value_id="'.$field.'">Add new item</button>
						</div>
						<!-- /btn-group -->
					</div>
					<!-- /input-group -->
				</div>';
            return $html;
        }
    }
	function append_file_editon($file = '', $field='') {
		$html ='<input type="file" id="'.$field.'" name="'.$field.'">
										<p class="help-block">'.$file.'</p>';
        return $html;
    }
	function append_html($field = '', $value_filter='') {
        if($field == '') {
            return '';
        } else {
			if($value_filter!='')
            return '<input type="text" id="html_'.strtolower($field).'" name="html_'.strtolower($field).'" placeholder="Search..." class="form-control form-filter input-sm" autocomplete="off" value="'.$value_filter.'"/>';
			else
			return '<input type="text" id="html_'.strtolower($field).'" name="html_'.strtolower($field).'" placeholder="Search..." class="form-control form-filter input-sm" autocomplete="off" />';
			
        }
    }
    
 }