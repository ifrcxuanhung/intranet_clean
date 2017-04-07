<?php

class Start extends Welcome{
    public function __construct() {
        parent::__construct();
		$this->load->model('int_article_model', 'table');
    }
    
    public function index() {
		
		$this->data->starthomes = $this->table->getStartHome();
		//echo "<pre>";print_r($this->data->starthome);exit;
		$this->template->write_view('content', 'start', $this->data);
		$this->template->render();
    }
    
}