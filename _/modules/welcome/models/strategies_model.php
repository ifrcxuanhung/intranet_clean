<?php
class Strategies_model extends CI_Model{
    protected $_lang;
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->_lang = $this->session->userdata('curent_language');
    }
    public function list_data(){
        $sql = "SELECT * FROM vdm_strategy_setting Order by id;";
        return $this->db->query($sql)->result_array();
    }
	public function get_data($tab){
		$sql = "select *
                from vdm_strategy_setting 
                where tab = '".$tab."'";
		 return $this->db->query($sql)->row_array();
	}
    public function get_data_desc_($tab, $lang_code){
		$sql = "select *
                from vdm_strategy_description 
                where tab = '".$tab."' and lang ='".strtoupper($lang_code)."'";
		 return $this->db->query($sql)->row_array();
	}
    
     function get_data_desc($tab, $lang_code = NULL) {
        $this->db->select('mt_expec, profit, loss, brk_event');
        $this->db->from('vdm_strategy_description');
        if ($lang_code == NULL):
            $lang_code = $this->session->userdata('curent_language');
        endif;
        $lang_code_default = $this->session->userdata('default_language');
        $this->db->where('tab', $tab);
        $this->db->where('lang', $lang_code['code']);
        $this->db->where('lang', $lang_code_default['code']);
       // $this->db->order_by("article.date_modified", "desc");
        $query = $this->db->get();
        //print_r( $this->db->get());exit;
        return $query->row();
    }
    function get_model_desc($tab, $id) {
        $this->db->select($tab.'_description.*');
        $this->db->from($tab);
        $this->db->join($tab.'_description', $tab . '.id = ' . $tab.'_description.id');
  //      if ($lang_code == NULL):
//            $lang_code = $this->session->userdata('curent_language');
//        endif;
//        $lang_code_default = $this->session->userdata('default_language');
       // $this->db->where('tab', $tab);
        $this->db->where($tab.'_description.id', $id);
//        $this->db->where('lang', $lang_code_default['code']);
       // $this->db->order_by("article.date_modified", "desc");
        $query = $this->db->get();
        //print_r( $this->db->get());exit;
        return $query->row_array();
    }
}


























