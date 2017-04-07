<?php
class User_model extends CI_Model{
	var $table_user_info = 'user_info';
    var $table_office_ref = 'office_ref';
    var $table_level_ref = 'level_ref';
    var $table_user = 'user';
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    function get_detail_user($id_user) {
        $sql = "select dui.*, dor.department, dor.service, dlr.description
                from $this->table_user_info dui
                left join $this->table_office_ref dor
                on dor.id_office = dui.id_office
                left join $this->table_level_ref dlr
                on dlr.id_office = dui.id_office and dlr.level = dui.id_level
                where dui.id_user = '$id_user';";
				// print_r($sql);
        return $this->db->query($sql)->row_array();
    }
    
    
    
    public function getInfoUserByEmail($email)
    {
        $sql = "SELECT u.id, u.username, u.email, u.first_name, u.last_name, u.phone, n.email AS email_sub_news ";
        $sql.= "FROM `user` u LEFT JOIN newsletters n ON u.id = n.iduser ";
        $sql.= "WHERE u.email = '$email'";

        $data = $this->db->query($sql)->result_array();
        return $data[0];
    }
    
    public function updateInfoUser($email, $first_name, $last_name, $phone)
    {
        $sql = "update user set first_name = '$first_name', last_name = '$last_name', phone = '$phone' where email = '$email'";
        $this->db->query($sql);
        return true;
    }
    
    public function insert_news_letter($email, $sub_email)
    {
        $sql = "select id from user where email = '$email'";
        $data = $this->db->query($sql)->result_array();
        $id = $data[0]['id'];
        
        $sql = "SELECT * FROM newsletters WHERE iduser = '$id'";
        $data = $this->db->query($sql)->result_array();
        if(empty($data))
        {
           $sql = "insert into newsletters(email, active, time, iduser) value ('$sub_email', 0, now(), '$id')";
        }
        else
        {
            if($sub_email == "")
            {
                $sql = "delete from newsletters where iduser = '$id'";
            }
            else
            {
                $sql = "update newsletters set email = '$sub_email' where iduser = '$id'";
            }
            
        }
        $this->db->query($sql);
        return true;
        
    }
}