<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Video_model extends MY_Model {
	
	protected $_table_name = 'video';
	protected $_primary_key = 'video_id';
	protected $_order_by = 'video_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	public $rules = array(
		// 'post_title' => array(
		// 	'field' => 'post_title', 
		// 	'label' => 'Judul Artikel', 
		// 	'rules' => 'trim|required'
		// )
	);	

	function __construct() {
		parent::__construct();
	}	
		
}