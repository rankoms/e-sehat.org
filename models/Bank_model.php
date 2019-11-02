<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends MY_Model {
	
	protected $_table_name = 'bank';
	protected $_primary_key = 'bank_id';
	protected $_order_by = 'bank_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules = array(
		'bank_norek' => array(
			'field' => 'bank_norek', 
			'label' => 'No rekening', 
			'rules' => 'trim|required|numeric'
		)
	);	

	function __construct() {
		parent::__construct();
	}	
}