<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_detil_model extends MY_Model {
	
	protected $_table_name = 'transaction_detail';
	protected $_primary_key = 'detail_id';
	protected $_order_by = 'detail_id';
	protected $_order_by_type = 'DESC';

	function __construct() {
		parent::__construct();
	}	
}