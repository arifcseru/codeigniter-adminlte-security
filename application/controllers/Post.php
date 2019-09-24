<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

class Post extends BaseController {

	public function __construct(){
		parent::__construct();
		$this->load->library('javascript');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('session');
		$this->load->model('user_model');
		$this->isLoggedIn(); 
	}
	public function index(){
		 $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
	}
	public function create(){
		$isValid = false;
		$message = "";
	
		$post = $this->input->post();
		$userfile = $post['fileName'];
		$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		if($this->session->userdata('isUserLoggedIn') && $this->session->userdata('status')==1){	
			$config['upload_path']          = './uploads/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 10000;
			$config['max_width']            = 15024;
			$config['max_height']           = 20368;
				
			$this->load->library('upload', $config);
			$blogId = 0;
			if($userfile != null){
				if ( ! $this->upload->do_upload('userfile')){
					$blog_list = array();
					$limit = 10;
						
					$blog_list = $this->getBlogList($limit);
					$data['ar_daily_blogs'] = $blog_list;
					$data['error'] = $this->upload->display_errors();
					$data['success'] = '';
					$this->load->view('newblog_form', $data);
					//header('Location: /arifrahman/upload');
				}	else {
					$image_data = array('upload_data' => $this->upload->data());
					$data = $this->upload->data();
						
					$this->load->model('Daily_Blog_model');
					$post['imageLink'] = $data['file_name'];
					$file = 'uploads/'.$data['file_name'];
						
					//indicate the path and name for the new resized file
					$resizedFile = 'uploads/minimized/'.$data['file_name'];
						
					//call the function (when passing path to pic)
					$this->smart_resize_image($file , null, 1024 , 768 , false , $resizedFile , false , false ,100 );
						
					try {
						$blogId = $this->Daily_Blog_model->insert($post);
						
					} catch (Exception $e) {
						echo 'error to insert data';
					}
						
					//$this->load->view('post_success', $image_data);
					//header('Location: /arifrahman/newblog/post_success');
					$blog_list = array();
					$limit = 10;
						
					$blog_list = $this->getBlogList($limit);
					$data['ar_daily_blogs'] = $blog_list;
					$data['error'] = '';
					$data['success'] = 'Successfully Blog Published.';
						
					$this->load->view('newblog_form', $data);
				}
			}else{
				$this->load->model('Daily_Blog_model');
				try {
					$post['imageLink'] = '';
					$blogId = $this->Daily_Blog_model->insert($post);
				} catch (Exception $e) {
					echo 'error to insert data';
				}
					
				//$this->load->view('post_success', $image_data);
				//header('Location: /arifrahman/newblog/post_success');
				$blog_list = array();
				$limit = 10;
					
				$blog_list = $this->getBlogList($limit);
				$data['ar_daily_blogs'] = $blog_list;
				$data['error'] = '';
				$data['success'] = 'Successfully Blog Published.';
					
				$this->load->view('newblog_form', $data);
				//header('Location: /arifrahman/upload');
			}
				
				
		}else{
	
			$blog_list = array();
			$limit = 10;
				
			$blog_list = $this->getBlogList($limit);
			$data['ar_daily_blogs'] = $blog_list;
			$data['error'] = 'Sorry You are not permitted to publish any post.';
			$data['success'] = '';
				
			$this->load->view('newblog_form', $data);
			//header('Location: /arifrahman/upload');
				
		}
			
	
	}
	public function edit($blogId){
		$blog_list = array();
		$limit = 10;
		$data = array();
		
		$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		
		$blog_list = $this->getBlogList($limit);
		$data['ar_daily_blogs'] = $blog_list;
		$data['error'] = '';
		$data['success'] = '';
		
		$blog = $this->getBlog($blogId);
		$data['ar_blog'] = $blog;
		
		$this->load->view('edit_form', $data);
	}

	public function update(){
		$post = $this->input->post();
		$blogId = $post['blogId'];
		
		$userfile = $post['fileName'];

		$data = array();
		$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		
		if($this->session->userdata('isUserLoggedIn') && $this->session->userdata('status')==1){
			$config['upload_path']          = './uploads/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 10000;
			$config['max_width']            = 15024;
			$config['max_height']           = 20368;
	
			$this->load->library('upload', $config);
			if($userfile != null){
				if ( ! $this->upload->do_upload('userfile')){
					$blog_list = array();
					$limit = 10;
				
					$blog_list = $this->getBlogList($limit);
					
					$data['ar_daily_blogs'] = $blog_list;
					$data['error'] = $this->upload->display_errors();
					$data['success'] = '';
					$this->load->view('newblog_form', $data);
					//header('Location: /arifrahman/upload');
				}
				else {
					$image_data = array('upload_data' => $this->upload->data());
					
					$data = $this->upload->data();
					$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
				
					$this->load->model('Daily_Blog_model');
					$dailyBlog = $this->Daily_Blog_model->findOne($blogId);
					$blog = $dailyBlog[0];
					//print_r($blog);
					$imageLink = $blog->imageLink;
					if ($imageLink!=null){
						unlink('./uploads/'.$imageLink);
						unlink('./uploads/minimized/'.$imageLink);
					}
					$post['imageLink'] = $data['file_name'];
					$file = 'uploads/'.$data['file_name'];
				
					//indicate the path and name for the new resized file
					$resizedFile = 'uploads/minimized/'.$data['file_name'];
				
					//call the function (when passing path to pic)
					$this->smart_resize_image($file , null, 1024 , 768 , false , $resizedFile , false , false ,100 );
				
					try {
						$blogId = $this->Daily_Blog_model->update($post);
						
					} catch (Exception $e) {
						echo 'error to insert data';
					}
				
					//$this->load->view('post_success', $image_data);
					//header('Location: /arifrahman/newblog/post_success');
					$blog_list = array();
					$limit = 10;
				
					$blog_list = $this->getBlogList($limit);
					$data['ar_daily_blogs'] = $blog_list;
					$data['error'] = '';
					$data['success'] = 'Successfully Blog Published.';
				
					$this->load->view('newblog_form', $data);
				}
			}else{
					$this->load->model('Daily_Blog_model');
					$dailyBlog = $this->Daily_Blog_model->findOne($blogId);
					$blog = $dailyBlog[0];
					//print_r($blog);
					$imageLink = $blog->imageLink;
					if ($imageLink!=null){
						unlink('./uploads/'.$imageLink);
						unlink('./uploads/minimized/'.$imageLink);
					}
					$post['imageLink'] = '';
					
					try {
						$blogId = $this->Daily_Blog_model->update($post);
						
					} catch (Exception $e) {
						echo 'error to insert data';
					}
					
				//$this->load->view('post_success', $image_data);
				//header('Location: /arifrahman/newblog/post_success');
				$blog_list = array();
				$limit = 10;
					
				$blog_list = $this->getBlogList($limit);
				$data['ar_daily_blogs'] = $blog_list;
				$data['error'] = '';
				$data['success'] = 'Successfully Blog Updated.';
					
				$this->load->view('newblog_form', $data);
			}
		
		}else{
			$blog_list = array();
			$limit = 10;
	
			$blog_list = $this->getBlogList($limit);
			$data['ar_daily_blogs'] = $blog_list;
			$data['error'] = 'Sorry You are not permitted to publish any post.';
			$data['success'] = '';
	
			$this->load->view('newblog_form', $data);
			//header('Location: /arifrahman/upload');
		}
			
	
	}
	public function delete($blogId){
        $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->database();
		$this->load->model('Daily_Blog_model');
		$post = $this->input->post();
		try {
			$post = $this->input->post();
			if($this->session->userdata('isUserLoggedIn') && $this->session->userdata('status')==1){
				$dailyBlog = array();
				$dailyBlog = $this->Daily_Blog_model->findOne($blogId);
				$blog = $dailyBlog[0];
				//print_r($blog);
				$imageLink = $blog->imageLink;
				if ($imageLink!=null) {
					unlink('./uploads/'.$imageLink);
					unlink('./uploads/minimized/'.$imageLink);
				}
					
				$this->Daily_Blog_model->delete($blogId);
				$blog_list = array();
				$limit = 10;

				$blog_list = $this->getBlogList($limit);
				$data['ar_daily_blogs'] = $blog_list;
				$data['error'] = '';
				$data['success'] = 'Successfully Blog Deleted.';

				$this->load->view('newblog_form', $data);
			}else {
				$blog_list = array();
				$limit = 10;

				$blog_list = $this->getBlogList($limit);
				$data['ar_daily_blogs'] = $blog_list;
				$data['error'] = '';
				$data['success'] = 'You are not permitted';

				$this->load->view('newblog_form', $data);

			}
		} catch (Exception $e) {
			echo 'error to delete data';
		}
	
		//header('Location: /arifrahman/Newblog');
		$this->load->view('newblog_form', $data);
	}
	public function readList($limit){
		$this->load->model('Daily_Blog_model');
		$this->load->helper('url');
		try {
			$blog_list = $this->Daily_Blog_model->get($limit);
		} catch (Exception $e) {
			echo 'error to insert data';
		}
	
		return $blog_list;
	
	}
	public function read($blogId){
		$this->load->model('Daily_Blog_model');
		$this->load->helper('url');
		$blog = array();
		try {
			$blog = $this->Daily_Blog_model->findOne($blogId);
		} catch (Exception $e) {
			echo 'error to fetch data';
		}
		return $blog;
	}

	public function post_success(){
		$message_array = array('error' => ' ','success' => "Your Post Successfully Published");
		$this->load->view('newblog_form', $message_array);
	}
	public function deleteBlog(){
		$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->database();
		$this->load->model('Daily_Blog_model');
		$post = $this->input->post();
		try {
			$post = $this->input->post();
			if($this->session->userdata('isUserLoggedIn') && $this->session->userdata('status')==1){
				$blogId = $post['blogId'];
				$dailyBlog = array();
				$dailyBlog = $this->Daily_Blog_model->findOne($blogId);
				$blog = $dailyBlog[0];
				//print_r($blog);
				$imageLink = $blog->imageLink;
				if ($imageLink!=null) {
					unlink('./uploads/'.$imageLink);
					unlink('./uploads/minimized/'.$imageLink);
				}
					
				$this->Daily_Blog_model->delete($blogId);
				$blog_list = array();
				$limit = 10;
	
				$blog_list = $this->getBlogList($limit);
				$data['ar_daily_blogs'] = $blog_list;
				$data['error'] = '';
				$data['success'] = 'Successfully Blog Deleted.';
	
				$this->load->view('newblog_form', $data);
			}else {
				$blog_list = array();
				$limit = 10;
	
				$blog_list = $this->getBlogList($limit);
				$data['ar_daily_blogs'] = $blog_list;
				$data['error'] = '';
				$data['success'] = 'You are not permitted';
	
				$this->load->view('newblog_form', $data);
	
			}
		} catch (Exception $e) {
			echo 'error to delete data';
		}
	}
	public function uploaded(){
		$list = $this->ftp->list_files('/arifrahman/uploads/');
		print_r($list);
	}
	/**
	 * easy image resize function
	 * @param  $file - file name to resize
	 * @param  $string - The image data, as a string
	 * @param  $width - new image width
	 * @param  $height - new image height
	 * @param  $proportional - keep image proportional, default is no
	 * @param  $output - name of the new file (include path if needed)
	 * @param  $delete_original - if true the original image will be deleted
	 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
	 * @param  $quality - enter 1-100 (100 is best quality) default is 100
	 * @param  $grayscale - if true, image will be grayscale (default is false)
	 * @return boolean|resource
	 */
	public function smart_resize_image($file,
			$string             = null,
			$width              = 0,
			$height             = 0,
			$proportional       = false,
			$output             = 'file',
			$delete_original    = true,
			$use_linux_commands = false,
			$quality            = 100,
			$grayscale          = false
	) {
	
		if ( $height <= 0 && $width <= 0 ) return false;
		if ( $file === null && $string === null ) return false;
		# Setting defaults and meta
		$info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
		$image                        = '';
		$final_width                  = 0;
		$final_height                 = 0;
		list($width_old, $height_old) = $info;
		$cropHeight = $cropWidth = 0;
		# Calculating proportionality
		if ($proportional) {
			if      ($width  == 0)  $factor = $height/$height_old;
			elseif  ($height == 0)  $factor = $width/$width_old;
			else                    $factor = min( $width / $width_old, $height / $height_old );
			$final_width  = round( $width_old * $factor );
			$final_height = round( $height_old * $factor );
		}
		else {
			$final_width = ( $width <= 0 ) ? $width_old : $width;
			$final_height = ( $height <= 0 ) ? $height_old : $height;
			$widthX = $width_old / $width;
			$heightX = $height_old / $height;
	
			$x = min($widthX, $heightX);
			$cropWidth = ($width_old - $width * $x) / 2;
			$cropHeight = ($height_old - $height * $x) / 2;
		}
		# Loading image to memory according to type
		switch ( $info[2] ) {
			case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
			default: return false;
		}
	
		# Making the image grayscale, if needed
		if ($grayscale) {
			imagefilter($image, IMG_FILTER_GRAYSCALE);
		}
	
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
			$transparency = imagecolortransparent($image);
			$palletsize = imagecolorstotal($image);
			if ($transparency >= 0 && $transparency < $palletsize) {
				$transparent_color  = imagecolorsforindex($image, $transparency);
				$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			}
			elseif ($info[2] == IMAGETYPE_PNG) {
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
	
	
		# Taking care of original, if needed
		if ( $delete_original ) {
			if ( $use_linux_commands ) exec('rm '.$file);
			else @unlink($file);
		}
		# Preparing a method of providing result
		switch ( strtolower($output) ) {
			case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
				break;
			case 'file':
				$output = $file;
				break;
			case 'return':
				return $image_resized;
				break;
			default:
				break;
		}
	
		# Writing image according to type to the output destination and image quality
		switch ( $info[2] ) {
			case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
			case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
			case IMAGETYPE_PNG:
				$quality = 9 - (int)((0.9*$quality)/10.0);
				imagepng($image_resized, $output, $quality);
				break;
			default: return false;
		}
		return true;
	}
}
