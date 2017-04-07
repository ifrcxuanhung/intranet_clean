<?php

class Block extends MY_Controller {

    protected $data;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
    

   
}