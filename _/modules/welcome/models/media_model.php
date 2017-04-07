<?php
class Media_model extends CI_Model{
    protected $_lang;
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->_lang = $this->session->userdata('curent_language');
    }
    
    public function getMedia($idMedia)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $sql = "SELECT m.media_id, md.title, m.category_id, m.image, m.link ";
        $sql.= "FROM media m, media_description md ";
        $sql.= "WHERE m.media_id = md.media_id AND STATUS = 1 AND md.lang_code = '$lang' AND m.category_id = $idMedia ";
        $sql.= "ORDER BY sort_order";
        
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getMediaByCateCode($code_cat)
    {
        $sql = "SELECT m.article_id, md.title, m.category_id, m.image, m.url as link, c.category_code
                FROM article m, article_description md , category c
                WHERE m.article_id = md.article_id AND c.category_id = m.category_id 
				AND m.`status` = 1 AND md.lang_code = '".LANG_DEFAULT."'  AND c.category_code = '$code_cat' AND m.sort_order<>0
                ORDER BY m.sort_order";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT m.article_id, md.title, m.category_id, m.image, m.url as link, c.category_code
                FROM article m, article_description md , category c
                WHERE m.article_id = md.article_id AND c.category_id = m.category_id 
				AND m.`status` = 1 AND md.lang_code = '".LANG_DEFAULT."' AND c.category_code = '$code_cat' AND m.sort_order<>0
                ORDER BY m.sort_order";
        $data['default'] = $this->db->query($sql)->result_array();
        
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
}