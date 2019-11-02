<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_modul_model extends MY_Model {
	
	protected $_table_name = 'log_modul';
	protected $_primary_key = 'log_modul_id';
	protected $_order_by = 'log_modul_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	// public $rules = array(
	// 	'bank_norek' => array(
	// 		'field' => 'bank_norek', 
	// 		'label' => 'No rekening', 
	// 		'rules' => 'trim|required|numeric'
	// 	)
	// );	

	function __construct() {
		parent::__construct();
	}	
}