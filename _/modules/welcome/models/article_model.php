<?php
class Article_model extends CI_Model{
    protected $_lang;
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->_lang = $this->session->userdata('curent_language');
    }
    public $table = 'article';
    public $article_description = 'article_description';
    public $article_option = 'article_option';
    public function getNewArticleIndex()
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $sql = "SELECT a.article_id, title, description, category_id, image, viewed, date_added, date_modified, poster_id, user_id, editer_id, url ";
        $sql.= "FROM article a, article_description ad ";
        $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND ";
        $sql.= "a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) AND lang_code = '$lang' ";
        $sql.= "ORDER BY a.article_id DESC LIMIT 0,9";
		
        $data = $this->db->query($sql)->result_array();
        
        for($i=0; $i<count($data); $i++)
        {
            $id_art = $data[$i]['article_id'];
            if($data[$i]['title'] == "")
            {
                $lang_default =  $this->config->item('lang_default');
                $art = $this->getArticleByIdDefaultLang($id_art, $lang_default);
                $data[$i] = $art[0];
            }
        }
        return $data;
    }
    
    /** SETTING **/ 
    
    public function listCode($code) {
        if($code == '')
        {
            $sql = "SELECT * FROM index_setting GROUP BY code;";
        }else{
            $sql = "SELECT * FROM index_setting WHERE code = '{$code}' GROUP BY code;";
        }
        return $this->db->query($sql)->result_array();
    }
    public function listData(){
        $sql = "SELECT * FROM data_setting;";
        return $this->db->query($sql)->result_array();
    }
    
    
    
  

    /** /SETTING **/ 
    
    public function getArticleByIdDefaultLang($art_id, $l="")
    {
        $lang = $l;

        $sql = "SELECT a.article_id, ad.title, ad.description, a.category_id, cd.name, cd.description AS description_cat, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url "; 
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND a.category_id = cd.category_id AND ad.lang_code = cd.lang_code AND ad.lang_code = '$lang' AND a.article_id = '$art_id'";
        $data = $this->db->query($sql)->result_array();
		
        return $data;
    }

    
    
    
    public function getArticleNew($page)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $art_per_page =  $this->config->item('article_per_page');
        $start = ($page-1)*$art_per_page;

        $sql = "SELECT a.article_id, ad.title, ad.description, a.category_id, cd.name, cd.description AS description_cat, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url ";
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND a.category_id = cd.category_id AND cd.lang_code = ad.lang_code AND `status` = '1' AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) AND ad.lang_code = '$lang' ";
        $sql.= "ORDER BY a.article_id DESC LIMIT $start,$art_per_page ";
        $data = $this->db->query($sql)->result_array();
        
        for($i=0; $i<count($data); $i++)
        {
            $id_art = $data[$i]['article_id'];
            if($data[$i]['title'] == "")
            {
                $lang_default =  $this->config->item('lang_default');
                $art = $this->getArticleByIdDefaultLang($id_art, $lang_default);
                $data[$i] = $art[0];
            }
        }
        return $data;
    }
    
    public function getNumPageAllArticle()
    {
        $art_per_page =  $this->config->item('article_per_page');
        $sql = "SELECT COUNT(*) AS num_row ";
        $sql.= "FROM article a ";
        $sql.= "WHERE `status` = '1' ";
        $sql.= "AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6)";
        $data = $this->db->query($sql)->result_array();
        $num_row = $data[0]['num_row'];
        $num_page = ceil($num_row/$art_per_page);
        $num_page = max(1,$num_page);
        return $num_page;
    }
    
    public function getArticleByCat($cate_id, $page)
    {
        
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $art_per_page =  $this->config->item('article_per_page');
        $start = ($page-1)*$art_per_page;
        
        $sql = "SELECT a.article_id, ad.title, ad.description, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url , cd.name AS name_cat ";
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND ad.lang_code = cd.lang_code AND a.category_id = cd.category_id AND `status` = '1' ";
        $sql.= "AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) AND a.category_id = '$cate_id' AND ad.lang_code = '$lang' ";
        $sql.= "ORDER BY a.article_id DESC LIMIT $start,$art_per_page";
        $data = $this->db->query($sql)->result_array();
        for($i=0; $i<count($data); $i++)
        {
            $id_art = $data[$i]['article_id'];
            if($data[$i]['title'] == "")
            {
                $lang_default =  $this->config->item('lang_default');
                $art = $this->getArticleByIdDefaultLang($id_art, $lang_default);
                $data[$i] = $art[0];
            }
        }
        return $data;
    }
    
    public function getNumPageArticleByCat($cate_id)
    {
        $art_per_page =  $this->config->item('article_per_page');
        $sql = "SELECT COUNT(*) AS num_row ";
        $sql.= "FROM article a ";
        $sql.= "WHERE `status` = '1' ";
        $sql.= "AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) AND a.category_id = '$cate_id'";
        $data = $this->db->query($sql)->result_array();
        $num_row = $data[0]['num_row'];
        $num_page = ceil($num_row/$art_per_page);
        $num_page = max(1,$num_page);
        return $num_page;
    }
    
    public function getBreakingNews()
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $sql = "SELECT a.article_id, ad.title, a.category_id ";
        $sql.= "FROM article a, article_description ad ";
        $sql.= "WHERE a.article_id = ad.article_id AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) AND a.status = 1 AND ad.lang_code = '$lang' ";
        $sql.= "ORDER BY a.article_id DESC LIMIT 0,10 ";
        $data = $this->db->query($sql)->result_array();
        for($i=0; $i<count($data); $i++)
        {
            $id_art = $data[$i]['article_id'];
            if($data[$i]['title'] == "")
            {
                $lang_default =  $this->config->item('lang_default');
                $art = $this->getArticleByIdDefaultLang($id_art, $lang_default);
                $data[$i] = $art[0];
            }
        }
        return $data;
    }
    
    public function changeView($art_id)
    {
        $sql = "UPDATE article SET viewed = (viewed+1) WHERE article_id = $art_id";
        $this->db->query($sql);
        return;
    }
    
    public function getMostViewed()
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $sql = "SELECT a.article_id, ad.title, a.category_id, a.image ";
        $sql.= "FROM article a, article_description ad ";
        $sql.= "WHERE a.article_id = ad.article_id AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) AND a.status = 1 AND ad.lang_code = '$lang' ";
        $sql.= "ORDER BY a.viewed DESC LIMIT 0,16 ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getContact($art_id)
    {        
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $sql = "SELECT a.article_id, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, a.category_id, cd.name, cd.description AS description_cat, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url "; 
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND a.category_id = cd.category_id AND ad.lang_code = cd.lang_code AND ad.lang_code = '$lang' AND a.article_id = '$art_id' LIMIT 0,1";
        $data = $this->db->query($sql)->result_array();
        
        if($data[0]['title'] == "")
        {
            $lang_default = $this->session->userdata('default_language');
            $lang_default = $lang_default['code'];
            $sql = "SELECT a.article_id, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, a.category_id, cd.name, cd.description AS description_cat, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url "; 
            $sql.= "FROM article a, article_description ad, category_description cd ";
            $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND a.category_id = cd.category_id AND ad.lang_code = cd.lang_code AND ad.lang_code = '$lang_default' AND a.article_id = '$art_id' LIMIT 0,1";
            $data = $this->db->query($sql)->result_array();
        }
        
        return $data;
    }
    
    
    
    public function searchLike($array_keyword, $page)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        $art_per_page =  $this->config->item('article_per_page');
        $start = ($page-1)*$art_per_page;
        
        $sql = "SELECT a.article_id, a.category_id, a.image, ad.title, ad.description ";
        $sql.= "FROM article a, article_description ad ";
        $sql.= "WHERE a.article_id = ad.article_id ";
        $sql.= "AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) ";
        $sql.= "AND a.status = 1 AND lang_code = '$lang' AND ";
        
        $str_search = "";
        for($i=0; $i<count($array_keyword); $i++)
        {
            if($i == 0)
                $str_search .= "ad.title LIKE '%". $array_keyword[$i] ."%'";
            else
                $str_search .= " OR ad.title LIKE '%". $array_keyword[$i] ."%'";
        }

        $sql .= $str_search . " LIMIT $start,$art_per_page";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    public function getNumPageSearch($array_keyword)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];
        
        $sql = "SELECT count(*) as count_sum ";
        $sql.= "FROM article a, article_description ad ";
        $sql.= "WHERE a.article_id = ad.article_id ";
        $sql.= "AND a.category_id IN (SELECT c.category_id FROM category c WHERE c.parent_id = 6) ";
        $sql.= "AND a.status = 1 AND lang_code = '$lang' AND ";
        
        $str_search = "";
        for($i=0; $i<count($array_keyword); $i++)
        {
            if($i == 0)
                $str_search .= "ad.title LIKE '%". $array_keyword[$i] ."%'";
            else
                $str_search .= " OR ad.title LIKE '%". $array_keyword[$i] ."%'";
        }

        $sql .= $str_search;
        $data = $this->db->query($sql)->result_array();
        $count_sum = $data[0]['count_sum'];
        $art_per_page =  $this->config->item('article_per_page');
        $num_page = ceil($count_sum/$art_per_page);
        $num_page = max(1,$num_page);
        $result['num_page'] = $num_page;
        $result['count_sum'] = $count_sum;
        return $result;
    }
    
    public function getArticleByCodeCategory($code_cat, $start = 0, $end = 0, $sort = "")
    {   
        $sort = ($sort == "") ? 'DESC' : 'ASC';
        if($end == 0) $end = $this->config->item('article_per_page');
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '{$this->_lang['code']}' AND a.status = 1 AND c.category_code = '$code_cat' AND a.sort_order>0
                ORDER BY a.sort_order $sort 
                LIMIT $start,$end";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '".LANG_DEFAULT."' AND a.status = 1 AND c.category_code = '$code_cat'  AND a.sort_order>0
                ORDER BY a.sort_order $sort 
                LIMIT $start,$end";
        $data['default'] = $this->db->query($sql)->result_array();
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
     public function getHighlightByCodeCategory($code_cat, $start = 0, $end = 0, $sort = "")
    {   
        $sort = ($sort == "") ? 'DESC' : 'ASC';
        if($end == 0) $end = $this->config->item('article_per_page');
        
        $sql = "SELECT *
                FROM highlights a 
                WHERE a.lang_code = '{$this->_lang['code']}' AND a.status = 1 AND a.category_code = '$code_cat' 
                ORDER BY a.sort_order $sort 
                LIMIT $start,$end";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT *
                FROM highlights a
                WHERE a.lang_code = '".LANG_DEFAULT."' AND a.status = 1 AND a.category_code = '$code_cat'  
                ORDER BY a.sort_order $sort 
                LIMIT $start,$end";
        $data['default'] = $this->db->query($sql)->result_array();
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    public function getArticleByCodeCategorySort($code_cat, $start = 0, $end = 0, $sort_by = "", $sort = "")
    {   
        //$sort = ($sort == "") ? 'DESC' : 'ASC';
        $sort_by  = ($sort_by == "title") ? 'ad.title' : 'a.'.$sort_by;
        if($end == 0) $end = $this->config->item('article_per_page');
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '{$this->_lang['code']}' AND a.status = 1 AND c.category_code = '$code_cat' AND a.sort_order>0
                ORDER BY $sort_by $sort 
                LIMIT $start,$end";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '".LANG_DEFAULT."' AND a.status = 1 AND c.category_code = '$code_cat' AND a.sort_order>0
                ORDER BY $sort_by $sort 
                LIMIT $start,$end";
        $data['default'] = $this->db->query($sql)->result_array();
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    
     public function getArticleByCodeCategoryOrderByDate($code_cat, $start = 0, $end = 0, $sort = "")
    {   
        $sort = ($sort == "") ? 'DESC' : 'ASC';
        if($end == 0) $end = $this->config->item('article_per_page');
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '{$this->_lang['code']}' AND a.status = 1 AND c.category_code = '$code_cat' 
                ORDER BY a.date_added $sort 
                LIMIT $start,$end";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '".LANG_DEFAULT."' AND a.status = 1 AND c.category_code = '$code_cat' 
                ORDER BY a.date_added $sort 
                LIMIT $start,$end";
        $data['default'] = $this->db->query($sql)->result_array();
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
	public function getHighlightOrderByDate($code_cat, $start = 0, $end = 0, $sort = "")
    {   
        $sort = ($sort == "") ? 'DESC' : 'ASC';
        if($end == 0) $end = $this->config->item('article_per_page');
        
        $sql = "SELECT *
                FROM highlights a
                WHERE a.lang_code = '{$this->_lang['code']}' AND a.status = 1 AND a.category_code = '$code_cat' 
                ORDER BY a.date_added $sort 
                LIMIT $start,$end";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT *
                FROM highlights a
                WHERE a.lang_code = '".LANG_DEFAULT."' AND a.status = 1 AND a.category_code = '$code_cat' 
                ORDER BY a.date_added $sort 
                LIMIT $start,$end";
        $data['default'] = $this->db->query($sql)->result_array();
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    
    public function getArticleShowIndex()
    {   
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '{$this->_lang['code']}' AND a.status = 1 AND a.show_index = 1
                ORDER BY a.sort_order DESC LIMIT 0,1";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT a.article_id, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.url, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, c.category_code 
                FROM article a, article_description ad, category c 
                WHERE a.article_id = ad.article_id AND a.category_id = c.category_id AND ad.lang_code = '".LANG_DEFAULT."' AND a.status = 1 AND a.show_index = 1
                ORDER BY a.sort_order DESC LIMIT 0,1";
        $data['default'] = $this->db->query($sql)->result_array();
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    
    public function getArticleBtTableById($art_id, $bttable)
    {

        $sql = "SELECT * "; 
        $sql.= "FROM {$bttable} ";
        $sql.= "WHERE `status` = '1' AND lang_code = '{$this->_lang['code']}' AND article_id = '$art_id'";
        $data['current'] = $this->db->query($sql)->result_array();
        
        
        $sql = "SELECT * "; 
        $sql.= "FROM {$bttable} ";
        $sql.= "WHERE `status` = '1' AND lang_code = '".LANG_DEFAULT."' AND article_id = '$art_id'";
        $data['default'] = $this->db->query($sql)->result_array();
        
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    
    public function getArticleRelativeByTable($bttable, $art_id)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];

        $lang_default = $this->session->userdata('default_language');
        $lang_default = $lang_default['code'];

        $sql = "SELECT * 
        FROM {$bttable} 
        WHERE `status` = '1' 
		AND lang_code = '{$lang}' AND article_id != '$art_id' 
        ORDER BY article_id desc LIMIT 0,10";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT * 
        FROM {$bttable} 
        WHERE `status` = '1' 
		AND lang_code = '{$lang_default}' AND article_id != '$art_id' 
        ORDER BY article_id desc LIMIT 0,10";
        $data['default'] = $this->db->query($sql)->result_array();
        
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    
    public function getArticleById($art_id)
    {

        $sql = "SELECT a.article_id, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, a.category_id, cd.name, cd.description AS description_cat, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url "; 
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND a.category_id = cd.category_id AND ad.lang_code = cd.lang_code AND ad.lang_code = '{$this->_lang['code']}' AND a.article_id = '$art_id'";
        $data['current'] = $this->db->query($sql)->result_array();
        
        
        $sql = "SELECT a.article_id, ad.title, ad.description, ad.long_description, ad.meta_description, ad.meta_keyword, a.category_id, cd.name, cd.description AS description_cat, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url "; 
        $sql.= "FROM article a, article_description ad, category_description cd ";
        $sql.= "WHERE a.article_id = ad.article_id AND `status` = '1' AND a.category_id = cd.category_id AND ad.lang_code = cd.lang_code AND ad.lang_code = '".LANG_DEFAULT."' AND a.article_id = '$art_id'";
        $data['default'] = $this->db->query($sql)->result_array();
        
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }


    public function getArticleRelative($cate_id, $art_id)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];

        $lang_default = $this->session->userdata('default_language');
        $lang_default = $lang_default['code'];

        $sql = "SELECT a.article_id, ad.title, ad.description, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url , cd.name AS name_cat 
        FROM article a, article_description ad, category_description cd 
        WHERE a.article_id = ad.article_id AND ad.lang_code = cd.lang_code AND a.category_id = cd.category_id AND `status` = '1' 
		AND a.category_id = '$cate_id' AND ad.lang_code = '{$lang}' AND a.article_id != '$art_id' 
        ORDER BY a.article_id desc LIMIT 0,10";
        $data['current'] = $this->db->query($sql)->result_array();
        
        $sql = "SELECT a.article_id, ad.title, ad.description, a.category_id, a.image, a.viewed, a.date_added, a.date_modified, a.poster_id, a.user_id, a.editer_id, a.url , cd.name AS name_cat 
        FROM article a, article_description ad, category_description cd 
        WHERE a.article_id = ad.article_id AND ad.lang_code = cd.lang_code AND a.category_id = cd.category_id AND `status` = '1' 
        AND a.category_id = '$cate_id' AND ad.lang_code = '{$lang_default}' AND a.article_id != '$art_id' 
        ORDER BY a.article_id desc LIMIT 0,10";
        $data['default'] = $this->db->query($sql)->result_array();
        
        if(!empty($data['default'])){
            $data['current'] = replaceValueNull($data['current'], $data['default']);
        }
        $data['current'] = @$data['current'];
        $data['default'] = @$data['default'];
        return $data;
    }
    
    
     public function list_article_cate($codeCate, $limit = null)
    {
        $lang = $this->session->userdata('curent_language');
        $lang = $lang['code'];

        $lang_default = $this->session->userdata('default_language');
        $lang_default = $lang_default['code'];
        $data = '';
        if ($limit)
        {
            $limit = "LIMIT $limit";
        }

        $sql = "select category_id from category where category_code = '$codeCate'";
        $rows = $this->db->query($sql)->result_array();
        //print_r($rows);exit;
        if (!empty($rows))
        {
            $listcodeCate = $this->ds_code_cate_news($rows[0]['category_id']);
            $cate = $rows[0]['category_id'];
            foreach ($listcodeCate as $key => $value)
            {
                $cate .= "," . $value['category_id'];
            }
            $sql = 'SELECT d.article_id,d.title,d.description,d.long_description,n.date_added,n.article_id as newsid,n.category_id,n.image,n.date_added,n.url, REPLACE(n.url,"http://","") as url1, c.name as catename,
                    (SELECT category.category_code FROM category WHERE category.category_id = n.category_id LIMIT 1) category_code, d.meta_keyword
                    FROM article n, article_description d, category_description c
                    WHERE 
                        n.category_id in (' . $cate . ') 
                    AND 
                        n.article_id = d.article_id 
                    AND 
                        n.status = 1 
                    AND 
                        d.lang_code = "' . $lang . '"
                    AND 
                        c.lang_code = "' . $lang . '" 
                    AND 
                        c.category_id = n.category_id 
                    ORDER BY d.title ASC ' . $limit;
            $sqlDF = 'SELECT d.article_id,d.title,d.description,d.long_description,n.date_added,n.article_id as newsid,n.category_id,n.image,n.date_added,n.url, REPLACE(n.url,"http://","") as url1,c.name as catename,
                    (SELECT category.category_code FROM category WHERE category.category_id = n.category_id LIMIT 1) category_code, d.meta_keyword
                    FROM article n, article_description d, category_description c
                    WHERE 
                        n.category_id in (' . $cate . ') 
                    AND 
                        n.article_id = d.article_id 
                    AND 
                        n.status = 1 
                    AND 
                        d.lang_code = "' . $lang_default . '"
                    AND 
                        c.lang_code = "' . $lang_default . '" 
                    AND 
                        c.category_id = n.category_id 
                    ORDER BY d.title ASC ' . $limit;
            $data['curent'] = $this->db->query($sql)->result_array();
            $data['default'] = $this->db->query($sqlDF)->result_array();
            if ($data['curent'] || empty($data['curent']))
            {
                $data['curent'] = replaceValueNull($data['curent'], $data['default']);
            }
        }
        return $data;
    }
    
    public function ds_code_cate_news($parent_id = '', $data = NULL)
    {
            $lang = $this->session->userdata('curent_language');
            $lang = $lang['code'];
        
            $lang_default = $this->session->userdata('default_language');
            $lang_default = $lang_default['code'];
            
            if (!$data)
                $data = array();
    
            $sql = "select * from category where parent_id = '$parent_id' order by sort_order ASC";
            $row = $this->db->query($sql)->result_array();
    
            if (count($row) > 0)
                foreach ($row as $key => $value)
                {
      
                    $sql = "select `name` from category_description where category_id = '{$value['category_id']}' and lang_code = '{$lang}'";
                    $name = $this->db->query($sql)->result_array();
    
                    $data[] = array('category_id' => $value['category_id'], 'name' => $name[0]['name']);
                    $data = $this->ds_code_cate_news($value['category_id'], $data);
                }
            return $data;
        }
        
         public function getArticle_description($art_id = '')
         {
            $lang = $this->session->userdata('curent_language');
            $lang = $lang['code'];
            $lang_default = $this->session->userdata('default_language');
            $lang_default = $lang_default['code'];
            
            $sql = "SELECT m.article_id, md.title, m.category_id, m.image, m.url as link, md.description"; 
            $sql.= " FROM article m, article_description md";
            $sql.= " WHERE m.article_id = md.article_id AND m.`status` = 1 AND md.lang_code = '{$lang}' AND md.article_id = '{$art_id}' LIMIT 0,1";
            $data = $this->db->query($sql)->result_array();
                return $data;
         }
		 public function getGlossary_description($art_id = '')
		 {
			$lang = $this->session->userdata('curent_language');
			$lang = $lang['code'];
			$lang_default = $this->session->userdata('default_language');
			$lang_default = $lang_default['code'];
			
			$sql = "SELECT * "; 
			$sql.= " FROM glossary";
			$sql.= " WHERE lang_code = '{$lang}' AND article_id = '{$art_id}' LIMIT 0,1";
			$data = $this->db->query($sql)->result_array();
				return $data;
		 }
         
        function detect_sort_order() {
            $sql = "SELECT MAX(sort_order) AS sort_order
                    FROM {$this->table}
                    LIMIT 1;";
            $data = $this->db->query($sql)->result_array();
            return isset($data[0]['sort_order']) ? $data[0]['sort_order'] + 1 : 0;
        }
        
         /*     * ************************************************************** */
    /*    Name : get_one                                                */
    /* --------------------------------------------------------------- */
    /*    Description  :  l?y ra thông tin ?ticle và article décription      */
    /*                    theo ID c?a article n?u $lang_code = NULL        */
    /*                   thì s? tr? v? all décription language        */
    /*                   n?u $status == NULL thì l?y t?t c?          */
    /* --------------------------------------------------------------- */
    /*    Params  :  $id   $lang_code  $active                                         */
    /* --------------------------------------------------------------- */
    /*    Return  :  array                                               */
    /* --------------------------------------------------------------- */
    /*    Warning :                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    function get_one($id = null, $lang_code = NULL, $status = NULL, $table = NULL) {
        if ($table == NULL) {
            $table = $this->article_description;
            $query = $this->db->get_where($this->table, array('article_id' => $id));
            $data = $query->result_array();
        }
        if ($id == FALSE):
            return FALSE;
        endif;
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        // get description
        
        $this->db->where('article_id', $id);
        if ($lang_code != NULL) {
            $this->db->where('lang_code', $lang_code);
        }
        $query_des = $this->db->get($table);
        $data['article_description'] = $query_des->result_array();
        
        return $data;
    }

    /*     * ************************************************************** */
    /*    Name : get_one                                                */
    /* --------------------------------------------------------------- */
    /*    Description  :  get detail article with para $id      */
    /* --------------------------------------------------------------- */
    /*    Params  :  $id   $lang_code  $active                                         */
    /* --------------------------------------------------------------- */
    /*    Return  :  array                                               */
    /* --------------------------------------------------------------- */
    /*    Warning :                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.14 (TuanAnh)                            */
    /*     * ************************************************************** */

    function getArticleDetail($id) {
        $this->db->select('*');
        $this->db->from($this->article_description);
        $this->db->where('article_id', $id);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where('lang_code', $lang_code);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $item) {
                $data[] = $item;
            }
            return $data;
        }
    }

    function getArticleCategory($id) {
        $sql = "SELECT article.category_id
                FROM article
                WHERE article_id = '{$id}'
                LIMIT 1;";
        return $this->db->query($sql)->result_array();
    }

    /**     * ************************************************************* */
    /*    Name : _check_exist                                                */
    /* --------------------------------------------------------------- */
    /*    Description  : check article exist                              */
    /* --------------------------------------------------------------- */
    /*    Params  :  $id, $lang_code                                   */
    /* --------------------------------------------------------------- */
    /*    Return  :  true if exist or false if not                    */
    /* --------------------------------------------------------------- */
    /*    Warning :                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    public function _check_exist($id = NULL, $lang_code = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('article_id', $id);
        $this->db->where('lang_code', $lang_code);
        $query = $this->db->get('_tempt');
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }
  
    

    function add($arrArticle, $list_language = NULL, $table = NULL) {
        
        $this->db->trans_begin();
        
        if($table==NULL){
                if($arrArticle['article']['show_index'] == 1)
            {
                $data = array(
                   'show_index' => 0
                );
                $this->db->update($this->table, $data); 
            }
            $this->db->insert($this->table, $arrArticle['article']);
            $articleid = $this->db->insert_id();
            foreach ($list_language as $value) {
                $arrArticle['articledes'][$value['code']]['article_id'] = $articleid;
                $this->db->insert($this->article_description, $arrArticle['articledes'][$value['code']]);
            }
        }else{
            foreach ($list_language as $value) {
                $arrArticle['articledes'][$value['code']]['article_id'] = $articleid;
                $this->db->insert($table, $arrArticle['articledes'][$value['code']]);
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    function edit($arrArticle, $id, $list_language, $table) {
        
        $this->db->trans_begin();
        if($table ==null){
            if($arrArticle['article']['show_index'] == 1)
            {
                $data = array(
                   'show_index' => 0
                );
                $this->db->update($this->table, $data); 
            }
            $this->db->update($this->table, $arrArticle['article'], array('article_id' => $id));
            foreach ($list_language as $value) {
                if (self::_check_exist($id, $value['code']) == FALSE) {
                    $arrArticle['articledes'][$value['code']]['article_id'] = $id;
                    $this->db->insert($this->article_description, $arrArticle['articledes'][$value['code']]);
                } else {
                    $this->db->update($this->article_description, $arrArticle['articledes'][$value['code']], array('article_id' => $id, 'lang_code' => $value['code']));
                }
            }
        }else{
            foreach ($list_language as $value) {
                if (self::_check_exist($id, $value['code']) == FALSE) {
                    $arrArticle['articledes'][$value['code']]['article_id'] = $id;
                    $this->db->insert($table, $arrArticle['articledes'][$value['code']]);
                } else {
                    $this->db->update($table, $arrArticle['articledes'][$value['code']], array('article_id' => $id, 'lang_code' => $value['code']));
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }
	

}














