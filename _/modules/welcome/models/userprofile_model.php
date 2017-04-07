<?php
class UserProfile_model extends CI_Model{
	var $table = 'user_profile';
    
	public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    function get_detail_user_profile($id_user, $lang='en') {
        $sql = "SELECT up.*, upn.name FROM user_profile up INNER JOIN user_info ui ON up.id_user = ui.id_user 
				INNER JOIN user_profile_list upl ON upl.info = up.info
				INNER JOIN user_profile_name upn ON upl.info = upn.info
				INNER JOIN user u on u.id = up.id_user
				WHERE up.id_user = $id_user AND up.lan = '$lang' AND upn.lan = '$lang'";
				
		echo $sql;
		return $this->db->query($sql)->result();
    }
}