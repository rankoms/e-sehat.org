<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Halaman extends Frontend_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function detil(){
		$data = array();

		/* untuk third part, pembuatan component 
		dan lain sebagainya ...  */
		$third_party = APPPATH.'third_party';
		$list_files = scandir($third_party);
		$class_name = $this->uri->segment(2); 	

		if(in_array($class_name.'.php', $list_files)){	
			require_once APPPATH.'third_party/'.$class_name.'.php';			 				
			$action = new $class_name();
			$method = $this->uri->segment(3);
			$action->$method();
			$this->site->_isDetail = TRUE;
			$this->site->view('halaman', $data);
		}
		else{
			$this->site->_isDetail = TRUE;		
			$this->post->get_post_detail();	
			$this->site->view('halaman', $data);
		}
				
		
	}

	public function get_halaman(){
		// if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		// 	$record = $this->Halaman_model->get_by(array('post_type' => 'halaman'));
		// 	echo json_encode(array('record' => $record));
		// }
		echo "randy ganteng";
	}
}
