<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends Frontend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
	}

	public function index(){
		$this->site->view('error404');	
	}

}