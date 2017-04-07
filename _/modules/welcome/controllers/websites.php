<?php
require('_/modules/welcome/controllers/block.php');

class Websites extends Welcome{
    public function __construct() {
        parent::__construct();
	
    }
    
    public function index() {
        $this->template->write_view('content', 'websites/index', $this->data);
        $this->template->render();
    }
}