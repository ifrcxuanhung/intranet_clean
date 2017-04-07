<?php
//require('_/modules/welcome/controllers/block.php');

class Profile extends Welcome{
    public function __construct() {
        parent::__construct();
		 $this->load->model('Profile_model', 'user');
    }
    
    public function index() {
    
		if(!isset($this->session->userdata['user_id'])){
			$url = explode("profile",$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$baseURL = $url[0];
			header("location:http://$baseURL");
			exit;
		}
		
		$detail_user = $this->user->get_detail_user_new($this->session->userdata('user_id'));
		
		foreach($detail_user as $k=>$dupma){
			$dupme['info'] = $dupma;
			$dupme[$dupma['label']] = $dupma['profile_value'];	
		}
		$this->data->detail_user = $dupme;
        $this->template->write_view('content', 'profile/index', $this->data);
        $this->template->render();
    }
    
   
    function upload_image() {
        
        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPG", "JPEG", "PNG");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);

        $respone['error'] = '';
        $respone['success'] = '';
        
        if ($_FILES["file"]["type"] == "image/gif"
        	|| $_FILES["file"]["type"] == "image/jpeg"
        	|| $_FILES["file"]["type"] == "image/jpg"
	        || $_FILES["file"]["type"] == "image/pjpeg"
	        || $_FILES["file"]["type"] == "image/x-png"
	        || $_FILES["file"]["type"] == "image/png"
	        && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                $respone['error'] = $_FILES["file"]["error"];
            } else {
            	$path = 'assets/upload/images/';
                $filename = strtolower($extension);
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $path.basename($filename))) {
                //	$this->db->where('id_user', $this->session->userdata('user_id'))
//                		->update('dghq_users', array('images' => $path.$filename));
                    $respone['success'] = $path.$filename;
                } else {
                    $respone['error'] = "Can not upload file";
                }
            }
        } else {
            $respone['error'] = "Invalid file";
        }
        $this->output->set_output(json_encode($respone));
    }
    
    function upload_avatar() {
        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPG", "JPEG", "PNG");
        $temp = explode(".", $_FILES["fileavatar"]["name"]);
        $extension = end($temp);

        $respone['error'] = '';
        $respone['success'] = '';
        
        if ($_FILES["fileavatar"]["type"] == "image/gif"
        	|| $_FILES["fileavatar"]["type"] == "image/jpeg"
        	|| $_FILES["fileavatar"]["type"] == "image/jpg"
	        || $_FILES["fileavatar"]["type"] == "image/pjpeg"
	        || $_FILES["fileavatar"]["type"] == "image/x-png"
	        || $_FILES["fileavatar"]["type"] == "image/png"
	        && in_array($extension, $allowedExts)) {
            if ($_FILES["fileavatar"]["error"] > 0) {
                $respone['error'] = $_FILES["fileavatar"]["error"];
            } else {
            	$path = 'assets/upload/avatar/';
                $filename = $this->session->userdata('user_id').'.'.strtolower($extension);
                if(move_uploaded_file($_FILES["fileavatar"]["tmp_name"], $path.basename($filename))) {
                	$this->db->where('user_id', $this->session->userdata('user_id'))
                		->update('login_users', array('avatar' => $path.$filename));
					//$files = scandir($path);
						/*echo "<pre>";print_r($files);exit;
						foreach($files as $k=>$file){
							if($k != 0 && $k != 1)
							$thumb = $this->resize_image('max',$path.basename($file),$path."thumb/".basename($file),100,100);
						}	*/
					$thumb = $this->resize_image('max',$path.basename($filename),$path."thumb/".basename($filename),100,100);
                    $respone['success'] = $path.$filename;
                } else {
                    $respone['error'] = "Can not upload file";
                }
            }
        } else {
            $respone['error'] = "Invalid file";
        }
        $this->output->set_output(json_encode($respone));
    }
    function view_user_home() {
        $sql = "select du.first_name, du.last_name, dui.profile, dui.education, dui.experiences, dui.interests
                from user_info dui, user du
                where dui.id_user = du.id
                and dui.id_user = '".$this->session->userdata('user_id')."';";
        $this->output->set_output(json_encode($this->db->query($sql)->row_array()));
    }
     function view_modal() {
		 $this->data->old_email = $this->db->where('id', $this->session->userdata('user_id'))->get('user')->row()->email;
        $this->data->detail_user = $this->user->get_detail_user($this->session->userdata('user_id'));
        
        //print_r($this->user->get_detail_user($this->session->userdata('user_id')));exit; 
        $this->load->view('profile/'.$this->input->post('modal_type'), $this->data);
    }
    function change_password() {
        $respone = 0;
        $old_password = real_escape_string($_POST['old_password']);
        $new_password = real_escape_string($_POST['new_password']);
		$query = $this->db->select('password, salt')
                ->where('id', $this->session->userdata('user_id'))
                ->limit(1)
                ->get('user');

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
             $respone = 0;
        }else{
        // sha1
			 $salt = substr($hash_password_db->password, 0, 10);
			 $db_password = $salt . substr(sha1($salt . $old_password), 0, -10);
			if($db_password == $hash_password_db->password) {
				if(!empty($new_password)){
					$salt_2 = substr(md5(uniqid(rand(), true)), 0, 10);
					$new_password_db = $salt_2 . substr(sha1($salt_2 . $new_password), 0, -10);
					$data = array(
						'password' => $new_password_db,
						'remember_code' => NULL,
					);
					$this->db->where('id', $this->session->userdata('user_id'))
						->update('user', $data);
					$respone = 1;
				}
			}
		}
        $this->output->set_output(json_encode($respone));
    }
    
    
    function change_user_info() {
        $respone = 0;
        $first_name = real_escape_string($_POST['first_name']);
        $last_name = real_escape_string($_POST['last_name']);
        $profile = real_escape_string($_POST['profile']);
        $education = real_escape_string($_POST['education']);
        $experiences = real_escape_string($_POST['experiences']);
        $interests = real_escape_string($_POST['interests']);
        
        $sql = "UPDATE user, user_info
                SET first_name = '{$first_name}',last_name = '{$last_name}',education = '{$education}',`profile` = '{$profile}',experiences = '{$experiences}',interests = '{$interests}'
                WHERE user.id=user_info.id_user
                AND user.id = '{$this->session->userdata('user_id')}';";
        $respone = $this->db->query($sql);
        //$this->db->where('id_user', )
        //->update('dghq_users_info', array('first_name' => $first_name, 'last_name' => $last_name, 'education' => $education,'profile' => $profile, 'experiences' => $experiences, 'interests' => $interests));
        //$respone = 1;
        //print_r($first_name.$last_name.$education);exit;
        $this->output->set_output(json_encode($respone));
    }

    function change_email() {
        $respone = 0;
        $old_email = real_escape_string($_POST['old_email']);
        $new_email = real_escape_string($_POST['new_email']);
        if($old_email == $this->db->where('id', $this->session->userdata('user_id'))->get('user')->row()->email) {
            $this->db->where('id', $this->session->userdata('user_id'))
                ->update('user', array('email' => $new_email));
				
			//update login_user
			 $this->db->where('user_id', $this->session->userdata('user_id'))
                ->update('login_users', array('email' => $new_email));
				
            $respone = 1;
        }
        $this->output->set_output(json_encode($respone));
    }

   // function view_user() {
//        $sql = "select dui.id_user, du.first_name, du.last_name, dui.from, du.birthdate, dui.prof_phone, dui.prof_mobile, du.email,
//                dui.addr_street, dui.addr_city, dui.addr_country
//                from {$this->user->table_dghq_users_info} dui, {$this->user->table_dghq_users} du
//                where dui.id_user = du.id_user
//                and dui.id_user = '".$this->input->post('id_user')."';";
//        $this->output->set_output(json_encode($this->db->query($sql)->row_array()));
//    }
//    
//	function view_user_home() {
//        $sql = "select du.first_name, du.last_name, dui.profile, dui.education, dui.experiences, dui.interests
//                from {$this->user->table_dghq_users_info} dui, {$this->user->table_dghq_users} du
//                where dui.id_user = du.id_user
//                and dui.id_user = '".$this->session->userdata('id_user')."';";
//        $this->output->set_output(json_encode($this->db->query($sql)->row_array()));
//    }

    function update_user() {
        $respone = 0;
        foreach ($this->input->post() as $key => $value) {
            switch ($key) {
                case 'from':
                case 'prof_phone':
                case 'prof_mobile':
                case 'addr_street':
                case 'addr_city':
                case 'addr_country':
                    $data_info[$key] = real_escape_string($value);
                    break;
                default:
                    $data[$key] = real_escape_string($value);
                    break;
            }
        }

        $this->db->trans_start();
        
        $this->db->where('id', $this->session->userdata('user_id'))
            ->update('user', $data);

        $this->db->where('id', $this->session->userdata('user_id'))
            ->update('user_info', $data_info);

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_complete();
            $respone = 1;
        }

        $this->output->set_output(json_encode($respone));
    }
	public function deleteImage(){
		$url = $_POST['attr'];
		$url_image = explode('assets',$url);
		$url_cut_image = "assets".$url_image[1]; 
		$data = array(
               'avatar' => ''
            );

		$this->db->where('avatar', $url_cut_image);
		$this->db->update('login_users', $data);
		$result = true;
		$this->output->set_output(json_encode($result));
	}
	
	function resize_image($method,$image_loc,$new_loc,$width,$height) {
		if (!is_array(@$GLOBALS['errors'])) { $GLOBALS['errors'] = array(); }
	 
		if (!in_array($method,array('force','max','crop'))) { $GLOBALS['errors'][] = 'Invalid method selected.'; }
	 
		if (!$image_loc) { $GLOBALS['errors'][] = 'No source image location specified.'; }
		else {
			if ((substr(strtolower($image_loc),0,7) == 'http://') || (substr(strtolower($image_loc),0,7) == 'https://')) { /*don't check to see if file exists since it's not local*/ }
			elseif (!file_exists($image_loc)) { $GLOBALS['errors'][] = 'Image source file does not exist.'; }
			$extension = strtolower(substr($image_loc,strrpos($image_loc,'.')));
			if (!in_array($extension,array('.jpg','.jpeg','.png','.gif','.bmp'))) { $GLOBALS['errors'][] = 'Invalid source file extension!'; }
		}
	 
		if (!$new_loc) { $GLOBALS['errors'][] = 'No destination image location specified.'; }
		else {
			$new_extension = strtolower(substr($new_loc,strrpos($new_loc,'.')));
			if (!in_array($new_extension,array('.jpg','.jpeg','.png','.gif','.bmp'))) { $GLOBALS['errors'][] = 'Invalid destination file extension!'; }
		}
	 
		$width = abs(intval($width));
		if (!$width) { $GLOBALS['errors'][] = 'No width specified!'; }
	 
		$height = abs(intval($height));
		if (!$height) { $GLOBALS['errors'][] = 'No height specified!'; }
	 
		if (count($GLOBALS['errors']) > 0) { $this->echo_errors(); return false; }
	 
		if (in_array($extension,array('.jpg','.jpeg'))) { $image = @imagecreatefromjpeg($image_loc); }
		elseif ($extension == '.png') { $image = @imagecreatefrompng($image_loc); }
		elseif ($extension == '.gif') { $image = @imagecreatefromgif($image_loc); }
		elseif ($extension == '.bmp') { $image = @imagecreatefromwbmp($image_loc); }
	 
		if (!$image) { $GLOBALS['errors'][] = 'Image could not be generated!'; }
		else {
			$current_width = imagesx($image);
			$current_height = imagesy($image);
			if ((!$current_width) || (!$current_height)) { $GLOBALS['errors'][] = 'Generated image has invalid dimensions!'; }
		}
		if (count($GLOBALS['errors']) > 0) { @imagedestroy($image); $this->echo_errors(); return false; }
	 
		if ($method == 'force') { $new_image = $this->resize_image_force($image,$width,$height); }
		elseif ($method == 'max') { $new_image = $this->resize_image_max($image,$width,$height); }
		elseif ($method == 'crop') { $new_image = $this->resize_image_crop($image,$width,$height); }
	 
		if ((!$new_image) && (count($GLOBALS['errors'] == 0))) { $GLOBALS['errors'][] = 'New image could not be generated!'; }
		if (count($GLOBALS['errors']) > 0) { @imagedestroy($image); $this->echo_errors(); return false; }
	 
		$save_error = false;
		if (in_array($extension,array('.jpg','.jpeg'))) { imagejpeg($new_image,$new_loc) or ($save_error = true); }
		elseif ($extension == '.png') { imagepng($new_image,$new_loc) or ($save_error = true); }
		elseif ($extension == '.gif') { imagegif($new_image,$new_loc) or ($save_error = true); }
		elseif ($extension == '.bmp') { imagewbmp($new_image,$new_loc) or ($save_error = true); }
		if ($save_error) { $GLOBALS['errors'][] = 'New image could not be saved!'; }
		if (count($GLOBALS['errors']) > 0) { @imagedestroy($image); @imagedestroy($new_image); $this->echo_errors(); return false; }
	 
		imagedestroy($image);
		imagedestroy($new_image);
	 
		return true;
	}
 
	function echo_errors() {
		if (!is_array(@$GLOBALS['errors'])) { $GLOBALS['errors'] = array('Unknown error!'); }
		foreach ($GLOBALS['errors'] as $error) { echo '<p style="color:red;font-weight:bold;">Error: '.$error.'</p>'; }
	}
	function resize_image_crop($image,$width,$height) {
		$w = @imagesx($image); //current width
		$h = @imagesy($image); //current height
		if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
		if (($w == $width) && ($h == $height)) { return $image; } //no resizing needed
	 
		//try max width first...
		$ratio = $width / $w;
		$new_w = $width;
		$new_h = $h * $ratio;
	 
		//if that created an image smaller than what we wanted, try the other way
		if ($new_h < $height) {
			$ratio = $height / $h;
			$new_h = $height;
			$new_w = $w * $ratio;
		}
	 
		$image2 = imagecreatetruecolor ($new_w, $new_h);
		imagecopyresampled($image2,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
	 
		//check to see if cropping needs to happen
		if (($new_h != $height) || ($new_w != $width)) {
			$image3 = imagecreatetruecolor ($width, $height);
			if ($new_h > $height) { //crop vertically
				$extra = $new_h - $height;
				$x = 0; //source x
				$y = round($extra / 2); //source y
				imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
			} else {
				$extra = $new_w - $width;
				$x = round($extra / 2); //source x
				$y = 0; //source y
				imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
			}
			imagedestroy($image2);
			return $image3;
		} else {
			return $image2;
		}
	}
	function resize_image_max($image,$max_width,$max_height) {
		$w = imagesx($image); //current width
		$h = imagesy($image); //current height
		if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
	 
		if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
	 
		//try max width first...
		$ratio = $max_width / $w;
		$new_w = $max_width;
		$new_h = $h * $ratio;
	 
		//if that didn't work
		if ($new_h > $max_height) {
			$ratio = $max_height / $h;
			$new_h = $max_height;
			$new_w = $w * $ratio;
		}
	 
		$new_image = imagecreatetruecolor ($new_w, $new_h);
		//$new_image = imagecreate($new_w, $new_h);
		imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		return $new_image;
	}
	function resize_image_force($image,$width,$height) {
		$w = @imagesx($image); //current width
		$h = @imagesy($image); //current height
		if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
		if (($w == $width) && ($h == $height)) { return $image; } //no resizing needed
	 
		$image2 = imagecreatetruecolor ($width, $height);
		imagecopyresampled($image2,$image, 0, 0, 0, 0, $width, $height, $w, $h);
	 
		return $image2;
	}
	
	

}
