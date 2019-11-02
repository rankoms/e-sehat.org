<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profesi_model extends MY_Model {
	
	protected $_table_name = 'profesi';
	protected $_primary_key = 'profesi_id';
	protected $_order_by = 'profesi_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules = array(
		'profesi_nama' => array(
			'field' => 'profesi_nama', 
			'label' => 'Nama Coupon', 
			'rules' => 'trim|required'
		)
	);	

	function __construct() {
		parent::__construct();
	}	
}