<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Portfolio_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    function array_msort($array, $cols)
	{
		$colarr = array();
		foreach ($cols as $col => $order) {
			$colarr[$col] = array();
			foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
		}
		$eval = 'array_multisort(';
		foreach ($cols as $col => $order) {
			$eval .= '$colarr[\''.$col.'\'],'.$order.',';
		}
		$eval = substr($eval,0,-1).');';
		eval($eval);
		$ret = array();
		foreach ($colarr as $col => $arr) {
			foreach ($arr as $k => $v) {
				$k = substr($k,1);
				if (!isset($ret[$k])) $ret[$k] = $array[$k];
				$ret[$k][$col] = $array[$k][$col];
			}
		}
		return $ret;
	
	}
    function get_all_data ($table='') {
		
        $this->db->select("category");
        $this->db->group_by('category');
        $query = $this->db->get($table);
        $data = $query->result_array();
        //return $data;
        //$data[0]['id'];
        $mdata = array();
		$result =array();
        for($i=0; $i<count($data); $i++)
        {
            //$mdata[$i] = $data[$i];
			
            $category = $data[$i]['category'];
			$result["category"][$i] = $category;
            $cdata = $this->getChild($category, $table);
			//$arr2 = $this->array_msort($cdata, array('date'=>SORT_DESC));
			//echo "<pre>";print_r($arr2);
		
            if(!empty($cdata))

            {
                $mdata = array_merge($mdata,$cdata);
			}
               

            
        }

		$result["document"]= $this->array_msort($mdata, array("date"=>SORT_DESC));
			

        return $result;

    }
    
    public function getChild($category, $table)
    {
        $this->db->select("*");
        $this->db->where("category",$category);
		$this->db->order_by('date','DESC');
        $query = $this->db->get($table);
        $data = $query->result_array();
        return $data;  
    }
	
	
}
