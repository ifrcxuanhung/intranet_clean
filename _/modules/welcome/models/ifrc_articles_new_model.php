<?php
class Ifrc_articles_new_model extends CI_Model{
    protected $_lang;
    public function __construct() {
        parent::__construct();
		$this->load->database();

		//echo "<pre>";print_r($this->db2);exit;
        $this->load->library('session');
        $this->_lang = $this->session->userdata('curent_language');
		
    }
	
	
}