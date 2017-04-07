<?php

class Portfolio extends Welcome{
    public function __construct() {
        parent::__construct();
		$this->load->model('Portfolio_model', 'portfolio');
    }
    
    public function index($table ='') {
        $int_table = 'int_'.strtolower($table);
        $this->data->mdata = $this->portfolio->get_all_data($int_table);
        //echo "<pre>"; print_r($this->data->mdata);exit;  
        $this->template->write_view('content', 'portfolio/index', $this->data);
        $this->template->render();
    }
    
        
    
}