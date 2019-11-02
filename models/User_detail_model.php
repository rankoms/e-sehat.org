<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_detail_model extends MY_Model {
	
	protected $_table_name = 'user_detail';
	protected $_primary_key = 'user_detail_ID';
	public $rules = array();	

	function __construct() {
		parent::__construct();
	}	
	
}