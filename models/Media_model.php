<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends MY_Model {
	
	protected $_table_name = 'post';
	protected $_primary_key = 'post_ID';
	protected $_order_by = 'post_ID';
	protected $_order_type = 'DESC';
	protected $_type = 'attachment';


	function __construct() {
		parent::__construct();
	}	
}