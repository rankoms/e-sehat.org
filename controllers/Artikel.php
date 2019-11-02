<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends Frontend_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();

		$pencarian = $this->input->get('pencarian', TRUE);
		
		if($pencarian){
			$this->site->_isSearch = TRUE;
			$this->post->post_search_keyword = $pencarian;
			$this->site->view('pencarian', $data);
		}
		else{
			$this->site->_isHome = TRUE;
			$this->site->view('index', $data);			
		}
	}

	public function about(){
		$this->site->view('about');	
	}	

	public function kategori(){
		$data = array();
		$this->site->_isCategory = TRUE;
		$this->site->view('kategori_artikel', $data);
	}	

	public function detil(){
		$data = array();
		$this->site->_isDetail = TRUE;		
		$this->post->get_post_detail();
		$this->site->view('artikel', $data);
	}
}
