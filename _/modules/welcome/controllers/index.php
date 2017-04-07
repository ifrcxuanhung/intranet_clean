<?php
require('_/modules/welcome/controllers/block.php');

class Index extends Welcome{
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->template->write_view('content', 'index', $this->data);
        $this->template->render();
    }
}
