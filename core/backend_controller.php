<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_Controller extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'security' ,'date', 'admin_helper', 'front_helper', 'backend_helper','tanggal_helper'));
		$this->load->library(array('form_validation','Recaptcha'));
		$this->load->model(array('Organisasi_model', 'Modul_model', 'Materi_model'));
		
		$this->site->side = 'backend';
		$this->site->template = 'templatevamp';

		$this->site->is_logged_in();
	}

}