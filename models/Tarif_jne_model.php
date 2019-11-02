<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tarif_jne_model extends MY_Model {
	
	protected $_table_name = 'tarif_jne';
	protected $_primary_key = 'tarif_id';
	protected $_order_by = 'tarif_id';
	protected $_order_by_type = 'DESC';

	function __construct() {
		parent::__construct();
	}	
}