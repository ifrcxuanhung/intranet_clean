<?php 
class Help extends Welcome{
    public function __construct() {
        parent::__construct();
        $this->load->model('Table_model', 'table');
        $this->load->model('Layout_model', 'layout');
    }
    
    public function index() {
        
        $this->data->dataSubCat = $this->layout->loadSubcatbyCategory('HELP');
        $this->data->dataArt = $this->layout->loadArticleBySubCat('HELP');
        //print_R($dataArt);exit;
        $this->template->write_view('content', 'help/index', $this->data);
        $this->template->render();
    }
    
    function checkUpdate(){
        $sql = "Select * FROM int_summary;";
        $data = $this->db->query($sql)->result_array();
        $flat = 0;
        $rs = array();
        foreach($data as $key=>$value){
            $rss = $this->checkTable($value['tab'],'update');
            if($rss==false){
                $rs[] = $value['tab'];
                $this->db->query("ALTER TABLE `{$value['tab']}` ADD `update` DATETIME NOT NULL DEFAULT ON UPDATE CURRENT_TIMESTAMP");
            }      
        }
        return $rs;
    }
    function checkTable($table, $column)
    {   
        $result = $this->db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        $exists = ($result->num_rows())?TRUE:FALSE;
        return $exists;
    }
    
}
