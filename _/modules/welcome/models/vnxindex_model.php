<?php
class Vnxindex_model extends CI_Model{
    protected $_lang;
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->_lang = $this->session->userdata('curent_language');
    }
	
	// Function To Fetch Selected Student Record
	public function show_article_clean_desc_id($data){
		
		$sql = "SELECT * FROM article_description_clean WHERE article_id = $data[0] AND website = '$data[1]' AND lang_code = '$data[2]'";
	
		$result = $this->db->query($sql)->row_array();
		return $result;
	}
	public function show_article_clean_id($id){
		
		$sql = "SELECT clean_artid FROM ifrc_articles WHERE id = $id ";
		$clean_artid = $this->db->query($sql)->row_array();
		if($clean_artid){
			$sql2 = "SELECT * FROM ifrc_articles WHERE clean_artid = $clean_artid[clean_artid]";
			$result = $this->db->query($sql2)->result_array();
			return $result;
		}
	}
	public function show_article_clean_id_intranet($id){
		
		$sql = "SELECT clean_artid FROM ifrc_articles WHERE id = $id ";
		$clean_artid = $this->db->query($sql)->row_array();
		if($clean_artid){
			$sql2 = "SELECT * FROM ifrc_articles WHERE clean_artid = $clean_artid[clean_artid]";
			$result = $this->db->query($sql2)->result_array();
			return $result;
		}
	}
	
	
	// Update Query For Selected Student
	public function update_article_desc_clean($info,$data){
		
		$param = explode(",",$info);
		$article_id = str_replace("'","",$param[0]);
		$website = str_replace("'","",$param[1]);
		$lang_code = str_replace("'","",$param[2]);
		$sql="UPDATE article_description_clean SET title = '$data[title]', description = '$data[description]', long_description = '$data[long_description]' WHERE article_id = $article_id AND website = '$website' AND lang_code = '$lang_code'";
		$this->db->query($sql);
		//echo "<pre>";print_r($data);exit;
		/*$this->db->update('article_description_clean', $data, array('article_id' => $article_id,'website'=>$website,'lang_code'=>$lang_code));*/
		echo 1;
	}
	
	public function getAllLangById($data){
		//echo "<pre>";print_r($data);exit;
		$sql1 = "SELECT lang_code FROM article_description_clean WHERE article_id = $data[0] AND website = '$data[1]'";
		$result1 = $this->db->query($sql1)->result_array();
		//echo "<pre>";print_r($result);exit;
		foreach($result1 as $val){
			$sql2 = "SELECT * FROM article_description_clean WHERE article_id = $data[0] AND website = '$data[1]'  AND lang_code = '$val[lang_code]' ";
			$result2[] = $this->db->query($sql2)->row_array();
		}
		$result = array("title"=>$result1,"content"=>$result2);
		
		return $result;
	}
	public function countLangById($id){
		$sql = "SELECT count(*) FROM ifrc_articles WHERE clean_artid = (SELECT clean_artid FROM ifrc_articles WHERE id = $id)";							$result = $this->db->query($sql)->row_array();
		return $result['count(*)'];
	}
	public function countLangById_intranet($id){
		$sql = "SELECT count(*) FROM ifrc_articles WHERE clean_artid = (SELECT clean_artid FROM ifrc_articles WHERE id = $id)";							$result = $this->db->query($sql)->row_array();
		return $result['count(*)'];
	}
	
	public function getCleanArtidById($id){
		$sql = "SELECT clean_artid FROM ifrc_articles WHERE id = $id";
		$result = $this->db->query($sql)->row_array();
		return $result['clean_artid'];
	}
	
	public function getCleanArtidById_intranet($id){
		$sql = "SELECT clean_artid FROM ifrc_articles WHERE id = $id";
		$result = $this->db->query($sql)->row_array();
		return $result['clean_artid'];
	}
	
	public function updateArticleVnxindex($data,$data_lang,$clean_artid,$lang){
		
	
		$title = str_replace("'","\'",$data_lang['title_'.$lang]);
		$description = str_replace("'","\'",$data_lang['description_'.$lang]);
		$long_description = str_replace("'","\'",$data_lang['long_description_'.$lang]);
		
		if(isset($data['images']) && isset($data['file'])){
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]', images = '$data[images]', file = '$data[file]', website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";
		}
		elseif(isset($data['file'])){
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]', file = '$data[file]', website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";	
		}
		elseif(isset($data['images'])){
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]', images = '$data[images]', website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";	
		}
		else{
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]',website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";	
		}
			
		
		$this->db->query($sql);	 	
	}
	
	public function updateArticleVnxindex_intranet($data,$data_lang,$clean_artid,$lang){
	
	
		$title = str_replace("'","\'",$data_lang['title_'.$lang]);
		$description = str_replace("'","\'",$data_lang['description_'.$lang]);
		$long_description = str_replace("'","\'",$data_lang['long_description_'.$lang]);
		
		if(isset($data['images']) && isset($data['file'])){
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]', images = '$data[images]', file = '$data[file]', website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";
		}
		elseif(isset($data['file'])){
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]', file = '$data[file]', website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";
		}
		elseif(isset($data['images'])){
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]', images = '$data[images]', website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";
		}
		else{
			$sql = "UPDATE ifrc_articles SET status = '$data[status]', clean_order = '$data[clean_order]', url = '$data[url]' ,website = '$data[website]', clean_cat = '$data[clean_cat]',clean_scat = '$data[clean_scat]', date_creation = '$data[date_creation]', date_update = '$data[date_update]', title = '$title', description = '$description', long_description = '$long_description', name_user = '$data[name_user]' WHERE clean_artid = $clean_artid AND lang_code = '$lang'";	
		}
		
		
		$this->db->query($sql);	 	
	}
	
	public function add_ifrc_articles($data,$id,$clean_artid,$lang_code,$common_lang){
			$title = str_replace("'","\'",$common_lang[0]['title_'.$lang_code]);
			$des = str_replace("'","\'",$common_lang[0]['description_'.$lang_code]);
			$long_des = str_replace("'","\'",$common_lang[0]['long_description_'.$lang_code]);
			
			$sql = "INSERT INTO ifrc_articles (status, clean_order, images, file, url, website, clean_cat, clean_scat, date_creation,date_update, id, clean_artid, lang_code, title, description, long_description, name_user)
VALUES ('".$data['status']."', '".$data['sort_order']."', '".$data['image']."', '".$data['file']."', '".$data['url']."', '".$data['website']."','".$data['clean_cat']."','".$data['clean_scat']."','".$data['date_creation']."' ,'".$data['date_update']."' ,".$id.",".$clean_artid.",'".$lang_code."','".$title."','".$des."','".$long_des."', '".$data['name_user']."');";
//echo "<pre>";print_r($sql);exit;
			$this->db->query($sql);
	}
	
	public function add_ifrc_articles_intranet($data,$id,$clean_artid,$lang_code,$common_lang){
		
			$title = str_replace("'","\'",$common_lang[0]['title_'.$lang_code]);
			$des = str_replace("'","\'",$common_lang[0]['description_'.$lang_code]);
			$long_des = str_replace("'","\'",$common_lang[0]['long_description_'.$lang_code]);
			
			$sql = "INSERT INTO ifrc_articles (status, clean_order, images, file, url, website, clean_cat, clean_scat, date_creation,date_update, id, clean_artid, lang_code, title, description, long_description, name_user)
VALUES ('".$data['status']."', '".$data['sort_order']."', '".$data['image']."', '".$data['file']."', '".$data['url']."', '".$data['website']."','".$data['clean_cat']."','".$data['clean_scat']."','".$data['date_creation']."' ,'".$data['date_update']."' ,".$id.",".$clean_artid.",'".$lang_code."','".$title."','".$des."','".$long_des."', '".$data['name_user']."');";
//echo "<pre>";print_r($sql);exit;
			$this->db->query($sql);
	}
	
	
	public function updateArticleDescClean($data,$condition){
		$id = explode(":",$condition);
		//echo "<pre>";print_r($data);exit;
		foreach($data as $key=>$val){
			$getLang = explode("_",$key);
			$title = $val[0]['title_'.$getLang[1]];
			$description = $val[0]['description_'.$getLang[1]];
			$long_description = $val[0]['long_description_'.$getLang[1]];
			$meta_keyword = $val[0]['meta_keyword_'.$getLang[1]];
			$meta_description = $val[0]['meta_description_'.$getLang[1]];
			$data_update = date("Y-m-d H:m:s",now());
			
			$sql = "UPDATE article_description_clean SET title = '$title', description = '$description', long_description = '$long_description', meta_keyword = '$meta_keyword', meta_description = '$meta_description',
			date_update = '$data_update' WHERE article_id = $id[0] AND website = '$id[1]' AND lang_code = '$getLang[1]'";
			$this->db->query($sql);
		}
	
		
			
	}
	public function addArticleDescClean($data,$param,$id){
		//echo "<pre>";print_r($data);exit;
		foreach($data as $key=>$val){
			$getLang = explode("_",$key);
			$title = $val[0]['title_'.$getLang[1]];
			$description = $val[0]['description_'.$getLang[1]];
			$long_description = $val[0]['long_description_'.$getLang[1]];
			$meta_keyword = $val[0]['meta_keyword_'.$getLang[1]];
			$meta_description = $val[0]['meta_description_'.$getLang[1]];
		
			$sql = "INSERT INTO article_description_clean (article_id,title,description, long_description, meta_keyword, meta_description,lang_code,website)
VALUES ($id, '$title', '$description', '$long_description','$meta_keyword', '$meta_description','$getLang[1]','$param[website]');";
			$this->db->query($sql);	
		}
	
		
			
	}
	
	public function getArticleFinal(){
		$sql = "SELECT id FROM ifrc_articles";
		$data = $this->db->query($sql)->result_array();
		$temp=0;
		foreach($data as $val){
			if($val['id'] >= $temp){
				$temp = $val['id']; 	
			}
		}
		
		return $temp+1;
	}
	
	public function getArticleFinal_intranet(){
		$sql = "SELECT id FROM ifrc_articles";
		$data = $this->db->query($sql)->result_array();
		$temp=0;
		foreach($data as $val){
			if($val['id'] >= $temp){
				$temp = $val['id']; 	
			}
		}
		
		return $temp+1;
	}
	
	public function getCleanartidFinal(){
		$sql = "SELECT clean_artid FROM ifrc_articles";
		$data = $this->db->query($sql)->result_array();
		$temp=0;
		foreach($data as $val){
			if($val['clean_artid'] >= $temp){
				$temp = $val['clean_artid']; 	
			}
		}
		
		return $temp+1;
	}
	public function getCleanartidFinal_intranet(){
		$sql = "SELECT clean_artid FROM ifrc_articles";
		$data = $this->db->query($sql)->result_array();
		$temp=0;
		foreach($data as $val){
			if($val['clean_artid'] >= $temp){
				$temp = $val['clean_artid']; 	
			}
		}
		
		return $temp+1;
	}
	
	public function list_website(){
		
		$sql = "SELECT DISTINCT website FROM ifrc_articles";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function get_int_article_website(){
		
		$sql = "SELECT * FROM int_article_websites";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	public function list_website_intranet(){
		
		$sql = "SELECT DISTINCT website FROM ifrc_articles";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function list_cat(){
		
		$sql = "SELECT DISTINCT clean_cat FROM ifrc_articles";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function list_cat_intranet(){
		
		$sql = "SELECT DISTINCT clean_cat FROM ifrc_articles";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function list_scat(){
		
		$sql = "SELECT DISTINCT clean_scat FROM ifrc_articles where clean_scat <> '' ";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	public function list_scat_intranet(){
		
		$sql = "SELECT DISTINCT clean_scat FROM ifrc_articles where clean_scat <> '' ";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
	}

}