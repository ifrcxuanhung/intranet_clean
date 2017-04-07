<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
define('PATH', str_replace("\\", "/", FCPATH));
/* load the MX_Router class */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('welcome/Setting_model', 'setting_model');
        $this->registry->set('setting', $this->setting_model->get_group('setting'));
    }

}