<?php
require('_/modules/welcome/controllers/block.php');

class About extends Welcome{
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $block = new Block;
        $this->load->model('article_model');
        $this->data->logo_partners = $block->logo_partners();
        $article_about = $this->article_model->getArticleByCodeCategory('about',0,20,'asc');
        $this->data->article_about = $article_about['current'];
        $this->template->write_view('content', 'about', $this->data);
        $this->template->render();
    }
}
