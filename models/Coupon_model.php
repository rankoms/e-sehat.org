<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends MY_Model {
	
	protected $_table_name = 'coupon';
	protected $_primary_key = 'coupon_id';
	protected $_order_by = 'coupon_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules = array(
		'coupon_nama' => array(
			'field' => 'coupon_nama', 
			'label' => 'Nama Coupon', 
			'rules' => 'trim|required'
		)
	);	

	function __construct() {
		parent::__construct();
	}	
}