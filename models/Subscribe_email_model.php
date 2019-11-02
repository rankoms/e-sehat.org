<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe_email_model extends MY_Model {
	
	protected $_table_name = 'subs_email';
	protected $_primary_key = 'subs_email_id';
	protected $_order_by = 'subs_email_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';



	function __construct() {
		parent::__construct();
	}	
}