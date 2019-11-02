<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan_model extends MY_Model {
	
	protected $_table_name = 'pesan';
	protected $_primary_key = 'pesan_id';
	protected $_order_by = 'pesan_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	public $rules = array(
		'pesan_nama' => array(
			'field' => 'pesan_nama', 
			'label' => 'Nama', 
			'rules' => 'trim|required'
		),
		'pesan_email' => array(
			'field' => 'pesan_email', 
			'label' => 'Email', 
			'rules' => 'trim|required'
		),
		'pesan_pesan' => array(
			'field' => 'pesan_pesan', 
			'label' => 'Pesan', 
			'rules' => 'trim|required|min_length[10]'
		),
	);	

	function __construct() {
		parent::__construct();
	}	
}