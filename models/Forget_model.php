<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Forget_model extends MY_Model {
	
	protected $_table_name = 'forget_password';
	protected $_primary_key = 'forget_key';
	protected $_order_by = 'forget_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	public $rules = array(
		'post_title' => array(
			'field' => 'post_title', 
			'label' => 'Judul Artikel', 
			'rules' => 'trim|required'
		)
	);	

	function __construct() {
		parent::__construct();
	}	
	
	
	function get_forget_password($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL, $username = NULL){
		$this->db->select_max('forget_id');
		$this->db->select('{PRE}user.*');
		$this->db->join('{PRE}user', '{PRE}user.email  = {PRE}forget_password.forget_email', 'LEFT' );
		// $this->db->where('forget_key', $key);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

}