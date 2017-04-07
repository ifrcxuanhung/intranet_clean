<?php
require('_/modules/welcome/controllers/block.php');

class Setting extends Welcome{
    public function __construct() {
        parent::__construct();
        $this->load->model('Article_model', 'article');
        $this->load->model('Market_maker_model', 'market');
		 $this->load->model('Tab_model', 'tab');
        $this->load->model('User_model', 'user');	
    
    }
    
    public function index() {
        $block = new Block;
        $this->data->calendar = $block->calendar();
        $this->data->logo_partners = $block->logo_partners();
        //$this->data->feed_chat = $block->feed_chat();
        $this->data->hightlights = $block->hightlights();  
        
		
		if(isset($_REQUEST['act'])&&($_REQUEST['act']=='exportCsv'||$_REQUEST['act']=='exportTxt')){
			$this->export();
		}
		else if(isset($_REQUEST['act'])&&($_REQUEST['act']=='exportXls')){
			$this->exportXls();
		}
		$tab_options = $this->tab->get_tab('vdm_underlying_setting');
        $headers_opt = $this->tab->get_headers($tab_options['table_name'],$tab_options['query']);
		 foreach ($headers_opt as $key => $value) {
            if($value['type_search'] == 1) {
                switch(strtolower($value['type'])) {
                    case 'varchar':
                    case 'longtext':
                    case 'int':
					case 'link':
                        $headers_opt[$key]['filter'] = $this->tab->append_input(strtolower($value['field']));
                        break;
                    case 'list':
					    $headers_opt[$key]['filter'] = $this->tab->append_select(strtolower($value['field']),(($tab_options['query']!='') && (!is_null($tab_options['query'])))?$tab_options['query']:$tab_options['table_name']);
                        break;
                    case 'date':
                        $headers_opt[$key]['filter'] = $this->tab->append_date(strtolower($value['field']));
                        break;
                    default:
                        $headers_opt[$key]['filter'] = '';
                }
            } else {
                $headers_opt[$key]['filter'] = '';
            }
        }
        $this->data->headers_opt = $headers_opt;
        
//        // get headers
		$tab_future = $this->tab->get_tab('vdm_contracts_setting_exc');
        $headers_fut = $this->tab->get_headers($tab_future['table_name'],$tab_future['query']);
        foreach ($headers_fut as $key => $value) {
            if($value['type_search'] == 1) {
                switch(strtolower($value['type'])) {
                    case 'varchar':
                    case 'longtext':
                    case 'int':
					case 'link':
                        $headers_fut[$key]['filter'] = $this->tab->append_input(strtolower($value['field']));
                        break;
                    case 'list':
                        $headers_fut[$key]['filter'] = $this->tab->append_select(strtolower($value['field']),(($tab_future['query']!='') && (!is_null($tab_future['query'])))?$tab_future['query']:$tab_future['table_name']);
                        break;
                    case 'date':
                        $headers_fut[$key]['filter'] = $this->tab->append_date(strtolower($value['field']));
                        break;
                    default:
                        $headers_fut[$key]['filter'] = '';
                }
            } else {
                $headers_fut[$key]['filter'] = '';
            }
        }
        $this->data->headers_fut = $headers_fut;
        $this->template->write_view('content', 'setting', $this->data);
        $this->template->render();
    }

    public function hose() {
        $block = new Block;
        $this->data->calendar = $block->calendar();
        $this->data->logo_partners = $block->logo_partners();
        $this->data->feed_chat = $block->feed_chat();
        $this->data->hightlights = $block->hightlights();
        $this->data->article_index = $this->article->getArticleShowIndex();
        $this->data->setting_content = $this->market->get_market_setting(0);

        $this->template->write_view('content', 'hose', $this->data);
        $this->template->render();
    }
    
}