<?php
class Sitemap extends Welcome{
    public function __construct() {
        parent::__construct();
        
    }
    public function index()
    {  
         // load data sidebar
        $this->load->model('Menu_model', 'menu');
        $this->data->menu = $this->menu->getMenu();
        $this->template->write_view('content', 'sitemap', $this->data);
        $this->template->render();   
    }
}