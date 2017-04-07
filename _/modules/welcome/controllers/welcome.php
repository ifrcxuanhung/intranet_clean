<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends MY_Controller {

    protected $data;

    function __construct() {
        parent::__construct();
		
        $this->data = new stdClass();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->load->library('user_agent');
        $this->load->library('ion_auth');
        $this->load->library('session');
		$this->load->model('User_model', 'user_detail');
		
		/*if(!isset($_SESSION)){
			session_start();
		}
		if (isset($_SESSION['intranet_clean']['username'])) :
			$this->ion_auth->logout();
		endif;
		*/
		if(isset($_SESSION['intranet_clean']['user_id'])){
			$this->session->set_userdata('user_id', $_SESSION['intranet_clean']['user_id']);
			$this->data->avatar = $_SESSION['intranet_clean']['avatar'];
			$this->data->name = $_SESSION['intranet_clean']['name'];
		}
		//echo $this->data->avatar;exit;
        $this->data->config = $this->db->get('config')->row_array();
        $this->load->model('language_model', 'language');
        $where = array('status' => 1);
        $langList = $this->language->find(NULL, $where);
        if (is_array($langList) == TRUE && count($langList) > 0) {
            foreach ($langList as $value) {
                $this->data->list_language[$value['code']] = $value;
            }
            $this->data->default_language = $langList[0];
        }
        unset($langList);
		
        $this->session->set_userdata('default_language', $this->data->default_language);
        if (!$this->session->userdata('curent_language')) {
            $this->session->set_userdata('curent_language', $this->data->default_language);
			$file = file_get_contents(base_url().'assets/translate/translate.json');
        }
        $this->data->curent_language = $this->session->userdata('curent_language');
       
		//user info
		
		
		//user info
		if($this->session->userdata('user_id')) {
            $this->session->set_userdata('login',1);
			if (!$this->session->userdata('user_level')) {
				 $this->load->model('User_model', 'user_detail');
				 $detail = $this->user_detail->get_detail_user($this->session->userdata('user_id'));
				 $this->session->set_userdata('user_level', $detail['user_level']);
				
			}
		}
		else {
		      $this->session->set_userdata('login',0);
			 $this->session->set_userdata('user_level', 0);
		}
	
        $this->data->loginAuth = $this->session->userdata('login');
		//menu
        /*if (!$this->session->userdata('id_menu')) {
            $this->session->set_userdata('id_menu', '1');
        }*/
        $this->data->template_url = template_url();
        $this->data->setting = $this->registry->setting;
        $this->template->set_template('default');
        
        // load data sidebar
        $this->load->model('Menu_model', 'menu');
        $this->data->menu = $this->menu->getMenu();
		
		// menu active
		
		
		$get_url = explode("table",$_SERVER['REQUEST_URI']);
		/*$menu =$this->session->userdata('id_menu') ? $this->session->userdata('id_menu') :'' ;
		if(isset($get_url[1])){
			$link_menu = "table".$get_url[1];
			 $sql ="SELECT * FROM menu WHERE link = '$link_menu'";
			$get_menu = $this->db->query($sql)->row_array();
			$menu = isset($get_menu['id']) ? $get_menu['id']:$menu;
		
		}
		$this->data->id_menu_actived = $menu;
        */
       $id_menu = $this->menu->getMenuByLink(current_url_domain());
		$this->data->id_menu_actived = (isset($id_menu["parent_id"]) && $id_menu["parent_id"]!=0) ? $id_menu["parent_id"] :((isset($id_menu["id"])&& $id_menu["id"]!=134) ? $id_menu["id"] : 0);
		$this->session->set_userdata('id_menu', $this->data->id_menu_actived);
        
        $this->data->setting_ = $this->getSettings();

        $this->data->list_lang = $this->db->where('status', 1)
            ->order_by('sort_order', 'asc')
            ->get('language')->result_array();
		/*$this->data->user = $this->db->where('id', $this->session->userdata('user_id'))
                    ->join('user_info', 'user_info.id_user = user.id')
                    ->limit(1)
                    ->get('user')->row_array();
					
		/*$this->data->detail_user = $this->db->where('user_id', $this->session->userdata('user_id'))
                    ->limit(1)
                    ->get('login_users')->row_array();
         */
        $this->data->user_job = $this->db->where_in('userid', array($this->session->userdata('user_id'),0))->where('active', 1)
                                ->get('int_shortcuts')->result_array();
        // load media
        $this->load->model('Media_model', 'media');
        
        $this->template->write_view('header', 'header', $this->data);
        
        $this->template->write_view('footer', 'footer', $this->data);
    }

    public function index() {
        //redirect(base_url() . 'home');
		
    }

    public function active($langCode = '') {
        $ls = $this->language_model->find();
        foreach ($ls as $key => $value) {
            $this->data->list_language[$value['code']] = $value;
        }
        if (isset($this->data->list_language[$langCode]) == TRUE) {
            $this->session->set_userdata('curent_language', $this->data->list_language[$langCode]);
            $this->output->set_output(json_encode(array('result' => 1)));
        }
    }

    public function getSettings(){
        // load setting
        $array = array();
        $data = $this->db->where('active', 1)
        ->get('setting')->result_array();
        foreach($data as $value){
            $array[$value['key']] = $value['value'];
        }
        return $array;  
    }
    public function timer() {
        $this->output->set_output(date('H:i:s'));
    }
	private function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }
        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }
        return array_map( 'objectToArray', $object );
    }
    
    public function _thumb(&$image = NULL) {
        $thumb = 'assets/upload/images/no-image.jpg';
        if ($image == NULL) {
            $image = $thumb;
            return $thumb;
        }
        if (isset($image) == TRUE && $image != '') {
            image_thumb($image, 200, 150);
        }
        return $image;
    }
    
    public function userOnline(){
        $query = $this->db->where('user_id',$this->session->userdata('user_id'))->get('user_log');
        if ($query->num_rows() > 0){
            $this->db->where('user_id',$this->session->userdata('user_id'))->delete('user_log');
        }
        $data_user = array(
            'user_id' => $this->session->userdata('user_id'),
            'lastactive' => $this->session->userdata('lastActivity'),
            'status' => $this->session->userdata('login')
        );
        $this->db->insert('user_log', $data_user);
    }
    public function getListUserOnline(){
        $this->db->where('status', 1);
        $this->db->from('user_log');
        $this->db->join('user', 'user_log.user_id = user.id');
        $this->db->join('user_info', 'user_info.id_user = user.id');
        $this->db->where('user_log.user_id !=', $this->session->userdata('user_id'));
        $query = $this->db->get();
        return $query->result_array();
    } 
}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */