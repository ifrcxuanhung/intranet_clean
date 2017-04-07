<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile_model extends CI_Model {

    var $table_user_info = 'user_info';
    var $table_office_ref = 'office_ref';
    var $table_level_ref = 'level_ref';

    function __construct() {
        parent::__construct();
    }

    function get_detail_user($id_user) {
        $sql = "select dui.*, dor.department, dor.service, dlr.description
                from $this->table_user_info dui
                left join $this->table_office_ref dor
                on dor.id_office = dui.id_office
                left join $this->table_level_ref dlr
                on dlr.id_office = dui.id_office and dlr.level = dui.id_level
                where dui.id_user = '$id_user';";
        return $this->db->query($sql)->row_array();
    }
	
	public function get_detail_user_new($id_user) {
        $sql = "SELECT lu.*,lpf.label,lp.profile_value
                FROM login_users lu LEFT JOIN login_profiles lp ON lu.user_id = lp.user_id LEFT JOIN login_profile_fields lpf ON lp.pfield_id = lpf.id WHERE lu.user_id = $id_user ORDER BY lpf.order ASC";
        return $this->db->query($sql)->result_array();
    }
    
    function get_summary($id_user) {
        $sql = "SELECT * FROM vdm_user_summary WHERE `id_user` = '".$id_user."'";
        return $this->db->query($sql)->row_array();
        
    }

}