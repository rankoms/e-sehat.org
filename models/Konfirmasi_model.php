<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasi_model extends MY_Model {
	
	protected $_table_name = 'confirmation';
	protected $_primary_key = 'confirm_id';
	protected $_order_by = 'confirm_id';
	protected $_order_by_type = 'DESC';
	
	function __construct() {
		parent::__construct();
	}	


}