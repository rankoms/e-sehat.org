<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman_model extends MY_Model {
	
	protected $_table_name = 'shipping';
	protected $_primary_key = 'shipping_id';
	protected $_order_by = 'shipping_id';
	protected $_order_by_type = 'DESC';

	function __construct() {
		parent::__construct();
	}	
}