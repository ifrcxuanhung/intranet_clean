<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends Welcome {
    function __construct() {
        parent::__construct();
    }
    
    function index() {

        $this->template->write_view('content', 'home/index', $this->data);
        $this->template->render();
    }

}