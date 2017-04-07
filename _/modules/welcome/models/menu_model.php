<?php
class Menu_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    
    
    
    public function getMenu()
    {
        //$sql = "SELECT id, `name`, link, parent_id, image, `value` ";
        //$sql.= "FROM menu m, menu_description md ";
        //$sql.= "WHERE m.id=md.menu_id AND parent_id = 0 AND `status` = 1 AND lang_code = 'vn'";
        //$data = $this->db->query($sql)->result_array();
        
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $this->db->select("id, `name`, `link`, `parent_id`, `image`, `value`");
        $this->db->join("menu_description md", "md.menu_id = m.id");
        $this->db->order_by("sort_order");
        $this->db->where("parent_id","0");
        $this->db->where("status","1");
        $this->db->where("lang_code","$lang");
        $query = $this->db->get("menu m");
        $data = $query->result_array();
       // echo "<pre>";print_r($data);exit;
        //return $data;
        //$data[0]['id'];
        $menu = array();
        for($i=0; $i<count($data); $i++)
        {
            $menu[$i] = $data[$i];
            $id = $data[$i]['id'];
            $cmenu = $this->getChildMenu($id);
            if(!empty($cmenu))
            {
                $menu[$i]['cmenu'] = $cmenu;
                for($j=0; $j<count($cmenu); $j++)
                {
                    $id2 = $cmenu[$j]['id'];
                    $ccmenu = $this->getChildMenu($id2);
                    if(!empty($ccmenu))
                    {
                        $menu[$i]['cmenu'][$j]['cmenu'] = $ccmenu;
                    }
                    $menu[$i]['list_child'][] = $id2;
                }
               
            }
            
        }
        return $menu;
    }
	
	public function getChildMenu($parent_id)
    {
		$lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $this->db->select("id, `name`, `link`, `parent_id`, `image`, `value`");
        $this->db->join("menu_description md", "md.menu_id = m.id");
        $this->db->order_by("sort_order");
        $this->db->where("parent_id","$parent_id");
        $this->db->where("status","1");
        $this->db->where("lang_code","$lang");
        $query = $this->db->get("menu m");
        $data = $query->result_array();
        
        return $data;  
    }
    

    
    public function listMenu($parentID = null, $stop = "", $data = null, $space = "") {

        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        if ($parentID)
            $this->db->where('parent_id', $parentID);
        else
            $this->db->where('parent_id', 0);

        if ($stop) {
            $this->db->where($this->table . '.id !=', $stop);
        }
        $this->db->where('lang_code', $lang);
        $this->db->from('menu m');
        $this->db->join('menu_description md', 'm.id = md.menu_id');
        $this->db->order_by('sort_order', 'asc');
        $query = $this->db->get();
        $rows = $query->result();
        if ($rows) {
            foreach ($rows as $value) {
                $arr = (object) array();
                $arr->id = $value->id;
                $arr->name = $space . $value->name;
                $arr->sort_order = $value->sort_order;
                $arr->status = $value->status;
                $arr->link = $value->link;
                $arr->image = $value->image;
                $arr->value = $value->value;
                $arr->parent = $value->parent_id;
                $data[] = $arr;
                unset($arr);

                $data = $this->listMenu($value->id, $stop, $data, $space . '');
            }
        }
        return $data;
    }
    
    public function getMainMenubk()
    {
        //$sql = "SELECT id, `name`, link, parent_id, image, `value` ";
        //$sql.= "FROM menu m, menu_description md ";
        //$sql.= "WHERE m.id=md.menu_id AND parent_id = 0 AND `status` = 1 AND lang_code = 'vn'";
        //$data = $this->db->query($sql)->result_array();
        
		$lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $this->db->select("id, `name`, `link`, `parent_id`, `image`, `value`");
        $this->db->join("menu_description md", "md.menu_id = m.id");
        $this->db->order_by("sort_order");
        $this->db->where("parent_id","0");
        $this->db->where("status","1");
        $this->db->where("lang_code","$lang");
        $query = $this->db->get("menu m");
        $data = $query->result_array();
        
        for($i=0; $i<count($data); $i++)
        {
            $menu[$i] = $data[$i];
            $id = $data[$i]['id'];
            $cmenu = $this->getChildMenu($id);
            if(!empty($cmenu))
            {
                $menu[$i]['cmenu'] = $cmenu;
            }
        }
        return $menu;
    }
	
	

    public function getMenuById($id) {
        $menu = array('id' => $id, 'name' => '', 'link' => '', 'parent_id' => '', 'image' => '', 'value' => '');
        if(is_numeric($id)) {
            // get menu by current language
            $lang = $this->session->userdata('curent_language');
            $lang = $lang['code'];
            $this->db->select("id, `name`, `link`, `parent_id`, `image`, `value`");
            $this->db->join("menu_description md", "md.menu_id = m.id");
            $this->db->where("m.id", $id);
            $this->db->where("lang_code", "$lang");
            $current = $this->db->get("menu m")->row_array();

            // get menu by default language
            $lang_default = $this->session->userdata('default_language');
            $lang_default = $lang_default['code'];
            $this->db->select("id, `name`, `link`, `parent_id`, `image`, `value`");
            $this->db->join("menu_description md", "md.menu_id = m.id");
            $this->db->where("m.id", $id);
            $this->db->where("lang_code", "$lang");
            $default = $this->db->get("menu m")->row_array();

            if(!empty($default)){
                $menu = replaceValueNull($current, $default);
            }
        }
        return $menu;
    }
  public function getMenuByLink($link=array()) {
	   
	   $sql ="select `id`, `link`, `parent_id` From menu Where `link` like '".$link[0]."' OR `link` like '".$link[1]."' LIMIT 1";
	   $menu = $this->db->query($sql)->row_array();
		return $menu;
	}
  
    
}