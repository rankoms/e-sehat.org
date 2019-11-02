<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site{

	public $side;
	public $template;
	public $template_setting;
	public $website_setting;
	public $_isHome = FALSE;
	public $_isCategory = FALSE;
	public $_isSearch = FALSE;
	public $_isDetail = FALSE;
	public $provinsi = array(
		0	=> 'Pilih Provinsi',
	    11	=> 'Aceh',
	    12	=> 'Sumatera Utara',
	    13	=> 'Sumatera Barat',
	    14	=> 'Riau',
	    15	=> 'Jambi',
	    16 	=> 'Sumatera Selatan',
	    17 	=> 'Bengkulu',
	    18 	=> 'Lampung',
	    19 	=> 'Kepulauan Bangka Belitung',
	    21 	=> 'Kepulauan Riau',
	    31 	=> 'DKI Jakarta',
	    32 	=> 'Jawa Barat',
	    33 	=> 'Jawa Tengah',
	    34 	=> 'DI Yogyakarta',
	    35 	=> 'Jawa Timur',
	    36	=> 'Banten',
	    51	=> 'Bali',
	    52	=> 'Nusa Tenggara Barat',
	    53	=> 'Nusa Tenggara Timur',
	    61	=> 'Kalimantan Barat',
	    62	=> 'Kalimantan Timur',
	    63	=> 'Kalimantan Selatan',
	    64	=> 'Kalimantan Timur',
	    65	=> 'Kalimantan Utara',
	    71	=> 'Sulawesi Utara',
	    72	=> 'Sulawesi Tengah',
	    73	=> 'Sulawesi Selatan',
	    74	=> 'Sulawesi Tenggara',
	    75	=> 'Gorontalo',
	    76	=> 'Sulawesi Barat',
	    81	=> 'Maluku',
	    82	=> 'Maluku Utara',
	    91	=> 'Papua Barat',
	    94	=> 'Papua',
	);	
	
	function view($pages, $data=NULL){
		$_this =& get_instance();

		$data ? 
			$_this->load->view($this->side.'/'.$this->template.'/'.$pages, $data)
							// backend/blue/index
				: 
					$_this->load->view($this->side.'/'.$this->template.'/'.$pages);
	}

	function is_logged_in(){
		$_this =& get_instance();

		$user_session = $_this->session->userdata;

		if($this->side == 'backend'){
			if($_this->uri->segment(2) == 'login'){
				if(isset($user_session['logged_in']) && $user_session['logged_in'] == TRUE && ($user_session['group'] == 'admin' || $user_session['group'] == 'instruktur' || $user_session['group'] == 'adminop')){
					redirect(set_url('dashboard'));
				}
			}
			else{
				if(!isset($user_session['logged_in']) || $user_session['group'] == 'murid' || ($user_session['group'] != 'admin' && $user_session['group'] == 'instruktur' && $user_session['group'] == 'adminop')){
					$_this->session->sess_destroy();
					redirect(base_url().'login');
				}
			}
		}
		else{
			if(!isset($user_session['logged_in'])){
				$_this->session->sess_destroy();
				redirect(set_url('user/login'));
			}
		}
	}

	function visitor_log(){
		
		$_this =& get_instance();	
		$_this->load->library('user_agent');
		$_this->load->model('Statistik_model');
		
		if((!$_this->session->userdata('user_online')) ){
			$sessId = session_id();
			
			//$ip = $_SERVER['REMOTE_ADDR'];
			$ip = '112.215.36.142';
			//$ip = '127.0.0.1';
			$date = date('Y-m-d H:i:s');
			$agent = $_this->agent->agent_string();
			(!empty($_SERVER['HTTP_REFERER'])) ? $reff = $_SERVER['HTTP_REFERER'] : $reff = '';
			
			@$var = file_get_contents("http://ip-api.com/json/$ip");
			$var = json_decode($var);

			$visitorLogs = array(
					'visitor_IP' => $var->query, 
					'visitor_IP' => $ip, 
					'visitor_referer' => $reff, 
					'visitor_date' => $date, 
					'visitor_agent' => $agent, 
					'visitor_session' => $sessId,
					'visitor_city' => @$var->city, 	
					'visitor_region' => @$var->regionName, 	
					'visitor_country' => @$var->country, 	
					'visitor_os' => $_this->agent->platform(),
					'visitor_browser' => $_this->agent->browser().' '.$_this->agent->version(),
					'visitor_isp' => @$var->isp
			);
								
			$_this->Statistik_model->insert($visitorLogs);
			
			$_this->session->set_userdata(array('user_online' => session_id() ));			
		}
		
		return TRUE;		
	}	


	function create_dir(){
		global $SConfig;
		$_this =& get_instance();
		
		$datepath = date('d-M-Y');
		$yeardir = date('Y');
		$monthdir = date('M');
		$datedir = date('d');
		// $path --> "d:/xampp/htdocs/kaffahv3";
		$path = $SConfig->_document_root.'/uploads';
		
		/* jika folder user ada */
		if(is_dir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID'))){
			if(!is_dir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/')){
				mkdir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID'), 0755);
				touch($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.'index.php');
			}
		}
		
		else{
			mkdir($path.'/'.$yeardir, 0755);
			touch($path.'/'.$yeardir.'/'.'index.php');
			mkdir($path.'/'.$yeardir.'/'.$monthdir, 0755);
			touch($path.'/'.$yeardir.'/'.$monthdir.'/'.'index.php');
			mkdir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir, 0755);
			touch($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.'index.php');
			mkdir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID'), 0755);
			touch($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/index.php');
		}		
	}	

	function media_upload_config(){
		global $SConfig;
		$_this =& get_instance();		
		$path = $SConfig->_document_root.'/uploads/';
		$yeardir = date('Y');
		$monthdir = date('M');
		$datedir = date('d');
		$realpath = $path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID');
		
		$config['upload_path'] = $realpath;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
		$config['max_size']	= '25100';
		$config['max_width']  = '25100';
		$config['max_height']  = '25100';
		
		return $config;		
	}	

	function resize_img($image=NULL, $width=NULL, $height=NULL, $type=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->library('image_lib'); 
		
		/* definite globalvar */
		$hostname = $SConfig->_host_name; 
		$docroot = $SConfig->_document_root;
		$siteurl = $SConfig->_site_url;
		
		/* jika kosong maka jadikan nilai default */
		(!empty($width)) ? $width_image = $width : $width_image = 75;
		(!empty($height)) ? $height_image = $height : $height_image = 50;		
		
		/* change path to directory */
		$directory = str_replace($siteurl,$docroot,$image);
		
		/* change files name to new name */
		$get_latest_slash = strrpos($directory, '/');
		$file_name = substr($directory,	$get_latest_slash+1 );
		$extension = substr($file_name, strrpos($file_name, '.'));
		$file_name_without_ext = substr($directory,	$get_latest_slash+1, strrpos($file_name, '.') );
		$new_name = $file_name_without_ext.'_'.$width_image.'x'.$height_image.$extension;
		
		/* path baru */
		$new_path = str_replace($file_name, $new_name, $directory);
		
		/* new url */
		$new_url = str_replace($docroot,$siteurl, $new_path);
		
		$file_is_exist = file_exists($new_path);
		
		if($file_is_exist == TRUE){
			return $new_url;
		}
		else{
			/* configuration */
			$config['image_library'] = 'gd2';
			$config['source_image']	= $directory;
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = '';
			$config['maintain_ratio'] = TRUE;
			
			if(file_exists($config['source_image'])){
				$img_size = getimagesize($config['source_image']);
				$t_ratio = $width/$height;
		      	$o_width = $img_size[0];
		      	$o_height = $img_size[1];
			
				if ((!empty($img_size)) && ($t_ratio > $o_width/$o_height)){
					$config['width'] = $width;
					$config['height'] = round( $width * ($o_height / $o_width));
					$y_axis = round(($config['height']/2) - ($height/2));
					$x_axis = 0;
				}
				else{
					$config['width'] = round( $height * ($o_width / $o_height));
					$config['height'] = $height;
					$y_axis = 0;
					$x_axis = round(($config['width']/2) - ($width/2));
				}				
			}
			
			else{
				$config['width'] = $width;
				$config['height'] = $height;
				$y_axis = 0;
				$x_axis = round(($config['width']/2) - ($width/2));				
			}

	  		
			$config['new_image'] = $new_path;
			
			/* load library image */
			$_this->image_lib->clear();
			$_this->image_lib->initialize($config);
			
			/* jika tidak ada masalah maka lakukan resize */
			$_this->image_lib->resize();
			
			$source_img01 = $config['new_image'];
			$config['image_library'] = 'gd2';
			$config['source_image'] = $source_img01;
			$config['create_thumb'] = false;
			$config['maintain_ratio'] = false;
			$config['width'] = $width;
			$config['height'] = $height;
			$config['y_axis'] = $y_axis ;
			$config['x_axis'] = $x_axis ;
			
			$_this->image_lib->clear();
			$_this->image_lib->initialize($config);
			$_this->image_lib->crop();
			/* return value */			
		}		

		return $new_url;
	}

	function is_url_admin(){
		$_this =& get_instance();
		if($_this->uri->total_segments() == 1 && $_this->uri->segment(1) == 'admin'){
			redirect(set_url('dashboard'));
		}
	}
}