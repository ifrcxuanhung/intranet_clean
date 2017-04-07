<?php
class int_article_model extends CI_Model{
    protected $_lang;
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->_lang = $this->session->userdata('curent_language');
    }
	
	// Function To Fetch Selected Student Record
	
	public function getStartHome(){
		 $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
		
		$sql ="SELECT * FROM ifrc_articles WHERE clean_cat = 'STARTHOME' AND status = 1 AND lang_code = '$lang' ORDER BY clean_order DESC";	
		return $this->db->query($sql)->result_array();
	}
	
}