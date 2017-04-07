<?php
class _404 extends Welcome{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($page=1)
    {
        $this->template->write_view('content', '_404', $this->data);
        $this->template->render();
    }
}