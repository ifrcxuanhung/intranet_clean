<?php
class Article_clean_desc_model extends CI_Model{
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
	public function show_article_clean_id($data){
		
		$sql = "SELECT * FROM article_clean WHERE article_id = $data[0] AND website = '$data[1]'";
	
		$result = $this->db->query($sql)->row_array();
		return $result;
	}
	
	public function list_website(){
		
		$sql = "SELECT DISTINCT website FROM article_clean";
	
		$result = $this->db->query($sql)->result_array();
		return $result;
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
	public function countLangById($data){
		$sql = "SELECT count(*) FROM article_description_clean WHERE article_id = $data[0] AND website = '$data[1]'";
		$result = $this->db->query($sql)->row_array();
		return $result['count(*)'];
	}
	
	public function updateArticleClean($data,$condition){
		
			$id = explode(":",$condition);
			$this->db->where('article_id', $id[0]);
			$this->db->where('website', $id[1]);
			$this->db->update('article_clean', $data); 	
	}
	
	public function addArticleClean($data){
			
			$this->db->insert('article_clean', $data); 	
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
		$sql = "SELECT article_id FROM article_clean";
		$data = $this->db->query($sql)->result_array();
		$temp=0;
		foreach($data as $val){
			if($val['article_id'] >= $temp){
				$temp = $val['article_id']; 	
			}
		}
		
		return $temp+1;
	}
}